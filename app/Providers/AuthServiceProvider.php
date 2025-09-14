<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Delivery;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\DeliveryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Order::class => OrderPolicy::class,
        Product::class => ProductPolicy::class,
        Delivery::class => DeliveryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
