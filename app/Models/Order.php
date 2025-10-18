<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'subtotal',
        'service_fee',
        'shipping_cost',
        'shipping_method',
        'total_items',
        'status',
        'shipping_address',
        'recipient_name',
        'recipient_phone',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'float',
        'subtotal' => 'float',
        'service_fee' => 'float',
        'shipping_cost' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    // Accessors
    public function getHasCourierAttribute()
    {
        return $this->delivery && $this->delivery->courier_id;
    }

    public function getCourierNameAttribute()
    {
        return $this->delivery && $this->delivery->courier ? $this->delivery->courier->name : null;
    }

    public function getStatusLabelAttribute()
    {
        // Prioritaskan delivery status jika ada
        if ($this->relationLoaded('delivery') && $this->delivery) {
            $delivery = $this->delivery;

            // Jika delivery sudah delivered, tampilkan sebagai selesai
            if ($delivery->status === 'delivered') {
                return 'Barang Terkirim';
            }
            
            // Jika delivery gagal
            if ($delivery->status === 'failed') {
                return 'Pengiriman Gagal';
            }

            // Untuk status lain, cek jika ada courier
            if (in_array($this->status, ['paid', 'processing', 'shipped']) && $delivery->courier_id) {
                switch ($delivery->status) {
                    case 'assigned':
                        return 'Ditugaskan ke Kurir';
                    case 'picked_up':
                        return 'Barang Diambil Kurir';
                    case 'in_transit':
                        return 'Dalam Pengiriman';
                }
            }
        }

        // Default status labels berdasarkan order status
        $labels = [
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_PAID => 'Dibayar - Perlu Assign Kurir',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_SHIPPED => 'Dikirim',
            self::STATUS_DELIVERED => 'Terkirim',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    public function getStatusColorAttribute()
    {
        // Cek delivery status terlebih dahulu
        if ($this->relationLoaded('delivery') && $this->delivery) {
            if ($this->delivery->status === 'delivered') {
                return 'green'; // Hijau untuk delivered
            }
            if ($this->delivery->status === 'failed') {
                return 'red'; // Merah untuk failed
            }
            if (in_array($this->delivery->status, ['in_transit', 'picked_up'])) {
                return 'purple'; // Ungu untuk dalam pengiriman
            }
        }
        
        // Default colors berdasarkan order status
        $colors = [
            self::STATUS_PENDING => 'yellow',
            self::STATUS_PAID => 'blue',
            self::STATUS_PROCESSING => 'purple',
            self::STATUS_SHIPPED => 'indigo',
            self::STATUS_DELIVERED => 'green',
            self::STATUS_CANCELLED => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    public function getShippingMethodLabelAttribute()
    {
        $labels = [
            'pickup' => 'Jemput di Tempat',
            'courier' => 'Kurir Antar',
        ];

        return $labels[$this->shipping_method] ?? 'Unknown';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    public function scopeShipped($query)
    {
        return $query->where('status', self::STATUS_SHIPPED);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    // Methods
    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PAID]);
    }

    public function isPaid()
    {
        return in_array($this->status, [
            self::STATUS_PAID,
            self::STATUS_PROCESSING,
            self::STATUS_SHIPPED,
            self::STATUS_DELIVERED
        ]);
    }

    public function isCompleted()
    {
        // Order completed jika status delivered atau delivery status delivered
        if ($this->status === self::STATUS_DELIVERED) {
            return true;
        }
        
        // Cek delivery status jika ada
        if ($this->relationLoaded('delivery') && $this->delivery) {
            return $this->delivery->status === 'delivered';
        }
        
        return false;
    }

    public function getTotalItemsAttribute()
    {
        return $this->orderItems->sum('quantity');
    }

    public function getCalculatedSubtotalAttribute()
    {
        return $this->orderItems->sum('total');
    }

    public function getFinalTotalAttribute()
    {
        return $this->subtotal + $this->service_fee + $this->shipping_cost;
    }

    public function getServiceFeePercentageAttribute()
    {
        return $this->subtotal > 0 ? ($this->service_fee / $this->subtotal) * 100 : 0;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id'; // Pastikan menggunakan ID sebagai route key
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Log ketika order di-load untuk debugging
        static::retrieved(function ($order) {
            \Illuminate\Support\Facades\Log::debug('Order retrieved', [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => $order->user_id
            ]);
        });
    }
}
