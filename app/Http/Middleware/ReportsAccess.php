<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ReportsAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Allow admin and produsen to access reports features
        if (in_array($user->user_type, ['admin', 'produsen'])) {
            return $next($request);
        }

        // Redirect other user types to dashboard
        return redirect()->route('admin.dashboard')
            ->with('error', 'Anda tidak memiliki akses ke laporan.');
    }
}
