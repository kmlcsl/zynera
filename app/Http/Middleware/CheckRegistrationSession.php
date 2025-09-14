<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckRegistrationSession
{
    public function handle(Request $request, Closure $next)
    {
        $email = $request->get('email') ?? $request->input('email');
        $registrationData = Session::get('registration_data');
        $expiresAt = Session::get('registration_expires_at');

        if (
            !$email || !$registrationData || !$expiresAt ||
            now()->greaterThan($expiresAt) ||
            $registrationData['email'] !== $email
        ) {

            Session::forget(['registration_data', 'registration_expires_at']);

            return redirect()->route('register')
                ->withErrors(['email' => 'Session registrasi tidak valid atau telah berakhir. Silakan daftar ulang.']);
        }

        return $next($request);
    }
}
