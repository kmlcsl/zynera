<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Product;

class ProductAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check basic access
        if (!in_array($user->user_type, ['admin', 'produsen'])) {
            abort(403, 'Access denied. Product management requires admin or produsen access.');
        }

        // Check specific product access for non-admin users
        if ($user->user_type === 'produsen') {
            $productId = $request->route('product')?->id ?? $request->route('product');

            if ($productId) {
                $product = Product::find($productId);

                if ($product && $product->user_id !== $user->id) {
                    abort(403, 'You can only access your own products.');
                }
            }
        }

        return $next($request);
    }
}
