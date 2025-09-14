<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class OrderCreated extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pesanan Baru - ' . $this->order->order_number)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Pesanan baru telah dibuat dengan nomor: ' . $this->order->order_number)
            ->line('Total: Rp ' . number_format($this->order->total_amount, 0, ',', '.'))
            ->action('Lihat Pesanan', route('orders.show', $this->order))
            ->line('Terima kasih telah berbelanja di AgriConnect!');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'total_amount' => $this->order->total_amount,
            'message' => 'Pesanan baru telah dibuat: ' . $this->order->order_number
        ];
    }
}
