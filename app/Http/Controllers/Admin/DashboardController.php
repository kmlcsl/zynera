<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Delivery;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userType = $user->user_type;
        $userName = $user->name;

        // Dashboard titles and descriptions
        $dashboardData = [
            'user_type' => $userType,
            'user_name' => $userName,
            'page_title' => $this->getPageTitle($userType),
            'page_description' => $this->getPageDescription($userType),
        ];

        // Get stats based on user type
        $stats = $this->getStatsForUserType($userType, $user->id);

        // Get recent data based on user type
        $recentData = $this->getRecentDataForUserType($userType, $user->id);

        // Get additional data based on user type
        $additionalData = $this->getAdditionalDataForUserType($userType, $user->id);

        return view('admin.dashboard', compact('dashboardData', 'stats', 'recentData', 'additionalData'));
    }

    private function getPageTitle($userType)
    {
        $titles = [
            'admin' => 'Dashboard Admin',
            'produsen' => 'Dashboard Produsen',
            'kurir' => 'Dashboard Kurir',
        ];

        return $titles[$userType] ?? 'Dashboard';
    }

    private function getPageDescription($userType)
    {
        $descriptions = [
            'admin' => 'Ringkasan aktivitas platform Zynera',
            'produsen' => 'Kelola produk dan pesanan Anda',
            'kurir' => 'Kelola pengiriman dan rute delivery',
        ];

        return $descriptions[$userType] ?? 'Selamat datang di Zynera';
    }

    private function getStatsForUserType($userType, $userId)
    {
        switch ($userType) {
            case 'admin':
                return [
                    'total_users' => User::count(),
                    'total_products' => Product::count(),
                    'total_orders' => Order::whereDate('created_at', today())->count(), // ✅ HANYA HARI INI
                    'total_revenue' => Order::where('status', 'delivered')->sum('total_amount'),
                    'pending_orders' => Order::where('status', 'pending')->count(),
                    'active_products' => Product::where('is_active', true)->count(),
                    // User role statistics
                    'total_customers' => User::where('user_type', 'konsumen')->count(),
                    'total_producers' => User::where('user_type', 'produsen')->count(),
                    'total_couriers' => User::where('user_type', 'kurir')->count(),
                    'total_admins' => User::where('user_type', 'admin')->count(),
                    // Legacy fields for backward compatibility
                    'producers' => User::where('user_type', 'produsen')->count(),
                    'couriers' => User::where('user_type', 'kurir')->count(),
                ];

            case 'produsen':
                // Get order IDs that contain products from this producer
                $producerOrderIds = OrderItem::whereHas('product', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })->distinct('order_id')->pluck('order_id');

                // Calculate revenue from delivered orders
                $revenueQuery = OrderItem::whereHas('product', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })->whereHas('order', function ($q) {
                    $q->where('status', 'delivered');
                });

                return [
                    'my_products' => Product::where('user_id', $userId)->count(),
                    'active_products' => Product::where('user_id', $userId)->where('is_active', true)->count(),
                    'incoming_orders' => Order::whereIn('id', $producerOrderIds)
                        ->whereDate('created_at', today())->count(), // ✅ HANYA HARI INI
                    'my_orders' => Order::whereIn('id', $producerOrderIds)->count(),
                    'my_revenue' => $revenueQuery->sum('total'),
                    'pending_orders' => Order::whereIn('id', $producerOrderIds)->where('status', 'pending')->count(),
                    'low_stock_products' => Product::where('user_id', $userId)->where('stock', '<=', 10)->count(),
                ];

            case 'kurir':
                // Using deliveries table for courier-specific data
                return [
                    'assigned_deliveries' => Delivery::where('courier_id', $userId)->count(),
                    'completed_deliveries' => Delivery::where('courier_id', $userId)->where('status', 'delivered')->count(),
                    'pending_deliveries' => Delivery::where('courier_id', $userId)->whereIn('status', ['assigned', 'picked_up', 'in_transit'])->count(),
                    'today_deliveries' => Delivery::where('courier_id', $userId)->whereDate('created_at', today())->count(),
                    'failed_deliveries' => Delivery::where('courier_id', $userId)->where('status', 'failed')->count(),
                    'delivery_rating' => 4.8, // Calculate average rating when available
                ];

            default:
                return [];
        }
    }

    private function getRecentDataForUserType($userType, $userId)
    {
        switch ($userType) {
            case 'admin':
                return [
                    'recent_orders' => Order::with(['user', 'orderItems.product'])
                        ->latest()
                        ->take(5)
                        ->get(),
                    'recent_users' => User::latest()->take(5)->get(),
                    'recent_products' => Product::with('user')->latest()->take(5)->get(),
                ];

            case 'produsen':
                // Get orders that contain products from this producer
                $producerOrderIds = OrderItem::whereHas('product', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })->distinct('order_id')->pluck('order_id');

                return [
                    'recent_orders' => Order::with(['user', 'orderItems.product'])
                        ->whereIn('id', $producerOrderIds)
                        ->latest()
                        ->take(5)
                        ->get(),
                    'recent_products' => Product::where('user_id', $userId)->latest()->take(5)->get(),
                ];

            case 'kurir':
                // Using deliveries table for courier-specific data
                return [
                    'assigned_orders' => Delivery::with(['order.user', 'order.orderItems.product'])
                        ->where('courier_id', $userId)
                        ->whereIn('status', ['assigned', 'picked_up', 'in_transit'])
                        ->latest()
                        ->take(5)
                        ->get(),
                    'delivery_history' => Delivery::with(['order.user', 'order.orderItems.product'])
                        ->where('courier_id', $userId)
                        ->where('status', 'delivered')
                        ->latest()
                        ->take(5)
                        ->get(),
                ];

            default:
                return [];
        }
    }

    private function getAdditionalDataForUserType($userType, $userId)
    {
        switch ($userType) {
            case 'admin':
                return [
                    'top_products' => Product::withCount('orderItems')
                        ->orderBy('order_items_count', 'desc')
                        ->take(5)
                        ->get(),
                    'top_producers' => User::where('user_type', 'produsen')
                        ->withCount('products')
                        ->orderBy('products_count', 'desc')
                        ->take(5)
                        ->get(),
                ];

            case 'produsen':
                return [
                    'top_products' => Product::where('user_id', $userId)
                        ->withCount('orderItems')
                        ->orderBy('order_items_count', 'desc')
                        ->take(5)
                        ->get(),
                    'low_stock_products' => Product::where('user_id', $userId)
                        ->where('stock', '<=', 10)
                        ->orderBy('stock', 'asc')
                        ->take(5)
                        ->get(),
                ];

            case 'kurir':
                // Calculate performance metrics from deliveries table
                $totalDeliveries = Delivery::where('courier_id', $userId)->delivered()->count();
                $onTimeDeliveries = $totalDeliveries > 0 ? ($totalDeliveries * 0.95) : 0; // Placeholder calculation

                // Calculate average delivery time with proper error handling
                $deliveredOrders = Delivery::where('courier_id', $userId)->delivered()->get();
                $avgDeliveryTime = 0;
                if ($deliveredOrders->count() > 0) {
                    $totalHours = 0;
                    $validDurations = 0;
                    foreach ($deliveredOrders as $delivery) {
                        // Use calculateDuration method or duration field
                        $duration = $delivery->duration ?? $delivery->calculateDuration();
                        if ($duration !== null && $duration > 0) {
                            $totalHours += $duration;
                            $validDurations++;
                        }
                    }
                    if ($validDurations > 0) {
                        $avgDeliveryTime = $totalHours / $validDurations;
                    }
                }

                return [
                    'delivery_areas' => [], // Add delivery areas data when available
                    'performance_metrics' => [
                        'on_time_delivery' => $totalDeliveries > 0 ? round(($onTimeDeliveries / $totalDeliveries) * 100) : 0,
                        'customer_rating' => 4.8,
                        'avg_delivery_time' => round($avgDeliveryTime, 1),
                        'total_deliveries' => $totalDeliveries,
                    ],
                ];

            default:
                return [];
        }
    }
}

