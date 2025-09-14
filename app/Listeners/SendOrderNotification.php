<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Notifications\OrderCreated as OrderCreatedNotification;
use App\Models\User;

class SendOrderNotification
{
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        // Notify customer
        $order->user->notify(new OrderCreatedNotification($order));

        // Notify admin
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrderCreatedNotification($order));
        }

        // Notify producers
        $producerIds = $order->orderItems->pluck('product.user_id')->unique();
        $producers = User::whereIn('id', $producerIds)->get();
        foreach ($producers as $producer) {
            $producer->notify(new OrderCreatedNotification($order));
        }
    }
}
