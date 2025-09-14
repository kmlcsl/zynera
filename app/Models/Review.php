<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'comment',
        'is_verified'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified' => 'boolean'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopePublic($query)
    {
        // Public reviews - rating is always visible, comments depend on context
        return $query->where('is_verified', true);
    }

    public function scopeForProducer($query, $producerId)
    {
        // Producer can see all reviews for their products (including comments)
        return $query->whereHas('product', function ($q) use ($producerId) {
            $q->where('user_id', $producerId);
        });
    }

    public function scopeRatingOnly($query)
    {
        // For public display - only rating without comments
        return $query->select('id', 'user_id', 'product_id', 'rating', 'created_at', 'is_verified');
    }

    // Methods
    public function canViewComment($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return false;
        }

        // Admin can see all comments
        if ($user->user_type === 'admin') {
            return true;
        }

        // Producer can see comments for their products
        if ($user->user_type === 'produsen' && $this->product->user_id === $user->id) {
            return true;
        }

        // Review author can see their own comment
        if ($this->user_id === $user->id) {
            return true;
        }

        return false;
    }

    public function getDisplayCommentAttribute()
    {
        // Public users only see rating, not comments
        // Comments are private between customer and producer
        return $this->canViewComment() ? $this->comment : null;
    }

    // Check if user can create review for this order/product
    public static function canCreateReview($userId, $productId, $orderId)
    {
        // Check if order exists and is delivered
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->where('status', Order::STATUS_DELIVERED)
            ->first();

        if (!$order) {
            return false;
        }

        // Check if order contains this product
        $orderItem = $order->orderItems()
            ->where('product_id', $productId)
            ->first();

        if (!$orderItem) {
            return false;
        }

        // Check if review already exists
        $existingReview = static::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->first();

        return !$existingReview;
    }
}
