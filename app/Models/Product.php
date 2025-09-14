<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'unit',
        'images',
        'category_id',
        'user_id',
        'village_id',
        'is_active',
        'is_featured',
        'weight',
        'ingredients',
        'expired_date'
    ];

    protected $casts = [
        'images' => 'array',
        'ingredients' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'expired_date' => 'date',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function village()
    {
        return $this->belongsTo(Region::class, 'village_id');
    }

    // ============================================================
    // IMAGE ACCESSORS
    // ============================================================

    public function getMainImageAttribute()
    {
        $images = $this->images;
        if (is_array($images) && count($images) > 0) {
            return $images[0];
        }
        return null;
    }

    public function getMainImageUrlAttribute()
    {
        $mainImage = $this->main_image;

        if (!$mainImage) {
            return null;
        }

        // Coba beberapa kemungkinan path
        $possiblePaths = [
            $mainImage,
            'products/' . $mainImage,
            'images/' . $mainImage,
            ltrim($mainImage, '/')
        ];

        foreach ($possiblePaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path);
            }
        }

        // Fallback ke public folder
        $publicPath = public_path('storage/' . $mainImage);
        if (file_exists($publicPath)) {
            return asset('storage/' . $mainImage);
        }

        return null;
    }

    public function getImageUrlsAttribute()
    {
        $images = $this->images;
        if (!is_array($images)) {
            return [];
        }

        return collect($images)->map(function ($image) {
            $possiblePaths = [
                $image,
                'products/' . $image,
                'images/' . $image,
                ltrim($image, '/')
            ];

            foreach ($possiblePaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    return Storage::url($path);
                }
            }

            $publicPath = public_path('storage/' . $image);
            if (file_exists($publicPath)) {
                return asset('storage/' . $image);
            }

            return null;
        })->filter()->values()->toArray();
    }

    public function getHasImagesAttribute()
    {
        $images = $this->images;
        return is_array($images) && count($images) > 0;
    }

    // ============================================================
    // OTHER ACCESSORS
    // ============================================================

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeWithImages($query)
    {
        return $query->whereNotNull('images')
            ->where('images', '!=', '')
            ->where('images', '!=', '[]')
            ->where('images', '!=', 'null')
            ->where('images', '!=', '[""]');
    }

    public function scopeForUser($query, $user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if ($user && property_exists($user, 'user_type') && $user->user_type === 'produsen') {
            return $query->where('user_id', $user->id);
        }

        return $query;
    }

    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByVillage($query, $villageId)
    {
        return $query->where('village_id', $villageId);
    }

    public function scopeByDistrict($query, $districtId)
    {
        return $query->whereHas('village', function ($q) use ($districtId) {
            $q->where('parent_id', $districtId);
        });
    }

    // ============================================================
    // METHODS
    // ============================================================

    public function canBeAccessedBy($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return false;
        }

        if (isset($user->user_type) && $user->user_type === 'admin') {
            return true;
        }

        if (isset($user->user_type) && $user->user_type === 'produsen') {
            return $this->user_id === $user->id;
        }

        return false;
    }

    public function getImagesAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?: [];
        }
        return $value ?: [];
    }

    public function setImagesAttribute($value)
    {
        $this->attributes['images'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getIngredientsAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?: [];
        }
        return $value ?: [];
    }
}
