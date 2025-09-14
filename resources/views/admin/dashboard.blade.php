@extends('layouts.admin')

@section('content')
    <!-- Welcome Message -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Selamat datang, {{ $dashboardData['user_name'] }}!</h2>
                <p class="text-white/90">
                    @if ($dashboardData['user_type'] === 'admin')
                        Kelola seluruh aktivitas platform AgriConnect dengan mudah
                    @elseif($dashboardData['user_type'] === 'produsen')
                        Tingkatkan penjualan produk lokal Anda
                    @elseif($dashboardData['user_type'] === 'kurir')
                        Siap melayani pengiriman untuk pelanggan setia
                    @endif
                </p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/20 rounded-lg p-4">
                    <i class="fas fa-chart-line text-3xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @if ($dashboardData['user_type'] === 'admin')
            <!-- Added proper grid layout and fixed CSS classes for admin stats -->
            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Pengguna</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>12% dari bulan lalu
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Produk</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>8% dari bulan lalu
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-basket text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Pesanan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_orders'] ?? 0 }}</p>
                        <p class="text-xs text-blue-600 mt-1">
                            <i class="fas fa-shopping-cart mr-1"></i>Hari ini
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pendapatan</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue'] ?? 0) }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>Bulan ini
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-white text-xl"></i>
                    </div>
                </div>
            </div>
        @elseif($dashboardData['user_type'] === 'produsen')
            <!-- Added proper grid layout for producer stats -->
            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Produk Saya</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['my_products'] }}</p>
                        <p class="text-xs text-blue-600 mt-1">{{ $stats['active_products'] }} aktif</p>
                    </div>
                    <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-basket text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pesanan Masuk</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['incoming_orders'] ?? 0 }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>Hari ini
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-white text-xl"></i>
                    </div>
                </div>
            </div>
        @elseif($dashboardData['user_type'] === 'kurir')
            <!-- Added proper grid layout for courier stats -->
            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Delivery</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['assigned_deliveries'] }}</p>
                        <p class="text-xs text-green-600 mt-1">{{ $stats['completed_deliveries'] }} selesai</p>
                    </div>
                    <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-truck text-white text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pending Delivery</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_deliveries'] ?? 0 }}</p>
                        <p class="text-xs text-yellow-600 mt-1">
                            <i class="fas fa-clock mr-1"></i>Menunggu
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
            </div>
        @endif
    </div>

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
                @if(isset($recentItems) && count($recentItems) > 0)
                    <div class="space-y-4">
                        @foreach($recentItems as $item)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-shopping-bag text-gray-600"></i>
                                    </div>
                                    <div>
                                        @if ($dashboardData['user_type'] === 'kurir')
                                            <span class="font-medium text-green-600">{{ $item->order->order_number ?? 'N/A' }}</span>
                                        @else
                                            <span class="font-medium text-green-600">{{ $item->order_number }}</span>
                                        @endif
                                        <p class="text-sm text-gray-600">{{ $item->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">Rp {{ number_format($item->total_amount ?? 0) }}</p>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                        @if($item->status === 'completed') bg-green-100 text-green-800
                                        @elseif($item->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
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
                @if(isset($notifications) && count($notifications) > 0)
                    <div class="space-y-4">
                        @foreach($notifications as $notification)
                            <div class="flex items-start space-x-3 py-3 border-b border-gray-100 last:border-b-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-bell text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">{{ $notification->message }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
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
