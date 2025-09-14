<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPanelAccess
{
    public function handle(Request $request, Closure $next)
    {
        $userType = Auth::user()->user_type ?? null;
        $allowedTypes = ['admin', 'produsen', 'kurir'];

        if (!in_array($userType, $allowedTypes)) {
            abort(403, 'Access denied. Admin panel access required.');
        }

        return $next($request);
    }
}
