<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Handle Google linking mode (untuk user yang sudah login)
            if (session('google_link_mode')) {
                session()->forget('google_link_mode');
                return $this->handleGoogleLinking($googleUser);
            }

            // Check if user exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user && $user->is_verified) {
                // User sudah ada dan terverifikasi - langsung login
                $this->updateUserWithGoogleData($user, $googleUser);
                Auth::login($user);

                $user->last_login_at = now();
                $user->save();

                return $this->redirectAfterLogin($user, 'Selamat datang kembali!');
            } else if ($user && !$user->is_verified) {
                // User ada tapi belum terverifikasi - hapus akun lama
                $user->delete();
            }

            // User baru - REDIRECT KE PEMILIHAN USER TYPE DULU
            return $this->redirectToUserTypeSelection($googleUser);
        } catch (\Exception $e) {
            Log::error('Google OAuth Error: ' . $e->getMessage());

            return redirect()->route('login')
                ->withErrors(['google' => 'Login dengan Google gagal. Silakan coba lagi atau gunakan email dan password.']);
        }
    }

    private function redirectToUserTypeSelection($googleUser)
    {
        // Simpan data Google sementara di session
        $tempGoogleData = [
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
        ];

        Session::put('temp_google_data', $tempGoogleData);
        Session::put('temp_google_expires_at', now()->addMinutes(60)); // 60 menit untuk pilih user type

        return redirect()->route('google.user.type.selection', ['email' => $googleUser->getEmail()])
            ->with('success', 'Halo ' . $googleUser->getName() . '! Silakan pilih tipe akun yang ingin dibuat.');
    }

    private function getUserTypeLabel($userType)
    {
        return match ($userType) {
            'konsumen' => 'pembeli',
            'produsen' => 'penjual/produsen',
            'kurir' => 'kurir',
            default => 'pengguna'
        };
    }

    /**
     * Inisiasi registrasi Google dengan OTP
     */
    private function initiateGoogleRegistrationWithOtp($googleUser, $userType = 'konsumen', $additionalData = [])
    {
        // Hapus OTP lama untuk email ini
        Otp::where('email', $googleUser->getEmail())->delete();

        // Download avatar Google terlebih dahulu
        $avatarPath = null;
        if ($googleUser->getAvatar()) {
            $avatarPath = $this->downloadGoogleAvatar($googleUser->getAvatar(), $googleUser->getId());
        }

        // Simpan data Google user di session (sementara)
        $googleRegistrationData = [
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar_path' => $avatarPath,
            'avatar_url' => $googleUser->getAvatar(),
            'user_type' => $userType,
            'is_google_oauth' => true,
            'login_provider' => 'google',
            'phone' => $additionalData['phone'] ?? null,
            'address' => $additionalData['address'] ?? null,
            'notification_preferences' => [
                'email_notifications' => true,
                'order_updates' => true,
                'promotion_notifications' => false,
                'newsletter' => false,
            ]
        ];

        // Simpan di session dengan expiry (60 menit)
        Session::put('google_registration_data', $googleRegistrationData);
        Session::put('google_registration_expires_at', now()->addMinutes(60));

        // Generate OTP
        $otp = sprintf('%06d', mt_rand(1, 999999));

        // Simpan OTP ke database
        Otp::create([
            'email' => $googleUser->getEmail(),
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Kirim email OTP
        try {
            Mail::to($googleUser->getEmail())->send(new OtpMail($otp, $googleUser->getName()));
        } catch (\Exception $e) {
            Log::error('Failed to send Google OTP email: ' . $e->getMessage());
        }

        // Redirect ke halaman verifikasi OTP Google
        return redirect()->route('google.otp.verify', ['email' => $googleUser->getEmail()])
            ->with('success', 'Data berhasil disimpan! Silakan verifikasi email Anda dengan kode OTP untuk melengkapi registrasi sebagai ' . $this->getUserTypeLabel($userType) . '.');
    }

    /**
     * Show Google OTP verification form
     */
    public function showGoogleOtpForm(Request $request)
    {
        $email = $request->get('email');

        // Cek apakah ada data registrasi Google di session
        $googleRegistrationData = Session::get('google_registration_data');
        $expiresAt = Session::get('google_registration_expires_at');

        // Debug logging
        Log::info('Google OTP Form Access', [
            'email' => $email,
            'has_registration_data' => !is_null($googleRegistrationData),
            'has_expires_at' => !is_null($expiresAt),
            'expires_at' => $expiresAt ? $expiresAt->toDateTimeString() : null,
            'now' => now()->toDateTimeString(),
            'is_expired' => $expiresAt ? now()->greaterThan($expiresAt) : null
        ]);

        if (!$email || !$googleRegistrationData || !$expiresAt || now()->greaterThan($expiresAt)) {
            // Data registrasi tidak ada atau sudah expired
            Session::forget(['google_registration_data', 'google_registration_expires_at']);
            return redirect()->route('login')
                ->withErrors(['email' => 'Session registrasi Google telah berakhir. Silakan login ulang dengan Google.']);
        }

        // Pastikan email di session sama dengan yang di URL
        if ($googleRegistrationData['email'] !== $email) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Email tidak sesuai dengan data registrasi Google.']);
        }

        return view('auth.verify-google-otp', compact('email', 'googleRegistrationData'));
    }

    /**
     * Verify Google OTP and complete registration
     */
    public function verifyGoogleOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        // Cek data registrasi Google di session
        $googleRegistrationData = Session::get('google_registration_data');
        $expiresAt = Session::get('google_registration_expires_at');

        if (!$googleRegistrationData || !$expiresAt || now()->greaterThan($expiresAt)) {
            Session::forget(['google_registration_data', 'google_registration_expires_at']);
            return redirect()->route('login')
                ->withErrors(['otp' => 'Session registrasi Google telah berakhir. Silakan login ulang dengan Google.']);
        }

        // Verifikasi OTP
        $otpRecord = Otp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        // Buat user setelah OTP terverifikasi
        $userData = [
            'name' => $googleRegistrationData['name'],
            'email' => $googleRegistrationData['email'],
            'password' => Hash::make(Str::random(32)),
            'user_type' => $googleRegistrationData['user_type'],
            'is_verified' => true,
            'email_verified_at' => now(),
            'avatar' => $googleRegistrationData['avatar_path'],
            'google_id' => $googleRegistrationData['google_id'],
            'login_provider' => $googleRegistrationData['login_provider'],
            'phone' => $googleRegistrationData['phone'],
            'address' => $googleRegistrationData['address'],
            'notification_preferences' => $googleRegistrationData['notification_preferences'],
        ];

        $userData['profile_completed'] = $this->checkProfileCompletion($userData);
        $user = User::create($userData);

        // Hapus OTP dan session data
        $otpRecord->delete();
        Session::forget(['google_registration_data', 'google_registration_expires_at']);

        // Login user
        Auth::login($user);
        $user->last_login_at = now();
        $user->save();

        // Welcome message
        return $this->redirectAfterLogin($user, 'Selamat datang di Zynera! Akun Google Anda telah berhasil dibuat dan diverifikasi.');
    }

    /**
     * Resend Google OTP
     */
    public function resendGoogleOtp(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        // Rate limiting
        $key = 'google_otp_resend:' . $request->email;
        $attempts = cache()->get($key, 0);

        if ($attempts >= 3) {
            return back()->withErrors(['email' => 'Terlalu banyak permintaan kirim ulang. Silakan tunggu 10 menit.']);
        }

        // Cek session
        $googleRegistrationData = Session::get('google_registration_data');
        $expiresAt = Session::get('google_registration_expires_at');

        if (!$googleRegistrationData || !$expiresAt || now()->greaterThan($expiresAt) || $googleRegistrationData['email'] !== $request->email) {
            Session::forget(['google_registration_data', 'google_registration_expires_at']);
            return redirect()->route('login')
                ->withErrors(['email' => 'Session registrasi Google telah berakhir. Silakan login ulang dengan Google.']);
        }

        // Generate OTP baru
        $otp = sprintf('%06d', mt_rand(1, 999999));

        // Update OTP
        Otp::updateOrCreate(
            ['email' => $request->email],
            ['otp' => $otp, 'expires_at' => now()->addMinutes(10)]
        );

        // Kirim email OTP
        try {
            Mail::to($request->email)->send(new OtpMail($otp, $googleRegistrationData['name']));

            // Increment attempts
            cache()->put($key, $attempts + 1, now()->addMinutes(10));

            return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            Log::error('Failed to resend Google OTP email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Gagal mengirim ulang kode OTP. Silakan coba lagi.']);
        }
    }

    /**
     * Handle Google linking process (TIDAK BERUBAH)
     */
    private function handleGoogleLinking($googleUser)
    {
        $currentUser = Auth::user();

        if (!$currentUser) {
            return redirect()->route('login')->withErrors([
                'google' => 'Anda harus login terlebih dahulu untuk menghubungkan akun Google.'
            ]);
        }

        // Check if this Google account is already linked to another user
        $existingGoogleUser = User::where('google_id', $googleUser->getId())
            ->where('id', '!=', $currentUser->id)
            ->first();

        if ($existingGoogleUser) {
            return redirect()->route('profile.edit')->withErrors([
                'google' => 'Akun Google ini sudah terhubung dengan akun lain.'
            ]);
        }

        // Link Google account
        $currentUser = User::find($currentUser->id);
        $currentUser->google_id = $googleUser->getId();
        $currentUser->login_provider = 'google';

        // Update avatar if current user doesn't have one
        if (!$currentUser->avatar && $googleUser->getAvatar()) {
            $avatarPath = $this->downloadGoogleAvatar($googleUser->getAvatar(), $googleUser->getId());
            if ($avatarPath) {
                $currentUser->avatar = $avatarPath;
            }
        }

        // Verify email if matches
        if ($currentUser->email === $googleUser->getEmail() && !$currentUser->email_verified_at) {
            $currentUser->email_verified_at = now();
            $currentUser->is_verified = true;
        }

        $currentUser->save();

        return redirect()->route('profile.edit')->with('success', 'Akun Google berhasil dihubungkan!');
    }

    /**
     * Update existing user with Google data (TIDAK BERUBAH)
     */
    private function updateUserWithGoogleData($user, $googleUser)
    {
        $user = User::find($user->id);

        // Update Google ID if not set
        if (!$user->google_id) {
            $user->google_id = $googleUser->getId();
            $user->login_provider = 'google';
        }

        // Update avatar if user doesn't have one and Google provides one
        if (!$user->avatar && $googleUser->getAvatar()) {
            $avatarPath = $this->downloadGoogleAvatar($googleUser->getAvatar(), $googleUser->getId());
            if ($avatarPath) {
                $user->avatar = $avatarPath;
            }
        }

        // Set default notification preferences if not set
        if (!$user->notification_preferences) {
            $user->notification_preferences = [
                'email_notifications' => true,
                'order_updates' => true,
                'promotion_notifications' => false,
                'newsletter' => false,
            ];
        }

        // Update profile completion status
        $user->profile_completed = $this->checkProfileCompletion($user->toArray());

        $user->save();
    }

    /**
     * Download and store Google avatar (TIDAK BERUBAH)
     */
    private function downloadGoogleAvatar($avatarUrl, $googleId)
    {
        try {
            // Get high quality avatar by modifying URL
            $avatarUrl = str_replace('s96-c', 's400-c', $avatarUrl);

            $avatarContent = file_get_contents($avatarUrl);

            if ($avatarContent) {
                $filename = 'google_avatar_' . $googleId . '_' . time() . '.jpg';
                $path = 'avatars/' . $filename;

                Storage::disk('public')->put($path, $avatarContent);

                return $path;
            }
        } catch (\Exception $e) {
            Log::error('Failed to download Google avatar: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Check if profile is completed (TIDAK BERUBAH)
     */
    private function checkProfileCompletion($userData)
    {
        $requiredFields = ['name', 'email'];
        $optionalFields = ['phone', 'address', 'avatar'];

        // Check required fields
        foreach ($requiredFields as $field) {
            if (empty($userData[$field])) {
                return false;
            }
        }

        // Check if at least 2 optional fields are filled
        $filledOptional = 0;
        foreach ($optionalFields as $field) {
            if (!empty($userData[$field])) {
                $filledOptional++;
            }
        }

        return $filledOptional >= 2;
    }

    /**
     * Redirect after successful login based on user type (TIDAK BERUBAH)
     */
    private function redirectAfterLogin(User $user, $message = null)
    {
        $redirectRoute = in_array($user->user_type, ['admin', 'produsen', 'kurir'])
            ? 'admin.dashboard'
            : 'home';

        if ($message) {
            return redirect()->route($redirectRoute)->with('success', $message);
        }

        return redirect()->route($redirectRoute);
    }

    /**
     * Unlink Google account (TIDAK BERUBAH)
     */
    public function unlinkGoogle(Request $request)
    {
        $user = $request->user();
        $user = User::find($user->id);

        // Check if user has password set (not OAuth-only)
        if (!$user->password || $user->login_provider === 'google') {
            return back()->withErrors([
                'google' => 'Tidak dapat memutus koneksi Google karena ini adalah satu-satunya metode login Anda. Silakan atur password terlebih dahulu.'
            ]);
        }

        $user->google_id = null;
        $user->login_provider = null;
        $user->save();

        return back()->with('success', 'Koneksi Google berhasil diputus.');
    }

    /**
     * Link Google account to existing account
     */
    public function linkGoogle(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $currentUser = $request->user();

            // Check if this Google account is already linked to another user
            $existingGoogleUser = User::where('google_id', $googleUser->getId())
                ->where('id', '!=', $currentUser->id)
                ->first();

            if ($existingGoogleUser) {
                return back()->withErrors([
                    'google' => 'Akun Google ini sudah terhubung dengan akun lain.'
                ]);
            }

            // Link Google account
            $currentUser = User::find($currentUser->id);
            $currentUser->google_id = $googleUser->getId();
            $currentUser->login_provider = 'google';

            // Update avatar if current user doesn't have one
            if (!$currentUser->avatar && $googleUser->getAvatar()) {
                $avatarPath = $this->downloadGoogleAvatar($googleUser->getAvatar(), $googleUser->getId());
                if ($avatarPath) {
                    $currentUser->avatar = $avatarPath;
                }
            }

            // Verify email if matches
            if ($currentUser->email === $googleUser->getEmail() && !$currentUser->email_verified_at) {
                $currentUser->email_verified_at = now();
                $currentUser->is_verified = true;
            }

            $currentUser->save();

            return back()->with('success', 'Akun Google berhasil dihubungkan!');
        } catch (\Exception $e) {
            Log::error('Google Link Error: ' . $e->getMessage());

            return back()->withErrors([
                'google' => 'Terjadi kesalahan saat menghubungkan akun Google.'
            ]);
        }
    }

    /**
     * Get Google OAuth URL for linking
     */
    public function getLinkUrl(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        // Set session flag untuk linking mode
        session(['google_link_mode' => true]);

        // Return URL ke Google OAuth
        $url = route('auth.google');

        return response()->json(['url' => $url]);
    }

    /**
     * Show user type selection for Google users
     */
    public function showGoogleUserTypeSelection(Request $request)
    {
        $email = $request->get('email');

        // Cek apakah ada data Google user di session
        $tempGoogleData = Session::get('temp_google_data');
        $expiresAt = Session::get('temp_google_expires_at');

        // Debug logging
        Log::info('Google User Type Selection Access', [
            'email' => $email,
            'has_temp_data' => !is_null($tempGoogleData),
            'has_expires_at' => !is_null($expiresAt),
            'expires_at' => $expiresAt ? $expiresAt->toDateTimeString() : null,
            'now' => now()->toDateTimeString(),
            'is_expired' => $expiresAt ? now()->greaterThan($expiresAt) : null
        ]);

        if (!$email || !$tempGoogleData || !$expiresAt || now()->greaterThan($expiresAt)) {
            Session::forget(['temp_google_data', 'temp_google_expires_at']);
            return redirect()->route('login')
                ->withErrors(['email' => 'Session Google telah berakhir. Silakan login ulang dengan Google.']);
        }

        if ($tempGoogleData['email'] !== $email) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Email tidak sesuai dengan data Google.']);
        }

        return view('auth.google-user-type-selection', compact('email', 'tempGoogleData'));
    }

    /**
     * Handle user type selection for Google users
     */
    public function handleGoogleUserTypeSelection(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'user_type' => ['required', 'in:konsumen,produsen,kurir'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        // Get temp Google data
        $tempGoogleData = Session::get('temp_google_data');
        $expiresAt = Session::get('temp_google_expires_at');

        if (!$tempGoogleData || !$expiresAt || now()->greaterThan($expiresAt)) {
            Session::forget(['temp_google_data', 'temp_google_expires_at']);
            return redirect()->route('login')
                ->withErrors(['user_type' => 'Session Google telah berakhir. Silakan login ulang.']);
        }

        // Clear temp data
        Session::forget(['temp_google_data', 'temp_google_expires_at']);

        // Create Google user with selected type
        $googleUser = (object) $tempGoogleData;

        return $this->initiateGoogleRegistrationWithOtpFromArray($tempGoogleData, $request->user_type, $request->only(['phone', 'address']));
    }

    /**
     * Inisiasi registrasi Google dengan OTP menggunakan array data
     */
    private function initiateGoogleRegistrationWithOtpFromArray($googleData, $userType = 'konsumen', $additionalData = [])
    {
        // Hapus OTP lama untuk email ini
        Otp::where('email', $googleData['email'])->delete();

        // Download avatar Google terlebih dahulu
        $avatarPath = null;
        if (!empty($googleData['avatar'])) {
            $avatarPath = $this->downloadGoogleAvatar($googleData['avatar'], $googleData['google_id']);
        }

        // Simpan data Google user di session (sementara)
        $googleRegistrationData = [
            'name' => $googleData['name'],
            'email' => $googleData['email'],
            'google_id' => $googleData['google_id'],
            'avatar_path' => $avatarPath,
            'avatar_url' => $googleData['avatar'],
            'user_type' => $userType,
            'is_google_oauth' => true,
            'login_provider' => 'google',
            'phone' => $additionalData['phone'] ?? null,
            'address' => $additionalData['address'] ?? null,
            'notification_preferences' => [
                'email_notifications' => true,
                'order_updates' => true,
                'promotion_notifications' => false,
                'newsletter' => false,
            ]
        ];

        // Simpan di session dengan expiry (60 menit)
        Session::put('google_registration_data', $googleRegistrationData);
        Session::put('google_registration_expires_at', now()->addMinutes(60));

        // Generate OTP
        $otp = sprintf('%06d', mt_rand(1, 999999));

        // Simpan OTP ke database
        Otp::create([
            'email' => $googleData['email'],
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Kirim email OTP
        try {
            Mail::to($googleData['email'])->send(new OtpMail($otp, $googleData['name']));
        } catch (\Exception $e) {
            Log::error('Failed to send Google OTP email: ' . $e->getMessage());
        }

        // Redirect ke halaman verifikasi OTP Google
        return redirect()->route('google.otp.verify', ['email' => $googleData['email']])
            ->with('success', 'Data berhasil disimpan! Silakan verifikasi email Anda dengan kode OTP untuk melengkapi registrasi sebagai ' . $this->getUserTypeLabel($userType) . '.');
    }
}

