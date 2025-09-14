<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories
     */
    public function index()
    {
        // Get all active categories with product counts
        $categories = Category::active()
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true)
                    ->where('stock', '>', 0);
            }])
            ->orderBy('name')
            ->get();

        // Get featured categories (categories with most products)
        $featuredCategories = Category::active()
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true)
                    ->where('stock', '>', 0);
            }])
            ->orderBy('products_count', 'desc')
            ->take(6)
            ->get();

        // Get total stats
        $stats = [
            'total_categories' => Category::active()->count(),
            'total_products' => Product::active()->inStock()->count(),
            'categories_with_products' => Category::active()->withActiveProducts()->count(),
        ];

        return view('categories.index', compact('categories', 'featuredCategories', 'stats'));
    }

    /**
     * Show products for a specific category
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Redirect to products page with category filter
        return redirect()->route('products.index', ['category' => $category->slug]);
    }

    /**
     * Get category details (for AJAX)
     */
    public function details($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true)
                    ->where('stock', '>', 0);
            }])
            ->firstOrFail();

        // Get sample products from this category
        $sampleProducts = Product::where('category_id', $category->id)
            ->active()
            ->inStock()
            ->with('user')
            ->take(4)
            ->get();

        return response()->json([
            'category' => $category,
            'sample_products' => $sampleProducts,
            'total_products' => $category->products_count
        ]);
    }
}
