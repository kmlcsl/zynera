<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Symfony\Component\HttpFoundation\Response;

class LogOrderAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only log for order-related routes
        $routeName = $request->route() ? $request->route()->getName() : '';
        
        if (str_contains($routeName, 'orders.')) {
            $orderId = null;
            
            // Extract order ID from route parameters
            if ($request->route() && $request->route()->hasParameter('order')) {
                $order = $request->route()->parameter('order');
                $orderId = is_object($order) ? $order->id : $order;
            }
            
            $logData = [
                'timestamp' => now()->toDateTimeString(),
                'route' => $routeName,
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'order_id' => $orderId,
            ];
            
            if (Auth::check()) {
                $user = Auth::user();
                $logData = array_merge($logData, [
                    'authenticated' => true,
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'user_type' => $user->user_type,
                ]);
                
                // If we have order ID, check ownership
                if ($orderId) {
                    $order = Order::find($orderId);
                    if ($order) {
                        $logData = array_merge($logData, [
                            'order_owner_id' => $order->user_id,
                            'order_owner_email' => $order->user ? $order->user->email : 'unknown',
                            'is_owner' => $order->user_id === $user->id,
                            'order_status' => $order->status,
                            'order_created' => $order->created_at->toDateTimeString()
                        ]);
                    } else {
                        $logData['order_not_found'] = true;
                    }
                }
            } else {
                $logData['authenticated'] = false;
            }
            
            Log::info('Order Access Attempt', $logData);
        }
        
        return $next($request);
    }
}
