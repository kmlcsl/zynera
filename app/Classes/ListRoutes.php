<?php

namespace App\Classes;

class ListRoutes
{
    function getDataAuth($index = null)
    {
        $data = [
            [
                'title' => 'Dashboard',
                'icon' => 'fas fa-th-large',
                'user_types' => ['admin', 'produsen', 'kurir'],
                'item' => [
                    [
                        'type' => 'index',
                        'title' => 'Dashboard',
                        'method' => 'get',
                        'url' => '/admin/dashboard',
                        'controller' => 'Admin\DashboardController@index',
                        'name' => 'admin.dashboard',
                        'middleware' => 'admin.panel.access',
                        'user_types' => ['admin', 'produsen', 'kurir'],
                    ]
                ]
            ],
            [
                'title' => 'Manajemen User',
                'icon' => 'fas fa-users-cog',
                'user_types' => ['admin'],
                'item' => [
                    [
                        'type' => 'index',
                        'title' => 'Daftar Users',
                        'method' => 'get',
                        'url' => '/admin/users',
                        'controller' => 'Admin\UserController@index',
                        'name' => 'admin.users.index',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'create',
                        'title' => 'Tambah User',
                        'method' => 'get',
                        'url' => '/admin/users/create',
                        'controller' => 'Admin\UserController@create',
                        'name' => 'admin.users.create',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'store',
                        'title' => 'Simpan User',
                        'method' => 'post',
                        'url' => '/admin/users',
                        'controller' => 'Admin\UserController@store',
                        'name' => 'admin.users.store',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'show',
                        'title' => 'Detail User',
                        'method' => 'get',
                        'url' => '/admin/users/{user}',
                        'controller' => 'Admin\UserController@show',
                        'name' => 'admin.users.show',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'edit',
                        'title' => 'Edit User',
                        'method' => 'get',
                        'url' => '/admin/users/{user}/edit',
                        'controller' => 'Admin\UserController@edit',
                        'name' => 'admin.users.edit',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'update',
                        'title' => 'Update User',
                        'method' => ['put', 'patch'],
                        'url' => '/admin/users/{user}',
                        'controller' => 'Admin\UserController@update',
                        'name' => 'admin.users.update',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'delete',
                        'title' => 'Hapus User',
                        'method' => 'delete',
                        'url' => '/admin/users/{user}',
                        'controller' => 'Admin\UserController@destroy',
                        'name' => 'admin.users.destroy',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'bulk_action',
                        'title' => 'Bulk Action User',
                        'method' => 'post',
                        'url' => '/admin/users/bulk-action',
                        'controller' => 'Admin\UserController@bulkAction',
                        'name' => 'admin.users.bulk-action',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'toggle_verification',
                        'title' => 'Toggle User Verification',
                        'method' => 'patch',
                        'url' => '/admin/users/{user}/toggle-verification',
                        'controller' => 'Admin\UserController@toggleVerification',
                        'name' => 'admin.users.toggle-verification',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'permission_index',
                        'title' => 'Daftar Permission Users',
                        'method' => 'get',
                        'url' => '/admin/users/permissions',
                        'controller' => 'Admin\UserController@permissionsIndex',
                        'name' => 'admin.users.permissions.index',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'permission_show',
                        'title' => 'Setting Permission User',
                        'method' => 'get',
                        'url' => '/admin/users/{user}/permissions',
                        'controller' => 'Admin\UserController@permissions',
                        'name' => 'admin.users.permissions.show',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'permission_update',
                        'title' => 'Update Permission User',
                        'method' => 'post',
                        'url' => '/admin/users/{user}/permissions',
                        'controller' => 'Admin\UserController@updatePermissions',
                        'name' => 'admin.users.permissions.update',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                ]
            ],
            [
                'title' => 'Manajemen Produk',
                'icon' => 'fas fa-shopping-basket',
                'user_types' => ['admin', 'produsen'],
                'item' => [
                    [
                        'type' => 'index',
                        'title' => 'Daftar Produk',
                        'method' => 'get',
                        'url' => '/admin/products',
                        'controller' => 'Admin\ProductController@index',
                        'name' => 'admin.products.index',
                        'middleware' => 'product.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'create',
                        'title' => 'Tambah Produk',
                        'method' => 'get',
                        'url' => '/admin/products/create',
                        'controller' => 'Admin\ProductController@create',
                        'name' => 'admin.products.create',
                        'middleware' => 'product.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'store',
                        'title' => 'Simpan Produk',
                        'method' => 'post',
                        'url' => '/admin/products',
                        'controller' => 'Admin\ProductController@store',
                        'name' => 'admin.products.store',
                        'middleware' => 'product.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'show',
                        'title' => 'Detail Produk',
                        'method' => 'get',
                        'url' => '/admin/products/{product}',
                        'controller' => 'Admin\ProductController@show',
                        'name' => 'admin.products.show',
                        'middleware' => 'product.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'edit',
                        'title' => 'Edit Produk',
                        'method' => 'get',
                        'url' => '/admin/products/{product}/edit',
                        'controller' => 'Admin\ProductController@edit',
                        'name' => 'admin.products.edit',
                        'middleware' => 'product.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'update',
                        'title' => 'Update Produk',
                        'method' => ['put', 'patch'],
                        'url' => '/admin/products/{product}',
                        'controller' => 'Admin\ProductController@update',
                        'name' => 'admin.products.update',
                        'middleware' => 'product.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'delete',
                        'title' => 'Hapus Produk',
                        'method' => 'delete',
                        'url' => '/admin/products/{product}',
                        'controller' => 'Admin\ProductController@destroy',
                        'name' => 'admin.products.destroy',
                        'middleware' => 'product.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                ]
            ],
            [
                'title' => 'Manajemen Pembayaran',
                'icon' => 'fas fa-credit-card',
                'user_types' => ['admin', 'produsen'],
                'item' => [
                    [
                        'type' => 'index',
                        'title' => 'Daftar Pembayaran',
                        'method' => 'get',
                        'url' => '/admin/payments',
                        'controller' => 'Admin\PaymentController@index',
                        'name' => 'admin.payments.index',
                        'middleware' => 'payment.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'create',
                        'title' => 'Tambah Pembayaran',
                        'method' => 'get',
                        'url' => '/admin/payments/create',
                        'controller' => 'Admin\PaymentController@create',
                        'name' => 'admin.payments.create',
                        'middleware' => 'payment.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'store',
                        'title' => 'Simpan Pembayaran',
                        'method' => 'post',
                        'url' => '/admin/payments',
                        'controller' => 'Admin\PaymentController@store',
                        'name' => 'admin.payments.store',
                        'middleware' => 'payment.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'show',
                        'title' => 'Detail Pembayaran',
                        'method' => 'get',
                        'url' => '/admin/payments/{payment}',
                        'controller' => 'Admin\PaymentController@show',
                        'name' => 'admin.payments.show',
                        'middleware' => 'payment.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'edit',
                        'title' => 'Edit Pembayaran',
                        'method' => 'get',
                        'url' => '/admin/payments/{payment}/edit',
                        'controller' => 'Admin\PaymentController@edit',
                        'name' => 'admin.payments.edit',
                        'middleware' => 'payment.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'update',
                        'title' => 'Update Pembayaran',
                        'method' => ['put', 'patch'],
                        'url' => '/admin/payments/{payment}',
                        'controller' => 'Admin\PaymentController@update',
                        'name' => 'admin.payments.update',
                        'middleware' => 'payment.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'delete',
                        'title' => 'Hapus Pembayaran',
                        'method' => 'delete',
                        'url' => '/admin/payments/{payment}',
                        'controller' => 'Admin\PaymentController@destroy',
                        'name' => 'admin.payments.destroy',
                        'middleware' => 'payment.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                ]
            ],
            [
                'title' => 'Manajemen Pesanan',
                'icon' => 'fas fa-shopping-cart',
                'user_types' => ['admin', 'produsen', 'kurir'],
                'item' => [
                    [
                        'type' => 'index',
                        'title' => 'Daftar Pesanan',
                        'method' => 'get',
                        'url' => '/admin/orders',
                        'controller' => 'Admin\OrderController@index',
                        'name' => 'admin.orders.index',
                        'middleware' => 'order.access',
                        'user_types' => ['admin', 'produsen', 'kurir'],
                    ],
                    [
                        'type' => 'create',
                        'title' => 'Tambah Pesanan',
                        'method' => 'get',
                        'url' => '/admin/orders/create',
                        'controller' => 'Admin\OrderController@create',
                        'name' => 'admin.orders.create',
                        'middleware' => 'order.access',
                        'user_types' => ['admin', 'produsen', 'kurir'],
                    ],
                    [
                        'type' => 'store',
                        'title' => 'Simpan Pesanan',
                        'method' => 'post',
                        'url' => '/admin/orders',
                        'controller' => 'Admin\OrderController@store',
                        'name' => 'admin.orders.store',
                        'middleware' => 'order.access',
                        'user_types' => ['admin', 'produsen', 'kurir'],
                    ],
                    [
                        'type' => 'show',
                        'title' => 'Detail Pesanan',
                        'method' => 'get',
                        'url' => '/admin/orders/{order}',
                        'controller' => 'Admin\OrderController@show',
                        'name' => 'admin.orders.show',
                        'middleware' => 'order.access',
                        'user_types' => ['admin', 'produsen', 'kurir'],
                    ],
                    [
                        'type' => 'edit',
                        'title' => 'Edit Pesanan',
                        'method' => 'get',
                        'url' => '/admin/orders/{order}/edit',
                        'controller' => 'Admin\OrderController@edit',
                        'name' => 'admin.orders.edit',
                        'middleware' => 'order.access',
                        'user_types' => ['admin', 'produsen', 'kurir'],
                    ],
                    [
                        'type' => 'update',
                        'title' => 'Update Pesanan',
                        'method' => ['put', 'patch'],
                        'url' => '/admin/orders/{order}',
                        'controller' => 'Admin\OrderController@update',
                        'name' => 'admin.orders.update',
                        'middleware' => 'order.access',
                        'user_types' => ['admin', 'produsen', 'kurir'],
                    ],
                    [
                        'type' => 'delete',
                        'title' => 'Hapus Pesanan',
                        'method' => 'delete',
                        'url' => '/admin/orders/{order}',
                        'controller' => 'Admin\OrderController@destroy',
                        'name' => 'admin.orders.destroy',
                        'middleware' => 'order.access',
                        'user_types' => ['admin', 'produsen', 'kurir'],
                    ],
                    [
                        'type' => 'assign_courier_list',
                        'title' => 'Lihat Kurir Tersedia',
                        'method' => 'get',
                        'url' => '/admin/orders/available-couriers',
                        'controller' => 'Admin\OrderController@getAvailableCouriers',
                        'name' => 'admin.orders.available-couriers',
                        'middleware' => 'order.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'assign_courier',
                        'title' => 'Assign Kurir ke Pesanan',
                        'method' => 'post',
                        'url' => '/admin/orders/{order}/assign-courier',
                        'controller' => 'Admin\OrderController@assignCourier',
                        'name' => 'admin.orders.assign-courier',
                        'middleware' => 'order.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'remove_courier',
                        'title' => 'Hapus Assignment Kurir',
                        'method' => 'post',
                        'url' => '/admin/orders/{order}/remove-courier',
                        'controller' => 'Admin\OrderController@removeCourierAssignment',
                        'name' => 'admin.orders.remove-courier',
                        'middleware' => 'order.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                ]
            ],
            [
                'title' => 'Manajemen Pengiriman',
                'icon' => 'fas fa-truck',
                'user_types' => ['admin', 'kurir'],
                'item' => [
                    [
                        'type' => 'index',
                        'title' => 'Daftar Pengiriman',
                        'method' => 'get',
                        'url' => '/admin/deliveries',
                        'controller' => 'Admin\DeliveryController@index',
                        'name' => 'admin.deliveries.index',
                        'middleware' => 'delivery.access',
                        'user_types' => ['admin', 'kurir'],
                    ],
                    [
                        'type' => 'create',
                        'title' => 'Tambah Pengiriman',
                        'method' => 'get',
                        'url' => '/admin/deliveries/create',
                        'controller' => 'Admin\DeliveryController@create',
                        'name' => 'admin.deliveries.create',
                        'middleware' => 'delivery.access',
                        'user_types' => ['admin', 'kurir'],
                    ],
                    [
                        'type' => 'store',
                        'title' => 'Simpan Pengiriman',
                        'method' => 'post',
                        'url' => '/admin/deliveries',
                        'controller' => 'Admin\DeliveryController@store',
                        'name' => 'admin.deliveries.store',
                        'middleware' => 'delivery.access',
                        'user_types' => ['admin', 'kurir'],
                    ],
                    [
                        'type' => 'show',
                        'title' => 'Detail Pengiriman',
                        'method' => 'get',
                        'url' => '/admin/deliveries/{delivery}',
                        'controller' => 'Admin\DeliveryController@show',
                        'name' => 'admin.deliveries.show',
                        'middleware' => 'delivery.access',
                        'user_types' => ['admin', 'kurir'],
                    ],
                    [
                        'type' => 'edit',
                        'title' => 'Edit Pengiriman',
                        'method' => 'get',
                        'url' => '/admin/deliveries/{delivery}/edit',
                        'controller' => 'Admin\DeliveryController@edit',
                        'name' => 'admin.deliveries.edit',
                        'middleware' => 'delivery.access',
                        'user_types' => ['admin', 'kurir'],
                    ],
                    [
                        'type' => 'update',
                        'title' => 'Update Status Pengiriman',
                        'method' => ['put', 'patch'],
                        'url' => '/admin/deliveries/{delivery}',
                        'controller' => 'Admin\DeliveryController@update',
                        'name' => 'admin.deliveries.update',
                        'middleware' => 'delivery.access',
                        'user_types' => ['admin', 'kurir'],
                    ],
                    [
                        'type' => 'delete',
                        'title' => 'Hapus Pengiriman',
                        'method' => 'delete',
                        'url' => '/admin/deliveries/{delivery}',
                        'controller' => 'Admin\DeliveryController@destroy',
                        'name' => 'admin.deliveries.destroy',
                        'middleware' => 'delivery.access',
                        'user_types' => ['admin', 'kurir'],
                    ],
                ]
            ],
            [
                'title' => 'Manajemen Kategori',
                'icon' => 'fas fa-tags',
                'user_types' => ['admin'],
                'item' => [
                    [
                        'type' => 'index',
                        'title' => 'Daftar Kategori',
                        'method' => 'get',
                        'url' => '/admin/categories',
                        'controller' => 'Admin\CategoryController@index',
                        'name' => 'admin.categories.index',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'create',
                        'title' => 'Tambah Kategori',
                        'method' => 'get',
                        'url' => '/admin/categories/create',
                        'controller' => 'Admin\CategoryController@create',
                        'name' => 'admin.categories.create',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'store',
                        'title' => 'Simpan Kategori',
                        'method' => 'post',
                        'url' => '/admin/categories',
                        'controller' => 'Admin\CategoryController@store',
                        'name' => 'admin.categories.store',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'show',
                        'title' => 'Detail Kategori',
                        'method' => 'get',
                        'url' => '/admin/categories/{category}',
                        'controller' => 'Admin\CategoryController@show',
                        'name' => 'admin.categories.show',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'edit',
                        'title' => 'Edit Kategori',
                        'method' => 'get',
                        'url' => '/admin/categories/{category}/edit',
                        'controller' => 'Admin\CategoryController@edit',
                        'name' => 'admin.categories.edit',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'update',
                        'title' => 'Update Kategori',
                        'method' => ['put', 'patch'],
                        'url' => '/admin/categories/{category}',
                        'controller' => 'Admin\CategoryController@update',
                        'name' => 'admin.categories.update',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'delete',
                        'title' => 'Hapus Kategori',
                        'method' => 'delete',
                        'url' => '/admin/categories/{category}',
                        'controller' => 'Admin\CategoryController@destroy',
                        'name' => 'admin.categories.destroy',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                ]
            ],
            [
                'title' => 'Laporan',
                'icon' => 'fas fa-chart-line',
                'user_types' => ['admin', 'produsen'],
                'item' => [
                    // Sales Reports
                    [
                        'type' => 'index',
                        'title' => 'Laporan Penjualan',
                        'method' => 'get',
                        'url' => '/admin/reports/sales',
                        'controller' => 'Admin\Reports\SalesController@index',
                        'name' => 'admin.reports.sales.index',
                        'middleware' => 'reports.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'export',
                        'title' => 'Export Laporan Penjualan',
                        'method' => 'post',
                        'url' => '/admin/reports/sales/export',
                        'controller' => 'Admin\Reports\SalesController@export',
                        'name' => 'admin.reports.sales.export',
                        'middleware' => 'reports.access',
                        'user_types' => ['admin', 'produsen'],
                    ],

                    // Inventory Reports
                    [
                        'type' => 'index',
                        'title' => 'Laporan Inventori',
                        'method' => 'get',
                        'url' => '/admin/reports/inventory',
                        'controller' => 'Admin\Reports\InventoryController@index',
                        'name' => 'admin.reports.inventory.index',
                        'middleware' => 'reports.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'export',
                        'title' => 'Export Laporan Inventori',
                        'method' => 'post',
                        'url' => '/admin/reports/inventory/export',
                        'controller' => 'Admin\Reports\InventoryController@export',
                        'name' => 'admin.reports.inventory.export',
                        'middleware' => 'reports.access',
                        'user_types' => ['admin', 'produsen'],
                    ],

                    // Performance Reports
                    [
                        'type' => 'index',
                        'title' => 'Laporan Performa',
                        'method' => 'get',
                        'url' => '/admin/reports/performance',
                        'controller' => 'Admin\Reports\PerformanceController@index',
                        'name' => 'admin.reports.performance.index',
                        'middleware' => 'reports.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'insights',
                        'title' => 'AI Performance Insights',
                        'method' => 'post',
                        'url' => '/admin/reports/performance/insights',
                        'controller' => 'Admin\Reports\PerformanceController@generateInsights',
                        'name' => 'admin.reports.performance.insights',
                        'middleware' => 'reports.access',
                        'user_types' => ['admin', 'produsen'],
                    ],

                    // Financial Reports
                    [
                        'type' => 'index',
                        'title' => 'Laporan Keuangan',
                        'method' => 'get',
                        'url' => '/admin/reports/financial',
                        'controller' => 'Admin\Reports\FinancialController@index',
                        'name' => 'admin.reports.financial.index',
                        'middleware' => 'reports.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'profit_loss',
                        'title' => 'Laporan Laba Rugi',
                        'method' => 'get',
                        'url' => '/admin/reports/financial/profit-loss',
                        'controller' => 'Admin\Reports\FinancialController@profitLoss',
                        'name' => 'admin.reports.financial.profit-loss',
                        'middleware' => 'reports.access',
                        'user_types' => ['admin', 'produsen'],
                    ],

                    // Analytics Reports
                    [
                        'type' => 'index',
                        'title' => 'Laporan Analytics',
                        'method' => 'get',
                        'url' => '/admin/reports/analytics',
                        'controller' => 'Admin\Reports\AnalyticsController@index',
                        'name' => 'admin.reports.analytics.index',
                        'middleware' => 'reports.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'customer_behavior',
                        'title' => 'Analisis Perilaku Customer',
                        'method' => 'get',
                        'url' => '/admin/reports/analytics/customer-behavior',
                        'controller' => 'Admin\Reports\AnalyticsController@customerBehavior',
                        'name' => 'admin.reports.analytics.customer-behavior',
                        'middleware' => 'reports.access',
                        'user_types' => ['admin', 'produsen'],
                    ],

                    // Users Reports - ADMIN ONLY
                    [
                        'type' => 'index',
                        'title' => 'Laporan Pengguna',
                        'method' => 'get',
                        'url' => '/admin/reports/users',
                        'controller' => 'Admin\Reports\UsersController@index',
                        'name' => 'admin.reports.users.index',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'registration_stats',
                        'title' => 'Statistik Registrasi',
                        'method' => 'get',
                        'url' => '/admin/reports/users/registration-stats',
                        'controller' => 'Admin\Reports\UsersController@registrationStats',
                        'name' => 'admin.reports.users.registration-stats',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'activity_report',
                        'title' => 'Laporan Aktivitas User',
                        'method' => 'get',
                        'url' => '/admin/reports/users/activity',
                        'controller' => 'Admin\Reports\UsersController@activityReport',
                        'name' => 'admin.reports.users.activity',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],

                    // System Reports - ADMIN ONLY
                    [
                        'type' => 'index',
                        'title' => 'Laporan Sistem',
                        'method' => 'get',
                        'url' => '/admin/reports/system',
                        'controller' => 'Admin\Reports\SystemController@index',
                        'name' => 'admin.reports.system.index',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'performance_monitoring',
                        'title' => 'Monitoring Performa Sistem',
                        'method' => 'get',
                        'url' => '/admin/reports/system/performance',
                        'controller' => 'Admin\Reports\SystemController@performanceMonitoring',
                        'name' => 'admin.reports.system.performance',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],
                    [
                        'type' => 'error_logs',
                        'title' => 'Log Error Sistem',
                        'method' => 'get',
                        'url' => '/admin/reports/system/error-logs',
                        'controller' => 'Admin\Reports\SystemController@errorLogs',
                        'name' => 'admin.reports.system.error-logs',
                        'middleware' => 'user.type:admin',
                        'user_types' => ['admin'],
                    ],

                    // General Report Actions
                    [
                        'type' => 'dashboard_export',
                        'title' => 'Export Dashboard PDF',
                        'method' => 'post',
                        'url' => '/admin/reports/dashboard/export',
                        'controller' => 'Admin\Reports\DashboardController@exportPDF',
                        'name' => 'admin.reports.dashboard.export',
                        'middleware' => 'reports.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                    [
                        'type' => 'bulk_export',
                        'title' => 'Bulk Export Reports',
                        'method' => 'post',
                        'url' => '/admin/reports/bulk-export',
                        'controller' => 'Admin\Reports\BulkController@export',
                        'name' => 'admin.reports.bulk.export',
                        'middleware' => 'reports.access',
                        'user_types' => ['admin', 'produsen'],
                    ],
                ]
            ],
        ];

        if (!empty($index)) {
            return !empty($data[$index]) ? $data[$index] : null;
        }
        return $data;
    }

    function getIgnoreType($type = null)
    {
        $data = ['system', 'ajax', 'api'];
        if (!empty($type)) {
            if (!in_array($type, $data)) {
                return 1;
            }
            return 0;
        }
        return $data;
    }

    function getRoutesByUserType($userType)
    {
        $data = $this->getDataAuth();
        $filteredData = [];

        foreach ($data as $menu) {
            if (in_array($userType, $menu['user_types'])) {
                $filteredItems = [];
                foreach ($menu['item'] as $item) {
                    if (in_array($userType, $item['user_types'])) {
                        $filteredItems[] = $item;
                    }
                }
                if (!empty($filteredItems)) {
                    $menu['item'] = $filteredItems;
                    $filteredData[] = $menu;
                }
            }
        }

        return $filteredData;
    }

    function getAllRouteNames()
    {
        $data = $this->getDataAuth();
        $routes = [];

        foreach ($data as $menu) {
            foreach ($menu['item'] as $item) {
                if (!empty($item['name']) && $this->getIgnoreType($item['type'])) {
                    $routes[] = [
                        'name' => $item['name'],
                        'title' => $item['title'],
                        'menu' => $menu['title'],
                        'type' => $item['type'],
                        'user_types' => $item['user_types'],
                        'url' => $item['url'],
                        'method' => $item['method'],
                        'controller' => $item['controller'],
                        'middleware' => $item['middleware'],
                    ];
                }
            }
        }

        return $routes;
    }

    /**
     * Get user-specific menu for sidebar rendering
     */
    function getMenuForUser($userType)
    {
        $data = $this->getDataAuth();
        $menu = [];

        foreach ($data as $menuGroup) {
            if (in_array($userType, $menuGroup['user_types'])) {
                $filteredItems = [];
                foreach ($menuGroup['item'] as $item) {
                    if (in_array($userType, $item['user_types'])) {
                        $filteredItems[] = $item;
                    }
                }

                if (!empty($filteredItems)) {
                    $menuGroup['item'] = $filteredItems;
                    $menu[] = $menuGroup;
                }
            }
        }

        return $menu;
    }

    /**
     * Check if user has access to specific route
     */
    function hasAccess($userType, $routeName)
    {
        $routes = $this->getAllRouteNames();

        foreach ($routes as $route) {
            if ($route['name'] === $routeName) {
                return in_array($userType, $route['user_types']);
            }
        }

        return false;
    }

    /**
     * Get routes count by user type
     */
    function getRoutesCountByUserType($userType)
    {
        $routes = $this->getAllRouteNames();
        $count = 0;

        foreach ($routes as $route) {
            if (in_array($userType, $route['user_types'])) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get permission summary for user type
     */
    function getPermissionSummary($userType)
    {
        $data = $this->getDataAuth();
        $summary = [
            'total_menus' => 0,
            'accessible_menus' => 0,
            'total_routes' => 0,
            'accessible_routes' => 0,
            'menu_details' => []
        ];

        foreach ($data as $menu) {
            $summary['total_menus']++;

            $menuAccessible = in_array($userType, $menu['user_types']);
            if ($menuAccessible) {
                $summary['accessible_menus']++;
            }

            $accessibleItems = 0;
            foreach ($menu['item'] as $item) {
                $summary['total_routes']++;
                if (in_array($userType, $item['user_types'])) {
                    $summary['accessible_routes']++;
                    $accessibleItems++;
                }
            }

            $summary['menu_details'][] = [
                'title' => $menu['title'],
                'icon' => $menu['icon'],
                'accessible' => $menuAccessible,
                'total_items' => count($menu['item']),
                'accessible_items' => $accessibleItems,
                'access_percentage' => count($menu['item']) > 0 ? round(($accessibleItems / count($menu['item'])) * 100) : 0
            ];
        }

        return $summary;
    }
}
