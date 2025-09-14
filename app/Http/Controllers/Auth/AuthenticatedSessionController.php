<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // Cek apakah user sudah terverifikasi
        if (!$user->is_verified) {
            Auth::logout();
            return redirect()->route('otp.verify', ['email' => $request->email])
                ->withErrors(['email' => 'Akun Anda belum terverifikasi. Silakan verifikasi OTP terlebih dahulu.']);
        }

        $request->session()->regenerate();

        switch ($user->user_type) {
            case 'admin':
            case 'produsen':
            case 'kurir':
                return redirect()->intended(route('admin.dashboard'));
            case 'konsumen':
            default:
                return redirect()->intended(route('home'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
