<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine whether the user can view any products.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->user_type, ['admin', 'produsen']);
    }

    /**
     * Determine whether the user can view the product.
     */
    public function view(User $user, Product $product): bool
    {
        switch ($user->user_type) {
            case 'admin':
                return true;
            
            case 'produsen':
                return $product->user_id === $user->id;
            
            case 'konsumen':
                return $product->is_active;
            
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can create products.
     */
    public function create(User $user): bool
    {
        return in_array($user->user_type, ['admin', 'produsen']);
    }

    /**
     * Determine whether the user can update the product.
     */
    public function update(User $user, Product $product): bool
    {
        switch ($user->user_type) {
            case 'admin':
                return true;
            
            case 'produsen':
                return $product->user_id === $user->id;
            
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can delete the product.
     */
    public function delete(User $user, Product $product): bool
    {
        switch ($user->user_type) {
            case 'admin':
                return true;
            
            case 'produsen':
                return $product->user_id === $user->id;
            
            default:
                return false;
        }
    }
}
