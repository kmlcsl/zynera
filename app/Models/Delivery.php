<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'courier_id',
        'assigned_by',
        'status',
        'assigned_at',
        'picked_up_at',
        'delivered_at',
        'pickup_time',
        'delivery_time', 
        'duration',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'pickup_time' => 'datetime',
        'delivery_time' => 'datetime',
        'duration' => 'decimal:2',
    ];

    // Status constants
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_PICKED_UP = 'picked_up';
    const STATUS_IN_TRANSIT = 'in_transit';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_FAILED = 'failed';

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Scopes
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopePickedUp($query)
    {
        return $query->where('status', 'picked_up');
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['assigned', 'picked_up', 'in_transit']);
    }

    // Helper methods
    public function isDelivered()
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function isPending()
    {
        return in_array($this->status, [self::STATUS_ASSIGNED, self::STATUS_PICKED_UP, self::STATUS_IN_TRANSIT]);
    }

    public function canBeUpdated()
    {
        return !in_array($this->status, [self::STATUS_DELIVERED, self::STATUS_FAILED]);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_ASSIGNED => 'Ditugaskan ke Kurir',
            self::STATUS_PICKED_UP => 'Barang Diambil Kurir',
            self::STATUS_IN_TRANSIT => 'Dalam Pengiriman',
            self::STATUS_DELIVERED => 'Barang Terkirim',
            self::STATUS_FAILED => 'Pengiriman Gagal',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    // Calculate delivery duration
    public function calculateDuration()
    {
        if ($this->pickup_time && $this->delivery_time) {
            $pickup = $this->pickup_time;
            $delivery = $this->delivery_time;

            // Pastikan objek adalah Carbon instance
            if (!$pickup instanceof \Carbon\Carbon) {
                $pickup = \Carbon\Carbon::parse($pickup);
            }
            if (!$delivery instanceof \Carbon\Carbon) {
                $delivery = \Carbon\Carbon::parse($delivery);
            }

            return $delivery->diffInHours($pickup, true);
        }

        return null;
    }

    // Boot method - GABUNGAN KEDUA FUNGSI
    protected static function boot()
    {
        parent::boot();

        // Event saat menyimpan (saving) - untuk kalkulasi durasi
        static::saving(function ($delivery) {
            if ($delivery->status === self::STATUS_DELIVERED && $delivery->pickup_time && $delivery->delivery_time) {
                $delivery->duration = $delivery->calculateDuration();
            }
        });

        // Event saat mengupdate (updating) - untuk auto-clear cart
        static::updating(function ($delivery) {
            // Auto-clear cart when delivery is completed
            if ($delivery->status === self::STATUS_DELIVERED && $delivery->getOriginal('status') !== self::STATUS_DELIVERED) {
                // Clear cart for this user
                \App\Models\Cart::where('user_id', $delivery->order->user_id)->delete();
            }
        });
    }

    public function canBePickedUp()
    {
        return $this->status === self::STATUS_ASSIGNED;
    }

    public function canBeInTransit()
    {
        return $this->status === self::STATUS_PICKED_UP;
    }

    public function canBeDelivered()
    {
        return in_array($this->status, [self::STATUS_PICKED_UP, self::STATUS_IN_TRANSIT]);
    }

    public function markAsFailed($notes)
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'notes' => $notes
        ]);
    }
}
