<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HandleAuthorizationErrors
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (HttpException $e) {
            if ($e->getStatusCode() === 403) {
                // Log the authorization error for debugging
                Log::warning('403 Authorization Error Caught', [
                    'user_id' => Auth::id(),
                    'user_type' => Auth::check() ? Auth::user()->user_type : 'not_authenticated',
                    'email' => Auth::check() ? Auth::user()->email : null,
                    'route' => $request->route() ? $request->route()->getName() : 'unknown',
                    'method' => $request->method(),
                    'url' => $request->url(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'error_message' => $e->getMessage()
                ]);

                // Check if user is not authenticated
                if (!Auth::check()) {
                    return redirect()->route('login')
                        ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
                }

                // Check if user type is appropriate for the action
                $user = Auth::user();
                $routeName = $request->route() ? $request->route()->getName() : '';

                // Handle specific route authorization issues
                if (str_contains($routeName, 'admin.') && !in_array($user->user_type, ['admin', 'produsen', 'kurir'])) {
                    return redirect()->route('home')
                        ->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses panel admin.');
                }

                if (str_contains($routeName, 'checkout') || str_contains($routeName, 'orders.store')) {
                    // These should be accessible to all authenticated users
                    Log::error('Checkout/Order creation blocked unexpectedly', [
                        'user_id' => Auth::id(),
                        'user_type' => Auth::user()->user_type,
                        'route' => $routeName
                    ]);
                    
                    return redirect()->route('cart.index')
                        ->with('error', 'Terjadi masalah dengan sistem checkout. Silakan coba lagi atau hubungi customer service.');
                }

                // Generic 403 handling
                return redirect()->back()
                    ->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk melakukan tindakan ini.');
            }

            // Re-throw other HTTP exceptions
            throw $e;
        }
    }
}
