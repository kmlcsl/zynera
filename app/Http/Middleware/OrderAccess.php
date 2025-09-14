<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OrderAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $userType = Auth::user()->user_type;
        if (!in_array($userType, ['admin', 'produsen', 'kurir'])) {
            abort(403, 'Access denied. Order management requires admin, produsen, or kurir access.');
        }
        return $next($request);
    }
}
