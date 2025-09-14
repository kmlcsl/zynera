<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Review;
use App\Models\Order;

class HomeController extends Controller
{
    public function index()
    {
        // PRODUK UNGGULAN
        $featuredProducts = Product::with(['category', 'user', 'reviews'])
            ->active()
            ->featured()
            ->inStock()
            ->limit(8)
            ->get()
            ->map(function ($product) {
                $avgRating = $product->reviews()->avg('rating') ?? 0;
                $totalReviews = $product->reviews()->count();

                $product->average_rating = round($avgRating, 1);
                $product->total_reviews = $totalReviews;

                return $product;
            });

        // Data statistik
        $availableProducts = Product::active()->inStock()->count();

        $satisfiedCustomers = Review::where('is_verified', true)
            ->where('rating', '>=', 4)
            ->distinct('user_id')
            ->count('user_id');

        $farmerPartners = User::where('user_type', 'produsen')
            ->where('is_verified', true)
            ->count();

        $customerSupport = '24/7';

        $categories = Category::withCount(['products' => function ($query) {
            $query->active()->inStock();
        }])
            ->where('is_active', true)
            ->orderBy('products_count', 'desc')
            ->limit(6)
            ->get();

        $successfulOrders = Order::whereIn('status', [
            'delivered',
            'completed'
        ])->count();

        $bestSellingProduct = Product::withCount('orderItems')
            ->active()
            ->orderBy('order_items_count', 'desc')
            ->first();

        return view('home', compact(
            'availableProducts',
            'satisfiedCustomers',
            'farmerPartners',
            'customerSupport',
            'featuredProducts',
            'categories',
            'successfulOrders',
            'bestSellingProduct'
        ));
    }

    public function getStats()
    {
        return response()->json([
            'available_products' => Product::active()->inStock()->count(),
            'satisfied_customers' => Review::where('is_verified', true)
                ->where('rating', '>=', 4)
                ->distinct('user_id')
                ->count('user_id'),
            'farmer_partners' => User::where('user_type', 'produsen')
                ->where('is_verified', true)
                ->count(),
            'total_orders' => Order::whereIn('status', ['delivered', 'completed'])->count(),
        ]);
    }

    public function education()
    {
        return view('education.index');
    }

    public function about()
    {
        return view('about.index');
    }
}
