@extends('layouts.admin')

@section('content')
    <!-- Welcome Message -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg p-4 md:p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-lg md:text-2xl font-bold mb-2 truncate">Selamat datang, {{ $dashboardData['user_name'] }}!
                </h2>
                <p class="text-white/90 text-sm">
                    @if ($dashboardData['user_type'] === 'admin')
                        <span class="hidden sm:inline">Kelola seluruh aktivitas platform AgriConnect dengan mudah</span>
                        <span class="sm:hidden">Kelola platform AgriConnect</span>
                    @elseif($dashboardData['user_type'] === 'produsen')
                        <span class="hidden sm:inline">Tingkatkan penjualan produk lokal Anda</span>
                        <span class="sm:hidden">Tingkatkan penjualan Anda</span>
                    @elseif($dashboardData['user_type'] === 'kurir')
                        <span class="hidden sm:inline">Siap melayani pengiriman untuk pelanggan setia</span>
                        <span class="sm:hidden">Siap melayani pengiriman</span>
                    @endif
                </p>
            </div>
            <div class="hidden md:block ml-4">
                <div class="bg-white/20 rounded-lg p-4">
                    <i class="fas fa-chart-line text-3xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-8">
        @if ($dashboardData['user_type'] === 'admin')
            <!-- Total Users Card -->
            <div class="bg-white rounded-lg p-3 md:p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs md:text-sm text-gray-600 truncate">Total Pengguna</p>
                        <p class="text-lg md:text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i><span class="hidden sm:inline">12% dari bulan
                                lalu</span><span class="sm:hidden">+12%</span>
                        </p>
                    </div>
                    <div class="w-8 h-8 md:w-12 md:h-12 bg-green-600 rounded-lg flex items-center justify-center ml-2">
                        <i class="fas fa-users text-white text-sm md:text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Products Card -->
            <div class="bg-white rounded-lg p-3 md:p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs md:text-sm text-gray-600 truncate">Total Produk</p>
                        <p class="text-lg md:text-2xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i><span class="hidden sm:inline">8% dari bulan
                                lalu</span><span class="sm:hidden">+8%</span>
                        </p>
                    </div>
                    <div class="w-8 h-8 md:w-12 md:h-12 bg-emerald-600 rounded-lg flex items-center justify-center ml-2">
                        <i class="fas fa-shopping-basket text-white text-sm md:text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Orders Card -->
            <div class="bg-white rounded-lg p-3 md:p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs md:text-sm text-gray-600 truncate">Total Pesanan</p>
                        <p class="text-lg md:text-2xl font-bold text-gray-900">{{ $stats['total_orders'] ?? 0 }}</p>
                        <p class="text-xs text-blue-600 mt-1">
                            <i class="fas fa-shopping-cart mr-1"></i><span class="hidden sm:inline">Hari ini</span><span
                                class="sm:hidden">Today</span>
                        </p>
                    </div>
                    <div class="w-8 h-8 md:w-12 md:h-12 bg-blue-600 rounded-lg flex items-center justify-center ml-2">
                        <i class="fas fa-shopping-cart text-white text-sm md:text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Revenue Card -->
            <div class="bg-white rounded-lg p-3 md:p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs md:text-sm text-gray-600 truncate">Pendapatan</p>
                        <p class="text-lg md:text-2xl font-bold text-gray-900">Rp
                            {{ number_format($stats['total_revenue'] ?? 0) }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i><span class="hidden sm:inline">Bulan ini</span><span
                                class="sm:hidden">Monthly</span>
                        </p>
                    </div>
                    <div class="w-8 h-8 md:w-12 md:h-12 bg-yellow-600 rounded-lg flex items-center justify-center ml-2">
                        <i class="fas fa-money-bill-wave text-white text-sm md:text-xl"></i>
                    </div>
                </div>
            </div>
        @elseif($dashboardData['user_type'] === 'produsen')
            <!-- My Products Card -->
            <div class="bg-white rounded-lg p-3 md:p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs md:text-sm text-gray-600 truncate">Produk Saya</p>
                        <p class="text-lg md:text-2xl font-bold text-gray-900">{{ $stats['my_products'] }}</p>
                        <p class="text-xs text-blue-600 mt-1">{{ $stats['active_products'] }} aktif</p>
                    </div>
                    <div class="w-8 h-8 md:w-12 md:h-12 bg-green-600 rounded-lg flex items-center justify-center ml-2">
                        <i class="fas fa-shopping-basket text-white text-sm md:text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Incoming Orders Card -->
            <div class="bg-white rounded-lg p-3 md:p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs md:text-sm text-gray-600 truncate">Pesanan Masuk</p>
                        <p class="text-lg md:text-2xl font-bold text-gray-900">{{ $stats['incoming_orders'] ?? 0 }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i><span class="hidden sm:inline">Hari ini</span><span
                                class="sm:hidden">Today</span>
                        </p>
                    </div>
                    <div class="w-8 h-8 md:w-12 md:h-12 bg-blue-600 rounded-lg flex items-center justify-center ml-2">
                        <i class="fas fa-shopping-cart text-white text-sm md:text-xl"></i>
                    </div>
                </div>
            </div>
        @elseif($dashboardData['user_type'] === 'kurir')
            <!-- Total Delivery Card -->
            <div class="bg-white rounded-lg p-3 md:p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs md:text-sm text-gray-600 truncate">Total Delivery</p>
                        <p class="text-lg md:text-2xl font-bold text-gray-900">{{ $stats['assigned_deliveries'] }}</p>
                        <p class="text-xs text-green-600 mt-1">{{ $stats['completed_deliveries'] }} selesai</p>
                    </div>
                    <div class="w-8 h-8 md:w-12 md:h-12 bg-green-600 rounded-lg flex items-center justify-center ml-2">
                        <i class="fas fa-truck text-white text-sm md:text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Delivery Card -->
            <div class="bg-white rounded-lg p-3 md:p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs md:text-sm text-gray-600 truncate">Pending Delivery</p>
                        <p class="text-lg md:text-2xl font-bold text-gray-900">{{ $stats['pending_deliveries'] ?? 0 }}</p>
                        <p class="text-xs text-yellow-600 mt-1">
                            <i class="fas fa-clock mr-1"></i><span class="hidden sm:inline">Menunggu</span><span
                                class="sm:hidden">Pending</span>
                        </p>
                    </div>
                    <div class="w-8 h-8 md:w-12 md:h-12 bg-yellow-600 rounded-lg flex items-center justify-center ml-2">
                        <i class="fas fa-clock text-white text-sm md:text-xl"></i>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- User Role Statistics - Only for Admin -->
    @if ($dashboardData['user_type'] === 'admin')
        <div class="mb-8">
            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Statistik Pengguna Berdasarkan Role</h3>
                        <p class="text-sm text-gray-600">Overview total pengguna platform berdasarkan tipe user</p>
                    </div>
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg p-3">
                        <i class="fas fa-users-cog text-white text-xl"></i>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                    <!-- Customers Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 md:p-5 border border-blue-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs md:text-sm font-medium text-blue-700 truncate">Konsumen</p>
                                <p class="text-lg md:text-2xl font-bold text-blue-900">
                                    {{ $stats['total_customers'] ?? 0 }}</p>
                                <p class="text-xs text-blue-600 mt-1">
                                    <i class="fas fa-shopping-bag mr-1"></i><span
                                        class="hidden sm:inline">Pembeli</span><span class="sm:hidden">Customer</span>
                                </p>
                            </div>
                            <div
                                class="w-8 h-8 md:w-12 md:h-12 bg-blue-600 rounded-lg flex items-center justify-center shadow-lg ml-2">
                                <i class="fas fa-shopping-bag text-white text-sm md:text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Producers Card -->
                    <div
                        class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-3 md:p-5 border border-green-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs md:text-sm font-medium text-green-700 truncate">Produsen</p>
                                <p class="text-lg md:text-2xl font-bold text-green-900">
                                    {{ $stats['total_producers'] ?? 0 }}</p>
                                <p class="text-xs text-green-600 mt-1">
                                    <i class="fas fa-seedling mr-1"></i><span class="hidden sm:inline">Petani</span><span
                                        class="sm:hidden">Producer</span>
                                </p>
                            </div>
                            <div
                                class="w-8 h-8 md:w-12 md:h-12 bg-green-600 rounded-lg flex items-center justify-center shadow-lg ml-2">
                                <i class="fas fa-seedling text-white text-sm md:text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Couriers Card -->
                    <div
                        class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-3 md:p-5 border border-orange-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs md:text-sm font-medium text-orange-700 truncate">Kurir</p>
                                <p class="text-lg md:text-2xl font-bold text-orange-900">
                                    {{ $stats['total_couriers'] ?? 0 }}</p>
                                <p class="text-xs text-orange-600 mt-1">
                                    <i class="fas fa-truck mr-1"></i><span class="hidden sm:inline">Delivery</span><span
                                        class="sm:hidden">Courier</span>
                                </p>
                            </div>
                            <div
                                class="w-8 h-8 md:w-12 md:h-12 bg-orange-600 rounded-lg flex items-center justify-center shadow-lg ml-2">
                                <i class="fas fa-truck text-white text-sm md:text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Admins Card -->
                    <div
                        class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-3 md:p-5 border border-purple-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs md:text-sm font-medium text-purple-700 truncate">Admin</p>
                                <p class="text-lg md:text-2xl font-bold text-purple-900">{{ $stats['total_admins'] ?? 0 }}
                                </p>
                                <p class="text-xs text-purple-600 mt-1">
                                    <i class="fas fa-crown mr-1"></i><span class="hidden sm:inline">Pengelola</span><span
                                        class="sm:hidden">Manager</span>
                                </p>
                            </div>
                            <div
                                class="w-8 h-8 md:w-12 md:h-12 bg-purple-600 rounded-lg flex items-center justify-center shadow-lg ml-2">
                                <i class="fas fa-crown text-white text-sm md:text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Bar -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Total User Platform:</span>
                        <span class="font-semibold text-gray-900">{{ $stats['total_users'] }} Pengguna</span>
                    </div>
                    <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                        @php
                            $totalUsers = $stats['total_users'] ?? 1; // Avoid division by zero
                            $customerPercent = (($stats['total_customers'] ?? 0) / $totalUsers) * 100;
                            $producerPercent = (($stats['total_producers'] ?? 0) / $totalUsers) * 100;
                            $courierPercent = (($stats['total_couriers'] ?? 0) / $totalUsers) * 100;
                            $adminPercent = (($stats['total_admins'] ?? 0) / $totalUsers) * 100;
                        @endphp
                        <div class="flex h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-600" style="width: {{ $customerPercent }}%"></div>
                            <div class="bg-green-600" style="width: {{ $producerPercent }}%"></div>
                            <div class="bg-orange-600" style="width: {{ $courierPercent }}%"></div>
                            <div class="bg-purple-600" style="width: {{ $adminPercent }}%"></div>
                        </div>
                    </div>
                    <div
                        class="grid grid-cols-2 md:flex md:items-center md:justify-between gap-2 mt-2 text-xs text-gray-500">
                        <span class="flex items-center"><span
                                class="inline-block w-2 h-2 bg-blue-600 rounded-full mr-1"></span>Konsumen
                            ({{ number_format($customerPercent, 1) }}%)</span>
                        <span class="flex items-center"><span
                                class="inline-block w-2 h-2 bg-green-600 rounded-full mr-1"></span>Produsen
                            ({{ number_format($producerPercent, 1) }}%)</span>
                        <span class="flex items-center"><span
                                class="inline-block w-2 h-2 bg-orange-600 rounded-full mr-1"></span>Kurir
                            ({{ number_format($courierPercent, 1) }}%)</span>
                        <span class="flex items-center"><span
                                class="inline-block w-2 h-2 bg-purple-600 rounded-full mr-1"></span>Admin
                            ({{ number_format($adminPercent, 1) }}%)</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders/Activities -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        @if ($dashboardData['user_type'] === 'admin')
                            Pesanan Terbaru
                        @elseif($dashboardData['user_type'] === 'produsen')
                            Aktivitas Terbaru
                        @elseif($dashboardData['user_type'] === 'kurir')
                            Delivery Terbaru
                        @endif
                    </h3>
                    @if ($dashboardData['user_type'] === 'admin')
                        <a href="{{ route('admin.orders.index') }}" class="text-green-600 hover:underline text-sm">
                            Lihat Semua
                        </a>
                    @endif
                </div>
            </div>
            <div class="p-6">
                @if (isset($recentItems) && count($recentItems) > 0)
                    <div class="space-y-4">
                        @foreach ($recentItems as $item)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-shopping-bag text-gray-600"></i>
                                    </div>
                                    <div>
                                        @if ($dashboardData['user_type'] === 'kurir')
                                            <span
                                                class="font-medium text-green-600">{{ $item->order->order_number ?? 'N/A' }}</span>
                                        @else
                                            <span class="font-medium text-green-600">{{ $item->order_number }}</span>
                                        @endif
                                        <p class="text-sm text-gray-600">{{ $item->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">Rp
                                        {{ number_format($item->total_amount ?? 0) }}</p>
                                    @php
                                        $statusClasses = [
                                            'completed' => 'bg-green-50 text-green-700 border border-green-200',
                                            'pending' => 'bg-yellow-50 text-yellow-700 border border-yellow-200',
                                            'processing' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                            'cancelled' => 'bg-red-50 text-red-700 border border-red-200',
                                            'default' => 'bg-gray-50 text-gray-700 border border-gray-200',
                                        ];
                                        $statusClass = $statusClasses[$item->status] ?? $statusClasses['default'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada aktivitas terbaru</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Notifications -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Notifikasi</h3>
            </div>
            <div class="p-6">
                @if (isset($notifications) && count($notifications) > 0)
                    <div class="space-y-4">
                        @foreach ($notifications as $notification)
                            <div class="flex items-start space-x-3 py-3 border-b border-gray-100 last:border-b-0">
                                <div
                                    class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-bell text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">{{ $notification->message }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="pt-4 border-t border-gray-100">
                        <a href="#" class="text-sm text-green-600 hover:underline">Lihat semua notifikasi</a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-bell-slash text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Tidak ada notifikasi baru</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
