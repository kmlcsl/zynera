<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * User types constants
     */
    const USER_TYPES = [
        'konsumen' => 'Konsumen',
        'produsen' => 'Produsen',
        'admin' => 'Admin',
        'kurir' => 'Kurir'
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'village',
        'district',
        'village_id',
        'avatar',
        'user_type',
        'is_verified',
        'google_id',
        'login_provider',
        'notification_preferences',
        'last_login_at',
        'profile_completed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_verified' => 'boolean',
        'profile_completed' => 'boolean',
        'notification_preferences' => 'array',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }



    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'courier_id');
    }

    public function permissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    public function village()
    {
        return $this->belongsTo(Region::class, 'village_id');
    }

    public function categories()
    {
        return $this->hasManyThrough(Category::class, Product::class);
    }

    // Attributes
    public function getActiveCartCountAttribute()
    {
        return $this->carts()->whereHas('product', function ($query) {
            $query->where('is_active', true);
        })->sum('quantity');
    }

    // Scopes
    public function scopeProducers($query)
    {
        return $query->where('user_type', 'produsen');
    }

    public function scopeConsumers($query)
    {
        return $query->where('user_type', 'konsumen');
    }

    public function scopeCouriers($query)
    {
        return $query->where('user_type', 'kurir');
    }

    public function scopeAdmins($query)
    {
        return $query->where('user_type', 'admin');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeProfileCompleted($query)
    {
        return $query->where('profile_completed', true);
    }

    // Helper Methods
    public function hasPermission($routeName)
    {
        $permission = $this->permissions()->where('route_name', $routeName)->first();
        return $permission ? $permission->is_allowed : true; // Default allow
    }

    public function isProducer()
    {
        return $this->user_type === 'produsen';
    }

    public function isConsumer()
    {
        return $this->user_type === 'konsumen';
    }

    public function isCourier()
    {
        return $this->user_type === 'kurir';
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    public function getFullAddressAttribute()
    {
        $addressParts = array_filter([
            $this->address,
            $this->village,
            $this->district
        ]);

        return implode(', ', $addressParts);
    }

    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Return default avatar or generate avatar based on initials
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
    }

    // Static Methods
    public static function getUserTypeOptions()
    {
        return self::USER_TYPES;
    }

    public static function getUserTypeLabel($userType)
    {
        return self::USER_TYPES[$userType] ?? ucfirst($userType);
    }
}
