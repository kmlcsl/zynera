<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Base validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['required', 'in:konsumen,produsen,kurir'],
            'address' => ['nullable', 'string'],
            'terms' => ['required', 'accepted'],
        ];

        // Conditional validation based on user type
        $userType = $request->input('user_type', 'konsumen');

        switch ($userType) {
            case 'konsumen':
                $rules['phone'] = ['nullable', 'string', 'max:20'];
                $rules['village'] = ['nullable', 'string', 'max:255'];
                $rules['district'] = ['nullable', 'string', 'max:255'];
                break;

            case 'produsen':
                $rules['phone'] = ['required', 'string', 'max:20'];
                $rules['village'] = ['required', 'string', 'max:255'];
                $rules['district'] = ['required', 'string', 'max:255'];
                break;

            case 'kurir':
                $rules['phone'] = ['required', 'string', 'max:20'];
                $rules['village'] = ['nullable', 'string', 'max:255'];
                $rules['district'] = ['required', 'string', 'max:255'];
                break;
        }

        // Custom validation messages
        $messages = [
            'phone.required' => 'Nomor WhatsApp wajib diisi untuk ' . $this->getUserTypeLabel($userType),
            'village.required' => $userType === 'produsen'
                ? 'Desa/Gampong wajib diisi untuk produsen'
                : 'Field ini wajib diisi',
            'district.required' => $userType === 'produsen'
                ? 'Kecamatan wajib diisi untuk produsen'
                : ($userType === 'kurir' ? 'Area jangkauan wajib diisi untuk kurir' : 'Field ini wajib diisi'),
            'user_type.required' => 'Silakan pilih jenis akun yang ingin didaftarkan',
            'user_type.in' => 'Jenis akun yang dipilih tidak valid',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
        ];

        // Tambahkan pengecekan email existing SEBELUM validation
        $emailError = $this->checkExistingEmail($request->email);
        if ($emailError) {
            return back()->withErrors(['email' => $emailError])->withInput();
        }

        $request->validate($rules, $messages);

        // Hapus OTP lama untuk email ini
        Otp::where('email', $request->email)->delete();

        // Simpan data registrasi di session (sementara)
        $registrationData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'user_type' => $userType,
            'phone' => $request->phone,
            'address' => $request->address,
            'village' => $request->village,
            'district' => $request->district,
        ];

        // Simpan di session dengan expiry (30 menit)
        Session::put('registration_data', $registrationData);
        Session::put('registration_expires_at', now()->addMinutes(30));

        // Generate OTP
        $otp = sprintf('%06d', mt_rand(1, 999999));

        // Simpan OTP ke database
        Otp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Kirim email OTP
        try {
            Mail::to($request->email)->send(new OtpMail($otp, $request->name));
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());
        }

        // Redirect ke halaman verifikasi OTP dengan pesan sesuai user type
        $successMessage = $this->getSuccessMessage($userType);

        return redirect()->route('otp.verify', ['email' => $request->email])
            ->with('success', $successMessage);
    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm(Request $request): View|RedirectResponse
    {
        $email = $request->get('email');

        // Cek apakah ada data registrasi di session
        $registrationData = Session::get('registration_data');
        $expiresAt = Session::get('registration_expires_at');

        if (!$email || !$registrationData || !$expiresAt || now()->greaterThan($expiresAt)) {
            // Data registrasi tidak ada atau sudah expired
            Session::forget(['registration_data', 'registration_expires_at']);
            return redirect()->route('register')
                ->withErrors(['email' => 'Session registrasi telah berakhir. Silakan daftar ulang.']);
        }

        // Pastikan email di session sama dengan yang di URL
        if ($registrationData['email'] !== $email) {
            return redirect()->route('register')
                ->withErrors(['email' => 'Email tidak sesuai dengan data registrasi.']);
        }

        return view('auth.verify-otp', compact('email', 'registrationData'));
    }

    /**
     * Verify OTP and complete registration
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        // Cek data registrasi di session
        $registrationData = Session::get('registration_data');
        $expiresAt = Session::get('registration_expires_at');

        if (!$registrationData || !$expiresAt || now()->greaterThan($expiresAt)) {
            Session::forget(['registration_data', 'registration_expires_at']);
            return redirect()->route('register')
                ->withErrors(['otp' => 'Session registrasi telah berakhir. Silakan daftar ulang.']);
        }

        // Verifikasi OTP
        $otpRecord = Otp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        // PERUBAHAN UTAMA: Baru sekarang buat user setelah OTP terverifikasi
        $user = User::create([
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'password' => Hash::make($registrationData['password']),
            'user_type' => $registrationData['user_type'],
            'is_verified' => true,
            'email_verified_at' => now(),
            'phone' => $registrationData['phone'],
            'address' => $registrationData['address'],
            'village' => $registrationData['village'],
            'district' => $registrationData['district'],
        ]);

        // Hapus OTP dan session data
        $otpRecord->delete();
        Session::forget(['registration_data', 'registration_expires_at']);

        // Fire registered event
        event(new Registered($user));

        // Login user
        Auth::login($user);

        // Redirect based on user type with welcome message
        $welcomeMessage = $this->getWelcomeMessage($user->user_type);

        if (in_array($user->user_type, ['admin', 'produsen', 'kurir'])) {
            return redirect()->route('admin.dashboard')->with('success', $welcomeMessage);
        }

        return redirect()->route('home')->with('success', $welcomeMessage);
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        // Tambahkan rate limiting - max 3 kali per 10 menit
        $key = 'otp_resend:' . $request->email;
        $attempts = cache()->get($key, 0);

        if ($attempts >= 3) {
            return back()->withErrors(['email' => 'Terlalu banyak permintaan kirim ulang. Silakan tunggu 10 menit.']);
        }

        // Cek apakah ada data registrasi di session
        $registrationData = Session::get('registration_data');
        $expiresAt = Session::get('registration_expires_at');

        if (!$registrationData || !$expiresAt || now()->greaterThan($expiresAt) || $registrationData['email'] !== $request->email) {
            Session::forget(['registration_data', 'registration_expires_at']);
            return redirect()->route('register')
                ->withErrors(['email' => 'Session registrasi telah berakhir. Silakan daftar ulang.']);
        }

        // Generate OTP baru
        $otp = sprintf('%06d', mt_rand(1, 999999));

        // Update atau buat OTP baru
        Otp::updateOrCreate(
            ['email' => $request->email],
            ['otp' => $otp, 'expires_at' => now()->addMinutes(10)]
        );

        // Kirim email OTP
        try {
            Mail::to($request->email)->send(new OtpMail($otp, $registrationData['name']));

            // Increment attempts counter
            cache()->put($key, $attempts + 1, now()->addMinutes(10));

            return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            Log::error('Failed to resend OTP email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Gagal mengirim ulang kode OTP. Silakan coba lagi.']);
        }
    }

    /**
     * Check if email is already registered or in temp registration
     */
    private function checkExistingEmail(string $email): ?string
    {
        // Cek apakah email sudah terdaftar dan verified
        $existingUser = User::where('email', $email)->first();
        if ($existingUser && $existingUser->is_verified) {
            return 'Email sudah terdaftar dan terverifikasi. Silakan gunakan fitur login.';
        }

        // Cek apakah email sudah terdaftar tapi belum verified (akun zombie)
        if ($existingUser && !$existingUser->is_verified) {
            // Hapus akun yang tidak terverifikasi
            $existingUser->delete();
        }

        // Hapus data registrasi temporary yang mungkin masih ada
        Otp::where('email', $email)->delete();

        return null; // Email bisa digunakan
    }

    /**
     * Get user type label in Indonesian
     */
    private function getUserTypeLabel(string $userType): string
    {
        return match ($userType) {
            'konsumen' => 'pembeli',
            'produsen' => 'penjual/produsen',
            'kurir' => 'kurir',
            default => 'pengguna'
        };
    }

    /**
     * Get success message based on user type
     */
    private function getSuccessMessage(string $userType): string
    {
        return match ($userType) {
            'konsumen' => 'Data pendaftaran pembeli telah disimpan! Silakan cek email Anda untuk kode OTP dan selesaikan verifikasi.',
            'produsen' => 'Data pendaftaran penjual telah disimpan! Silakan cek email Anda untuk kode OTP dan selesaikan verifikasi.',
            'kurir' => 'Data pendaftaran kurir telah disimpan! Silakan cek email Anda untuk kode OTP dan selesaikan verifikasi.',
            default => 'Data pendaftaran telah disimpan! Silakan cek email Anda untuk kode OTP dan selesaikan verifikasi.'
        };
    }

    /**
     * Get welcome message after successful verification
     */
    private function getWelcomeMessage(string $userType): string
    {
        return match ($userType) {
            'konsumen' => 'Selamat datang di AgriConnect! Akun pembeli Anda telah berhasil dibuat dan diverifikasi. Anda dapat mulai berbelanja produk pangan lokal.',
            'produsen' => 'Selamat datang di AgriConnect! Akun penjual Anda telah berhasil dibuat dan diverifikasi. Anda dapat mulai mengelola toko dan produk Anda.',
            'kurir' => 'Selamat datang di AgriConnect! Akun kurir Anda telah berhasil dibuat dan diverifikasi. Admin akan segera melakukan review akun Anda.',
            default => 'Selamat datang di AgriConnect! Akun Anda telah berhasil dibuat dan diverifikasi.'
        };
    }
}
