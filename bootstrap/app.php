<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckUserType;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'user.type' => CheckUserType::class,
            'admin.panel.access' => \App\Http\Middleware\AdminPanelAccess::class,
            'product.access' => \App\Http\Middleware\ProductAccess::class,
            'order.access' => \App\Http\Middleware\OrderAccess::class,
            'payment.access' => \App\Http\Middleware\PaymentAccess::class,
            'delivery.access' => \App\Http\Middleware\DeliveryAccess::class,
            'reports.access' => \App\Http\Middleware\ReportsAccess::class,
            'check.registration.session' => \App\Http\Middleware\CheckRegistrationSession::class,
        ]);

        // Apply middleware to groups
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api', // Gunakan string alias, bukan method
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Apply throttling to specific routes in route files
        // Throttling dikonfigurasi langsung di routes, bukan di bootstrap
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
