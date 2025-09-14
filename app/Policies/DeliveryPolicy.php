<?php

namespace App\Policies;

use App\Models\Delivery;
use App\Models\User;

class DeliveryPolicy
{
    /**
     * Determine whether the user can view any deliveries.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->user_type, ['admin', 'kurir', 'produsen']);
    }

    /**
     * Determine whether the user can view the delivery.
     */
    public function view(User $user, Delivery $delivery): bool
    {
        switch ($user->user_type) {
            case 'admin':
                return true;

            case 'kurir':
                // Courier can view their assigned deliveries
                return $delivery->courier_id === $user->id;
            
            case 'produsen':
                // Producer can view deliveries for orders containing their products
                return $delivery->order->orderItems()
                    ->whereHas('product', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->exists();

            default:
                return false;
        }
    }

    /**
     * Determine whether the user can create deliveries.
     */
    public function create(User $user): bool
    {
        return in_array($user->user_type, ['admin', 'produsen']);
    }

    /**
     * Determine whether the user can update the delivery.
     */
    public function update(User $user, Delivery $delivery): bool
    {
        switch ($user->user_type) {
            case 'admin':
                return true;

            case 'kurir':
                // Courier can update their assigned deliveries
                return $delivery->courier_id === $user->id &&
                    !in_array($delivery->status, [Delivery::STATUS_DELIVERED, Delivery::STATUS_FAILED]);
            
            case 'produsen':
                // Producer can update deliveries for orders containing their products (limited actions)
                return $delivery->order->orderItems()
                    ->whereHas('product', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->exists();

            default:
                return false;
        }
    }

    /**
     * Determine whether the user can delete the delivery.
     */
    public function delete(User $user, Delivery $delivery): bool
    {
        // Only admin can delete failed deliveries
        return $user->user_type === 'admin' && $delivery->status === Delivery::STATUS_FAILED;
    }
}
