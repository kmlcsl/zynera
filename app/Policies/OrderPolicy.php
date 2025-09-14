<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        // User can view their own orders (including konsumen)
        if ($user->id === $order->user_id) {
            return true;
        }

        // Admin can view all orders
        if ($user->user_type === 'admin') {
            return true;
        }

        // Producer can view orders containing their products
        if ($user->user_type === 'produsen') {
            try {
                return $order->orderItems()
                    ->whereHas('product', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->exists();
            } catch (\Exception $e) {
                return false;
            }
        }

        // Courier can view orders assigned to them
        if ($user->user_type === 'kurir') {
            return $order->delivery && $order->delivery->courier_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        // Only admin can update orders
        if ($user->user_type === 'admin') {
            return true;
        }

        // Producer can update status of orders containing their products
        if ($user->user_type === 'produsen') {
            return $order->orderItems()
                ->whereHas('product', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->exists();
        }

        // Customer can cancel their own pending orders
        if ($user->id === $order->user_id && $order->canBeCancelled()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        // Only admin can delete orders
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, Order $order): bool
    {
        // Customer can cancel their own orders if cancellable
        if ($user->id === $order->user_id && $order->canBeCancelled()) {
            return true;
        }

        // Admin can cancel any order
        if ($user->user_type === 'admin') {
            return true;
        }

        return false;
    }
}
