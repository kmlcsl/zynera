<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DeliveryAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login');
        }

        // Allow access for admin, kurir, and produsen
        if (in_array($user->user_type, ['admin', 'kurir', 'produsen'])) {
            return $next($request);
        }

        // Deny access for other user types (konsumen, etc.)
        abort(403, 'Access denied to delivery management');
    }
}
