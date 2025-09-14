@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <div class="relative">
                        @if ($user->avatar)
                            <img class="w-24 h-24 rounded-full object-cover border-4 border-gampong-secondary"
                                src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                        @else
                            <img class="w-24 h-24 rounded-full object-cover border-4 border-gampong-secondary"
                                src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=8FBC8F&color=fff&size=96"
                                alt="{{ $user->name }}">
                        @endif

                        <!-- Online status indicator -->
                        <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 border-2 border-white rounded-full">
                        </div>
                    </div>
                </div>

                <!-- User Info -->
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $user->name }}</h1>
                    <p class="text-gray-600 mb-2">{{ $user->email }}</p>

                    @if ($user->phone)
                        <p class="text-gray-600 mb-2">
                            <i class="fas fa-phone text-gampong-primary mr-2"></i>
                            {{ $user->phone }}
                        </p>
                    @endif

                    <div class="flex items-center justify-center md:justify-start space-x-4 text-sm text-gray-500">
                        <span class="flex items-center">
                            <i class="fas fa-calendar text-gampong-primary mr-1"></i>
                            Bergabung {{ $stats['member_since'] }}
                        </span>

                        <span class="flex items-center">
                            <i class="fas fa-shield-alt text-green-500 mr-1"></i>
                            {{ $user->is_verified ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('profile.edit') }}" class="btn-primary text-center">
                        <i class="fas fa-edit mr-2"></i>Edit Profil
                    </a>
                    <a href="{{ route('profile.orders') }}"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors text-center">
                        <i class="fas fa-shopping-bag mr-2"></i>Riwayat Pesanan
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-gampong-primary mb-2">{{ $stats['total_orders'] }}</div>
                <div class="text-sm text-gray-600">Total Pesanan</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">{{ $stats['completed_orders'] }}</div>
                <div class="text-sm text-gray-600">Pesanan Selesai</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">Rp
                    {{ number_format($stats['total_spent'], 0, ',', '.') }}</div>
                <div class="text-sm text-gray-600">Total Belanja</div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">{{ $stats['reviews_count'] }}</div>
                <div class="text-sm text-gray-600">Review Diberikan</div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-user text-gampong-primary mr-3"></i>
                    Informasi Pribadi
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                        <p class="text-gray-900">{{ $user->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                        <p class="text-gray-900">{{ $user->email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nomor WhatsApp</label>
                        <p class="text-gray-900">{{ $user->phone ?: 'Belum diisi' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Akun</label>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            {{ ucfirst(str_replace('_', ' ', $user->user_type)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-map-marker-alt text-gampong-primary mr-3"></i>
                    Informasi Alamat
                </h2>

                <div class="space-y-4">
                    @if ($user->village)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Desa/Gampong</label>
                            <p class="text-gray-900">{{ $user->village }}</p>
                        </div>
                    @endif

                    @if ($user->district)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Kecamatan</label>
                            <p class="text-gray-900">{{ $user->district }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Lengkap</label>
                        <p class="text-gray-900">
                            {{ $user->address ?: 'Belum diisi alamat lengkap' }}
                        </p>
                    </div>

                    @if (!$user->address || !$user->village || !$user->district)
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex">
                                <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-yellow-800">Lengkapi Alamat</h4>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        Lengkapi informasi alamat untuk memudahkan pengiriman pesanan.
                                    </p>
                                    <a href="{{ route('profile.edit') }}"
                                        class="text-sm text-yellow-800 hover:text-yellow-900 font-medium mt-2 inline-block">
                                        Lengkapi Sekarang →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-clock text-gampong-primary mr-3"></i>
                Aktivitas Terbaru
            </h2>

            @if ($user->orders()->latest()->take(3)->exists())
                <div class="space-y-3">
                    @foreach ($user->orders()->with('orderItems.product')->latest()->take(3)->get() as $order)
                        <div
                            class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gampong-primary rounded-full flex items-center justify-center">
                                    <i class="fas fa-shopping-bag text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Pesanan #{{ $order->order_number }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $order->orderItems->count() }} produk •
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $order->status === 'completed'
                                    ? 'bg-green-100 text-green-800'
                                    : ($order->status === 'pending'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('profile.orders') }}" class="text-gampong-primary hover:text-green-700 font-medium">
                        Lihat Semua Pesanan →
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-shopping-bag text-gray-300 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Pesanan</h3>
                    <p class="text-gray-600 mb-4">Mulai berbelanja produk pangan lokal dari Aceh Barat</p>
                    <a href="{{ route('products.index') }}" class="btn-primary">
                        <i class="fas fa-shopping-cart mr-2"></i>Mulai Berbelanja
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-gradient-to-r from-gampong-primary to-green-700 rounded-lg p-6 mt-8 text-white">
            <h2 class="text-xl font-semibold mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('products.index') }}"
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 text-center transition-colors">
                    <i class="fas fa-shopping-basket text-2xl mb-2"></i>
                    <p class="text-sm font-medium">Belanja Produk</p>
                </a>

                <a href="{{ route('orders.index') }}"
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 text-center transition-colors">
                    <i class="fas fa-list-alt text-2xl mb-2"></i>
                    <p class="text-sm font-medium">Pesanan Saya</p>
                </a>

                <a href="{{ route('cart.index') }}"
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 text-center transition-colors">
                    <i class="fas fa-shopping-cart text-2xl mb-2"></i>
                    <p class="text-sm font-medium">Keranjang</p>
                </a>
            </div>
        </div>
    </div>
@endsection
