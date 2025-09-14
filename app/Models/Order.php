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
        'shipping_cost',
        'total_items',
        'status',
        'shipping_address',
        'recipient_name',
        'recipient_phone',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'float',
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
        if ($this->relationLoaded('delivery') && $this->delivery) {
            $delivery = $this->delivery;

            if (in_array($this->status, ['paid', 'processing', 'shipped']) && $delivery->courier_id) {
                switch ($delivery->status) {
                    case 'assigned':
                        return 'Ditugaskan ke Kurir';
                    case 'picked_up':
                        return 'Barang Diambil Kurir';
                    case 'in_transit':
                        return 'Dalam Pengiriman';
                    case 'delivered':
                        return 'Barang Terkirim';
                    case 'failed':
                        return 'Pengiriman Gagal';
                }
            }
        }

        // Default status labels
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
        return $this->status === self::STATUS_DELIVERED;
    }

    public function getTotalItemsAttribute()
    {
        return $this->orderItems->sum('quantity');
    }

    public function getSubtotalAttribute()
    {
        return $this->orderItems->sum('total');
    }

    public function getFinalTotalAttribute()
    {
        return $this->total_amount + $this->shipping_cost;
    }
}
