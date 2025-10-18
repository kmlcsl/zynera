@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('profile.show') }}" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-user mr-2"></i>Profil
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-3"></i>
                            <span class="text-gray-900 font-medium">Riwayat Pesanan</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 mt-4">Riwayat Pesanan</h1>
            <p class="text-gray-600 mt-2">Kelola dan lacak semua pesanan Anda</p>
        </div>

        @if ($orders->count() > 0)
            <!-- Filter Options -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('profile.orders') }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ !request('status') ? 'bg-gampong-primary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Semua
                        </a>
                        <a href="{{ route('profile.orders', ['status' => 'pending']) }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Menunggu
                        </a>
                        <a href="{{ route('profile.orders', ['status' => 'paid']) }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request('status') === 'paid' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Diproses
                        </a>
                        <a href="{{ route('profile.orders', ['status' => 'shipped']) }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request('status') === 'shipped' ? 'bg-purple-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Dikirim
                        </a>
                        <a href="{{ route('profile.orders', ['status' => 'completed']) }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request('status') === 'completed' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Selesai
                        </a>
                    </div>

                    <div class="flex items-center space-x-2">
                        <i class="fas fa-shopping-bag text-gray-400"></i>
                        <span class="text-sm text-gray-600">{{ $orders->total() }} pesanan ditemukan</span>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="space-y-6">
                @foreach ($orders as $order)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- Order Header -->
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <div
                                class="flex flex-col md:flex-row md:items-center md:justify-between space-y-2 md:space-y-0">
                                <div class="flex items-center space-x-4">
                                    <h3 class="font-semibold text-gray-900">
                                        Pesanan #{{ $order->order_number }}
                                    </h3>
                                    @php
                                        $actualStatus = $order->delivery && $order->delivery->status === 'delivered' ? 'completed' : $order->status;
                                        $statusLabel = $order->status_label;
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $actualStatus === 'completed' || ($order->delivery && $order->delivery->status === 'delivered')
                                        ? 'bg-green-100 text-green-800'
                                        : ($order->status === 'pending'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : ($order->status === 'paid'
                                                ? 'bg-blue-100 text-blue-800'
                                                : ($order->status === 'shipped' || ($order->delivery && in_array($order->delivery->status, ['in_transit', 'picked_up']))
                                                    ? 'bg-purple-100 text-purple-800'
                                                    : 'bg-gray-100 text-gray-800'))) }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>

                        <!-- Order Content -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Order Items -->
                                <div class="lg:col-span-2">
                                    <h4 class="font-medium text-gray-900 mb-4">Produk yang Dipesan</h4>
                                    <div class="space-y-3">
                                        @foreach ($order->orderItems as $item)
                                            <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                                                @php
                                                    $images = $item->product->images ?? [];
                                                    if (is_string($images)) {
                                                        $images = json_decode($images, true) ?: [];
                                                    }
                                                @endphp

                                                <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                                    @if (!empty($images) && count($images) > 0)
                                                        <img src="{{ asset('storage/' . $images[0]) }}"
                                                            alt="{{ $item->product->name }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center">
                                                            <i class="fas fa-image text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    <h5 class="font-medium text-gray-900 truncate">
                                                        {{ $item->product->name }}</h5>
                                                    <p class="text-sm text-gray-600">
                                                        {{ $item->quantity }} {{ $item->product->unit }} ×
                                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                                    </p>
                                                </div>

                                                <div class="text-right">
                                                    <p class="font-medium text-gray-900">
                                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Order Summary -->
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-4">Ringkasan Pesanan</h4>
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Subtotal</span>
                                            <span class="text-gray-900">Rp
                                                {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                        </div>

                                        @if ($order->shipping_cost > 0)
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">Ongkir</span>
                                                <span class="text-gray-900">Rp
                                                    {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                            </div>
                                        @endif

                                        @if ($order->tax_amount > 0)
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">Pajak</span>
                                                <span class="text-gray-900">Rp
                                                    {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                                            </div>
                                        @endif

                                        <div class="border-t pt-3">
                                            <div class="flex justify-between font-semibold">
                                                <span class="text-gray-900">Total</span>
                                                <span class="text-gampong-primary">Rp
                                                    {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Info -->
                                    @if ($order->payment)
                                        <div class="mt-4">
                                            <h5 class="font-medium text-gray-900 mb-2">Pembayaran</h5>
                                            <div class="text-sm space-y-1">
                                                <p class="text-gray-600">
                                                    Metode: <span
                                                        class="text-gray-900">{{ $order->payment->method_label }}</span>
                                                </p>
                                                <p class="text-gray-600">
                                                    Status:
                                                    <span
                                                        class="font-medium {{ $order->payment->status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                                                        {{ $order->payment->status_label }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="mt-6 space-y-2">
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="w-full btn-primary text-center">
                                            <i class="fas fa-eye mr-2"></i>Detail Pesanan
                                        </a>

                                        @if ($order->status === 'pending')
                                            <button onclick="cancelOrder({{ $order->id }})"
                                                class="w-full bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition-colors">
                                                <i class="fas fa-times mr-2"></i>Batalkan Pesanan
                                            </button>
                                        @endif

                                        @php
                                            $isCompleted = $order->status === 'completed' || ($order->delivery && $order->delivery->status === 'delivered');
                                        @endphp
                                        @if ($isCompleted && !$order->review_submitted)
                                            <a href="{{ route('orders.show', $order) }}#review"
                                                class="w-full bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600 transition-colors text-center">
                                                <i class="fas fa-star mr-2"></i>Beri Review
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-shopping-bag text-gray-300 text-6xl mb-6"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Belum Ada Pesanan</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Anda belum memiliki riwayat pesanan. Mulai berbelanja produk pangan lokal segar dari Aceh Barat
                    sekarang!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products.index') }}" class="btn-primary">
                        <i class="fas fa-shopping-cart mr-2"></i>Mulai Berbelanja
                    </a>
                    <a href="{{ route('categories.index') }}"
                        class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-tags mr-2"></i>Lihat Kategori
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script>
        function cancelOrder(orderId) {
            if (confirm('Yakin ingin membatalkan pesanan ini?')) {
                fetch(`/orders/${orderId}/cancel`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal membatalkan pesanan. Silakan coba lagi.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    });
            }
        }
    </script>
@endsection
