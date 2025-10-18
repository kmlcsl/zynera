<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Channel untuk order-specific updates
Broadcast::channel('order.{orderId}', function ($user, $orderId) {
    $order = Order::find($orderId);
    
    if (!$order) {
        return false;
    }
    
    // User dapat listen ke channel order mereka sendiri
    if ((int) $user->id === (int) $order->user_id) {
        return true;
    }
    
    // Admin dapat listen ke semua order channels
    if ($user->user_type === 'admin') {
        return true;
    }
    
    // Kurir dapat listen ke channel order yang ditugaskan ke mereka
    if ($user->user_type === 'kurir' && $order->delivery) {
        return (int) $user->id === (int) $order->delivery->courier_id;
    }
    
    // Produsen dapat listen ke channel order yang berisi produk mereka
    if ($user->user_type === 'produsen') {
        return $order->orderItems()
            ->whereHas('product', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->exists();
    }
    
    return false;
});

// Channel untuk user-specific notifications
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});