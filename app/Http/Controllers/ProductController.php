<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'user', 'reviews', 'village.parent'])
            ->where('is_active', true)
            ->inStock();

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }


        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Sort
        switch ($request->get('sort', 'latest')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')
                    ->orderByDesc('reviews_avg_rating');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        // TAMBAHKAN PERHITUNGAN RATING UNTUK SETIAP PRODUK
        $products = $query->paginate(12)->through(function ($product) {
            $product->average_rating = $product->reviews()->avg('rating') ?? 0;
            $product->total_reviews = $product->reviews()->count();
            return $product;
        });

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'user', 'reviews.user', 'village.parent'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Calculate average rating
        $product->average_rating = $product->reviews()->avg('rating') ?? 0;
        $product->total_reviews = $product->reviews()->count();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inStock()
            ->take(4)
            ->get()
            ->map(function ($relatedProduct) {
                $relatedProduct->average_rating = $relatedProduct->reviews()->avg('rating') ?? 0;
                $relatedProduct->total_reviews = $relatedProduct->reviews()->count();
                return $relatedProduct;
            });

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $products = Product::with(['category', 'user', 'reviews'])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->inStock()
            ->paginate(12)
            ->through(function ($product) {
                $product->average_rating = $product->reviews()->avg('rating') ?? 0;
                $product->total_reviews = $product->reviews()->count();
                return $product;
            });

        return view('products.category', compact('category', 'products'));
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product is active and in stock
        if (!$product->is_active) {
            return back()->with('error', 'Produk tidak tersedia');
        }

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi');
        }

        // Check if item already exists in cart
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingCart) {
            $newQuantity = $request->quantity; // Replace with new quantity for buy now

            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Jumlah melebihi stok yang tersedia');
            }

            $existingCart->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        // Redirect directly to checkout with this item selected
        return redirect()->route('checkout')->with([
            'success' => 'Produk berhasil ditambahkan',
            'buy_now_item' => $request->product_id
        ]);
    }

    public function getVillagesByDistrict(Request $request)
    {
        $districtId = $request->get('district_id');

        if (!$districtId) {
            return response()->json([]);
        }

        $villages = Region::desa()
            ->where('parent_id', $districtId)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($villages);
    }

    // TAMBAHKAN METHOD BARU UNTUK API ENDPOINTS
    public function search(Request $request)
    {
        $query = Product::with(['category'])
            ->where('is_active', true)
            ->inStock();

        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->limit(10)->get(['id', 'name', 'slug', 'price']);

        return response()->json($products);
    }

    public function filters()
    {
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true)->where('stock', '>', 0);
            }])
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $priceRange = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return response()->json([
            'categories' => $categories,
            'price_range' => $priceRange
        ]);
    }
}
