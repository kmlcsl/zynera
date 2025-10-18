<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Delivery;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'method',
        'amount',
        'status',
        'reference_number',
        'proof_image',
        'bank_name',
        'account_number',
        'account_holder',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'float',
        'paid_at' => 'datetime'
    ];

    // Payment methods
    const METHOD_COD = 'cod';
    const METHOD_TRANSFER = 'transfer';
    const METHOD_QRIS = 'qris';
    const METHOD_MANUAL = 'manual';
    const METHOD_MIDTRANS = 'midtrans';

    // Payment status
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Accessors
    public function getMethodLabelAttribute()
    {
        $labels = [
            self::METHOD_COD => 'Bayar di Tempat (COD)',
            self::METHOD_TRANSFER => 'Transfer Bank',
            self::METHOD_QRIS => 'QRIS',
            self::METHOD_MANUAL => 'Pembayaran Manual',
            self::METHOD_MIDTRANS => 'Pembayaran Otomatis (Midtrans)'
        ];

        return $labels[$this->method] ?? 'Unknown';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_PAID => 'Sudah Dibayar',
            self::STATUS_FAILED => 'Pembayaran Gagal',
            self::STATUS_REFUNDED => 'Dikembalikan'
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            self::STATUS_PENDING => 'yellow',
            self::STATUS_PAID => 'green',
            self::STATUS_FAILED => 'red',
            self::STATUS_REFUNDED => 'blue'
        ];

        return $colors[$this->status] ?? 'gray';
    }

    // Methods
    public function isPaid()
    {
        return $this->status === self::STATUS_PAID;
    }

    public function markAsPaid($referenceNumber = null)
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => Carbon::now(),
            'reference_number' => $referenceNumber
        ]);

        // Update order status sesuai payment method
        if ($this->method === 'cod') {
            $this->order->update(['status' => Order::STATUS_PAID]);
            // Untuk COD, cart dibersihkan setelah delivery selesai
        } else {
            $this->order->update(['status' => Order::STATUS_PAID]);
            // Untuk non-COD, langsung clear cart setelah payment confirmed
            Cart::where('user_id', $this->order->user_id)->delete();
        }

        // Auto-create delivery record untuk tracking
        if (!$this->order->delivery) {
            Delivery::create([
                'order_id' => $this->order->id,
                'status' => Delivery::STATUS_ASSIGNED,
                'assigned_at' => now(),
                'notes' => 'Menunggu penugasan kurir oleh produsen'
            ]);
        }
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}
