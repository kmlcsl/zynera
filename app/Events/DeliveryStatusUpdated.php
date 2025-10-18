<?php

namespace App\Events;

use App\Models\Delivery;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeliveryStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $delivery;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(Delivery $delivery, string $oldStatus, string $newStatus)
    {
        $this->delivery = $delivery->load(['order', 'courier']);
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcast ke private channel untuk user yang memiliki order ini
        return [
            new PrivateChannel('order.' . $this->delivery->order->id),
            new PrivateChannel('user.' . $this->delivery->order->user_id)
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'delivery.status.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'delivery' => [
                'id' => $this->delivery->id,
                'order_id' => $this->delivery->order_id,
                'status' => $this->newStatus,
                'status_label' => $this->delivery->status_label,
                'old_status' => $this->oldStatus,
                'courier' => $this->delivery->courier ? [
                    'id' => $this->delivery->courier->id,
                    'name' => $this->delivery->courier->name
                ] : null,
                'updated_at' => $this->delivery->updated_at->toISOString(),
                'timestamps' => [
                    'assigned_at' => $this->delivery->assigned_at?->toISOString(),
                    'picked_up_at' => $this->delivery->picked_up_at?->toISOString(),
                    'delivered_at' => $this->delivery->delivered_at?->toISOString(),
                ]
            ],
            'order' => [
                'id' => $this->delivery->order->id,
                'order_number' => $this->delivery->order->order_number,
                'status' => $this->delivery->order->status,
                'status_label' => $this->delivery->order->status_label
            ],
            'message' => "Status pengiriman diubah dari {$this->getStatusLabel($this->oldStatus)} ke {$this->delivery->status_label}",
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * Get status label
     */
    private function getStatusLabel($status): string
    {
        $labels = [
            'assigned' => 'Ditugaskan ke Kurir',
            'picked_up' => 'Barang Diambil Kurir',
            'in_transit' => 'Dalam Pengiriman',
            'delivered' => 'Barang Terkirim',
            'failed' => 'Pengiriman Gagal',
        ];

        return $labels[$status] ?? 'Unknown';
    }
}