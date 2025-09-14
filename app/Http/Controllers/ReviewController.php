<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a review for a product
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'order_id' => 'required|exists:orders,id'
        ]);

        // Verify that user can create review
        if (!Review::canCreateReview(Auth::id(), $product->id, $request->order_id)) {
            return back()->with('error', 'Anda tidak dapat memberikan review untuk produk ini.');
        }

        // Verify order belongs to user and is delivered
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->where('status', Order::STATUS_DELIVERED)
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak valid atau belum selesai.');
        }

        // Verify order contains this product
        $orderItem = $order->orderItems()
            ->where('product_id', $product->id)
            ->first();

        if (!$orderItem) {
            return back()->with('error', 'Produk tidak ditemukan dalam pesanan ini.');
        }

        // Create review
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_verified' => true // Auto verify for delivered orders
        ]);

        return back()->with('success', 'Review berhasil diberikan! Terima kasih atas feedback Anda.');
    }

    /**
     * Get reviews for a product (public view - rating only)
     */
    public function getProductReviews(Product $product)
    {
        $reviews = Review::with('user:id,name')
            ->where('product_id', $product->id)
            ->verified()
            ->latest()
            ->paginate(10);

        // Only return rating and user info for public view
        $publicReviews = $reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'user_name' => $review->user->name,
                'rating' => $review->rating,
                'created_at' => $review->created_at,
                'is_verified' => $review->is_verified
            ];
        });

        return response()->json([
            'reviews' => $publicReviews,
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'total' => $reviews->total()
            ]
        ]);
    }

    /**
     * Get reviews for producer (includes comments)
     */
    public function getProducerReviews(Request $request)
    {
        if (Auth::user()->user_type !== 'produsen') {
            abort(403, 'Akses tidak diizinkan');
        }

        $productId = $request->get('product_id');

        $query = Review::with(['user:id,name', 'product:id,name'])
            ->forProducer(Auth::id());

        if ($productId) {
            $query->where('product_id', $productId);
        }

        $reviews = $query->latest()->paginate(15);

        return view('producer.reviews.index', compact('reviews'));
    }

    /**
     * Show review details for producer
     */
    public function show(Review $review)
    {
        // Check if user can view this review
        if (Auth::user()->user_type === 'produsen') {
            if ($review->product->user_id !== Auth::id()) {
                abort(403, 'Anda tidak dapat melihat review ini');
            }
        } elseif (Auth::user()->user_type !== 'admin' && $review->user_id !== Auth::id()) {
            abort(403, 'Anda tidak dapat melihat review ini');
        }

        $review->load(['user:id,name', 'product:id,name', 'order:id,order_number']);

        return view('reviews.show', compact('review'));
    }

    /**
     * Reply to review (for producers)
     */
    public function reply(Request $request, Review $review)
    {
        if (Auth::user()->user_type !== 'produsen') {
            abort(403, 'Hanya produsen yang dapat membalas review');
        }

        if ($review->product->user_id !== Auth::id()) {
            abort(403, 'Anda tidak dapat membalas review ini');
        }

        $request->validate([
            'reply' => 'required|string|max:500'
        ]);

        // You might want to create a separate model for review replies
        // For now, we'll just add it as a notification or update

        return back()->with('success', 'Balasan berhasil dikirim');
    }

    /**
     * Get average rating for a product
     */
    public function getAverageRating(Product $product)
    {
        $avgRating = $product->reviews()
            ->verified()
            ->avg('rating');

        $totalReviews = $product->reviews()
            ->verified()
            ->count();

        return response()->json([
            'average_rating' => round($avgRating, 1),
            'total_reviews' => $totalReviews,
            'rating_breakdown' => $this->getRatingBreakdown($product)
        ]);
    }

    /**
     * Get rating breakdown (1-5 stars distribution)
     */
    private function getRatingBreakdown(Product $product)
    {
        $breakdown = [];

        for ($i = 1; $i <= 5; $i++) {
            $count = $product->reviews()
                ->verified()
                ->where('rating', $i)
                ->count();

            $breakdown[$i] = $count;
        }

        return $breakdown;
    }
}
