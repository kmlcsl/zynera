<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DeliveryController as AdminDeliveryController;
use App\Http\Controllers\Admin\Reports\SalesController as AdminReportsSalesController;
use App\Http\Controllers\Admin\Reports\InventoryController as AdminReportsInventoryController;
use App\Http\Controllers\Admin\Reports\PerformanceController as AdminReportsPerformanceController;
use App\Http\Controllers\Admin\Reports\FinancialController as AdminReportsFinancialController;
use App\Http\Controllers\Admin\Reports\AnalyticsController as AdminReportsAnalyticsController;
use App\Http\Controllers\Admin\Reports\UsersController as AdminReportsUsersController;
use App\Http\Controllers\Admin\Reports\SystemController as AdminReportsSystemController;
use App\Http\Controllers\Admin\Reports\DashboardController as AdminReportsDashboardController;
use App\Http\Controllers\Admin\Reports\BulkController as AdminReportsBulkController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Login Google API
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/villages-by-district', [ProductController::class, 'getVillagesByDistrict'])->name('products.villages-by-district');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{slug}', [ProductController::class, 'category'])->name('products.category');

Route::get('/education', [HomeController::class, 'education'])->name('education.index');
Route::get('/about', [HomeController::class, 'about'])->name('about.index');

// Categories routes
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/{slug}', [CategoryController::class, 'show'])->name('show');
    Route::get('/{slug}/details', [CategoryController::class, 'details'])->name('details');
});

Route::post('/products/buy-now', [ProductController::class, 'buyNow'])->name('products.buy-now');
Route::get('/get-stats', [HomeController::class, 'getStats'])->name('home.stats');
Route::get('/demo', function () {
    return view('demo');
})->name('demo');

// Midtrans callback route (no auth required)
Route::post('/payments/midtrans/notification', [PaymentController::class, 'midtransNotification'])->name('payments.midtrans.notification');

// Manual payment sync for development
Route::middleware('auth')->post('/payments/{payment}/sync-status', [PaymentController::class, 'syncPaymentStatus'])->name('payments.sync-status');

// Manual order status sync for development
Route::middleware('auth')->get('/sync-order-status', function() {
    $ordersToUpdate = App\Models\Order::with('delivery')
        ->whereHas('delivery', function($query) {
            $query->where('status', 'delivered');
        })
        ->where('status', '!=', 'delivered')
        ->get();

    $updated = 0;
    foreach ($ordersToUpdate as $order) {
        $oldStatus = $order->status;
        $order->update(['status' => 'delivered']);
        $updated++;
    }

    return response()->json([
        'message' => "Synchronized {$updated} orders",
        'updated_count' => $updated
    ]);
})->name('sync.order.status');

// Authentication routes (dari Laravel Breeze)
require __DIR__ . '/auth.php';

// CSRF token refresh route
Route::get('/csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
})->name('csrf.token');

// Google OTP routes
Route::get('google/otp/verify', [SocialAuthController::class, 'showGoogleOtpForm'])->name('google.otp.verify');
Route::post('google/otp/verify', [SocialAuthController::class, 'verifyGoogleOtp'])->name('google.otp.verify.post');
Route::post('google/otp/resend', [SocialAuthController::class, 'resendGoogleOtp'])->name('google.otp.resend');
Route::get('google/user-type-selection', [SocialAuthController::class, 'showGoogleUserTypeSelection'])->name('google.user.type.selection');
Route::post('google/user-type-selection', [SocialAuthController::class, 'handleGoogleUserTypeSelection'])->name('google.user.type.selection.post');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {

    Route::prefix('profile')->name('profile.')->group(function () {
        // Main profile routes
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
        Route::delete('/destroy', [ProfileController::class, 'destroy'])->name('destroy');

        // Additional profile routes
        Route::patch('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
        Route::delete('/remove-avatar', [ProfileController::class, 'removeAvatar'])->name('remove-avatar');
        Route::patch('/update-notifications', [ProfileController::class, 'updateNotifications'])->name('update-notifications');

        // Profile sub-pages
        Route::get('/orders', [ProfileController::class, 'orderHistory'])->name('orders');
        Route::get('/reviews', [ProfileController::class, 'reviewHistory'])->name('reviews');
    });

    // Google OAuth additional routes
    Route::prefix('auth/google')->name('auth.google.')->group(function () {
        Route::get('/link', [SocialAuthController::class, 'linkGoogle'])->name('link');
        Route::post('/unlink', [SocialAuthController::class, 'unlinkGoogle'])->name('unlink');
    });

    // Cart routes
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');

    Route::post('/products/buy-now', [ProductController::class, 'buyNow'])->name('products.buy-now');

    // Order routes
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/tracking', [OrderController::class, 'tracking'])->name('orders.tracking');
    Route::get('/orders/{order}/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/delivery-status', [OrderController::class, 'getDeliveryStatus'])->name('orders.delivery-status');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Payment routes
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::post('/payments/{payment}/upload-proof', [PaymentController::class, 'uploadProof'])->name('payments.upload-proof');
    Route::get('/payments/{payment}/status', [PaymentController::class, 'checkStatus'])->name('payments.check-status');
    Route::post('/payments/{payment}/simulate-qris', [PaymentController::class, 'simulateQrisPayment'])->name('payments.simulate-qris');
    Route::post('/payments/{payment}/simulate-midtrans', [PaymentController::class, 'simulateMidtransPayment'])->name('payments.simulate-midtrans');

    // Admin only payment routes
    Route::middleware('user.type:admin')->group(function () {
        Route::post('/payments/{payment}/confirm', [PaymentController::class, 'confirmPayment'])->name('payments.confirm');
    });

    // Review routes
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// =============================================================================
// UNIFIED ADMIN PANEL - All admin panel users use same interface
// =============================================================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Check if user is admin panel user (not konsumen) using middleware name
    Route::middleware(['admin.panel.access'])->group(function () {

        // Dashboard - Accessible by all admin panel users
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/products/villages-by-district', [AdminProductController::class, 'getVillagesByDistrict'])->name('products.villages-by-district');

        // =================================================================
        // USER MANAGEMENT - Only for ADMIN
        // =================================================================
        Route::middleware('user.type:admin')->group(function () {
            // User routes - PENTING: Urutkan dari yang paling spesifik ke general
            Route::get('/users/permissions', [AdminUserController::class, 'permissionsIndex'])->name('users.permissions.index');
            Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
            Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
            Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
            Route::post('/users/bulk-action', [AdminUserController::class, 'bulkAction'])->name('users.bulk-action');

            // Routes dengan {user} parameter - PINDAH KE BAWAH
            Route::get('/users/{user}/permissions', [AdminUserController::class, 'permissions'])->name('users.permissions.show');
            Route::post('/users/{user}/permissions', [AdminUserController::class, 'updatePermissions'])->name('users.permissions.update');
            Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
            Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
            Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
            Route::patch('/users/{user}/toggle-verification', [AdminUserController::class, 'toggleVerification'])->name('users.toggle-verification');
        });

        // =================================================================
        // PRODUCT MANAGEMENT - Admin & Produsen
        // =================================================================
        Route::middleware(['product.access'])->group(function () {
            Route::resource('products', AdminProductController::class);
        });

        // =================================================================
        // ORDER MANAGEMENT - Admin, Produsen, Kurir
        // =================================================================
        Route::middleware(['order.access'])->group(function () {
            Route::resource('orders', AdminOrderController::class);
            Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
            Route::post('/orders/bulk-status', [AdminOrderController::class, 'bulkUpdateStatus'])->name('orders.bulk-status');

            // ASSIGN COURIER ROUTES - NEW
            Route::get('/orders/available-couriers', [AdminOrderController::class, 'getAvailableCouriers'])->name('orders.available-couriers');
            Route::post('/orders/{order}/assign-courier', [AdminOrderController::class, 'assignCourier'])->name('orders.assign-courier');
            Route::delete('/orders/{order}/remove-courier', [AdminOrderController::class, 'removeCourierAssignment'])->name('orders.remove-courier');
            Route::post('/orders/bulk-assign-courier', [AdminOrderController::class, 'bulkAssignCourier'])->name('orders.bulk-assign-courier');
            Route::post('/orders/{order}/whatsapp-followup', [AdminOrderController::class, 'whatsappFollowup'])->name('orders.whatsapp-followup');
        });

        // =================================================================
        // PAYMENT MANAGEMENT - Admin, Produsen
        // =================================================================
        Route::middleware(['payment.access'])->group(function () {
            Route::resource('payments', AdminPaymentController::class);

            // Payment specific actions - Admin only
            Route::middleware(['user.type:admin'])->group(function () {
                Route::post('/payments/{payment}/confirm', [AdminPaymentController::class, 'confirmPayment'])->name('payments.confirm');
                Route::post('/payments/{payment}/reject', [AdminPaymentController::class, 'rejectPayment'])->name('payments.reject');
                Route::post('/payments/bulk-confirm', [AdminPaymentController::class, 'bulkConfirmPayments'])->name('payments.bulk-confirm');
                Route::post('/payments/bulk-reject', [AdminPaymentController::class, 'bulkRejectPayments'])->name('payments.bulk-reject');
            });

            // Export and receipt routes - Admin & Produsen
            Route::post('/payments/export-selected', [AdminPaymentController::class, 'exportSelected'])->name('payments.export-selected');
            Route::get('/payments/{payment}/receipt', [AdminPaymentController::class, 'receipt'])->name('payments.receipt');
        });

        // =================================================================
        // CATEGORY MANAGEMENT - Admin Only
        // =================================================================
        Route::middleware('user.type:admin')->group(function () {
            Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
            Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
            Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
            Route::get('/categories/{category}', [AdminCategoryController::class, 'show'])->name('categories.show');
            Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

            // Additional routes
            Route::post('/categories/bulk-action', [AdminCategoryController::class, 'bulkAction'])->name('categories.bulk-action');
            Route::patch('/categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        });

        // =================================================================
        // DELIVERY MANAGEMENT - Admin & Kurir & Produsen
        // =================================================================
        Route::middleware(['delivery.access'])->group(function () {
            Route::resource('deliveries', AdminDeliveryController::class);

            // AJAX routes for delivery management
            Route::patch('/deliveries/{delivery}/status', [AdminDeliveryController::class, 'updateStatus'])->name('deliveries.update-status');
            Route::post('/deliveries/{delivery}/assign', [AdminDeliveryController::class, 'assignCourier'])->name('deliveries.assign-courier');
            Route::post('/deliveries/bulk-assign', [AdminDeliveryController::class, 'bulkAssignCourier'])->name('deliveries.bulk-assign');
            Route::post('/deliveries/{delivery}/failed', [AdminDeliveryController::class, 'markAsFailed'])->name('deliveries.mark-failed');
        });

        // =================================================================
        // REPORTS MANAGEMENT - Admin & Produsen
        // =================================================================
        Route::middleware(['reports.access'])->prefix('reports')->name('reports.')->group(function () {

            // Sales Reports - Admin & Produsen
            Route::get('/sales', [AdminReportsSalesController::class, 'index'])->name('sales.index');
            Route::post('/sales/export', [AdminReportsSalesController::class, 'export'])->name('sales.export');

            // Inventory Reports - Admin & Produsen
            Route::get('/inventory', [AdminReportsInventoryController::class, 'index'])->name('inventory.index');
            Route::post('/inventory/export', [AdminReportsInventoryController::class, 'export'])->name('inventory.export');

            // Performance Reports - Admin & Produsen
            Route::get('/performance', [AdminReportsPerformanceController::class, 'index'])->name('performance.index');
            Route::post('/performance/insights', [AdminReportsPerformanceController::class, 'generateInsights'])->name('performance.insights');
            Route::post('/performance/export', [AdminReportsPerformanceController::class, 'export'])->name('performance.export');

            // Financial Reports - Admin & Produsen
            Route::get('/financial', [AdminReportsFinancialController::class, 'index'])->name('financial.index');
            Route::get('/financial/profit-loss', [AdminReportsFinancialController::class, 'profitLoss'])->name('financial.profit-loss');
            Route::post('/financial/export', [AdminReportsFinancialController::class, 'export'])->name('financial.export');

            // Analytics Reports - Admin & Produsen
            Route::get('/analytics', [AdminReportsAnalyticsController::class, 'index'])->name('analytics.index');
            Route::get('/analytics/customer-behavior', [AdminReportsAnalyticsController::class, 'customerBehavior'])->name('analytics.customer-behavior');
            Route::post('/analytics/export', [AdminReportsAnalyticsController::class, 'export'])->name('analytics.export');

            // Users Reports - ADMIN ONLY
            Route::middleware(['user.type:admin'])->group(function () {
                Route::get('/users', [AdminReportsUsersController::class, 'index'])->name('users.index');
                Route::get('/users/registration-stats', [AdminReportsUsersController::class, 'registrationStats'])->name('users.registration-stats');
                Route::get('/users/activity', [AdminReportsUsersController::class, 'activityReport'])->name('users.activity');
                Route::post('/users/export', [AdminReportsUsersController::class, 'export'])->name('users.export');
            });

            // System Reports - ADMIN ONLY
            Route::middleware(['user.type:admin'])->group(function () {
                Route::get('/system', [AdminReportsSystemController::class, 'index'])->name('system.index');
                Route::get('/system/performance', [AdminReportsSystemController::class, 'performanceMonitoring'])->name('system.performance');
                Route::get('/system/error-logs', [AdminReportsSystemController::class, 'errorLogs'])->name('system.error-logs');
                Route::post('/system/export', [AdminReportsSystemController::class, 'export'])->name('system.export');
            });

            // General Report Actions
            Route::post('/dashboard/export', [AdminReportsDashboardController::class, 'exportPDF'])->name('dashboard.export');
            Route::post('/bulk-export', [AdminReportsBulkController::class, 'export'])->name('bulk.export');
        });
    });
});

// =============================================================================
// REDIRECT OLD ROUTES TO UNIFIED ADMIN PANEL
// =============================================================================

// Redirect producer routes to admin panel
Route::middleware(['auth', 'user.type:produsen'])->group(function () {
    Route::get('/producer/dashboard', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/producer/{any}', function () {
        return redirect()->route('admin.dashboard');
    })->where('any', '.*');
});

// Redirect courier routes to admin panel
Route::middleware(['auth', 'user.type:kurir'])->group(function () {
    Route::get('/courier/dashboard', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/courier/{any}', function () {
        return redirect()->route('admin.dashboard');
    })->where('any', '.*');
});
