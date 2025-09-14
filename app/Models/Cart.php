<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'integer'
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

    // Accessors
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->product->price;
    }

    // Scope untuk mendapatkan cart user tertentu
    public function scopeForUser($query, $userId = null)
    {
        $userId = $userId ?: Auth::id();
        return $query->where('user_id', $userId);
    }

    // Method untuk validasi stok
    public function hasValidStock()
    {
        return $this->product->stock >= $this->quantity;
    }

    // Method untuk mendapatkan total cart user
    public static function getTotalForUser($userId = null)
    {
        $userId = $userId ?: Auth::id();

        return static::with('product')
            ->where('user_id', $userId)
            ->get()
            ->sum(function ($cart) {
                return $cart->quantity * $cart->product->price;
            });
    }

    // Method untuk mendapatkan jumlah item di cart
    public static function getItemCountForUser($userId = null)
    {
        $userId = $userId ?: Auth::id();

        return static::where('user_id', $userId)->sum('quantity');
    }
}
