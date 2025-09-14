@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8">Pesanan Saya</h1>

        @if ($orders->count() > 0)
            <div class="space-y-6">
                @foreach ($orders as $order)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- Order Header -->
                        <div class="bg-gray-50 px-6 py-4 border-b">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold">{{ $order->order_number }}</h3>
                                    <p class="text-gray-600 text-sm">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="px-3 py-1 bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800 rounded-full text-sm font-medium">
                                        {{ $order->status_label }}
                                    </span>
                                    @if ($order->canBeCancelled())
                                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin membatalkan pesanan?')"
                                                class="text-red-600 hover:text-red-700 text-sm font-medium">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Order Content -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Order Items -->
                                <div class="lg:col-span-2">
                                    <h4 class="font-medium mb-3">Produk ({{ $order->total_items }} item)</h4>
                                    <div class="space-y-3">
                                        @foreach ($order->orderItems->take(2) as $item)
                                            @php
                                                $images = [];
                                                if ($item->product->images) {
                                                    if (is_array($item->product->images)) {
                                                        $images = $item->product->images;
                                                    } elseif (is_string($item->product->images)) {
                                                        $decoded = json_decode($item->product->images, true);
                                                        $images = is_array($decoded) ? $decoded : [];
                                                    }
                                                }
                                                $imageCount = is_array($images) ? count($images) : 0;
                                            @endphp

                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                                    @if ($imageCount > 0)
                                                        <img src="{{ asset('storage/' . $images[0]) }}"
                                                            alt="{{ $item->product->name }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <div
                                                            class="w-full h-full flex items-center justify-center text-gray-400">
                                                            <i class="fas fa-image text-xs"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-medium text-sm">{{ $item->product->name }}</p>
                                                    <p class="text-gray-600 text-xs">{{ $item->quantity }} x Rp
                                                        {{ number_format($item->price, 0, ',', '.') }}</p>
                                                </div>
                                                <p class="font-semibold text-green-600 text-sm">Rp
                                                    {{ number_format($item->total, 0, ',', '.') }}</p>
                                            </div>
                                        @endforeach

                                        @if ($order->orderItems->count() > 2)
                                            <p class="text-gray-500 text-sm">
                                                +{{ $order->orderItems->count() - 2 }} produk lainnya
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Order Summary -->
                                <div class="lg:col-span-1">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h4 class="font-medium mb-3">Ringkasan</h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span>Subtotal</span>
                                                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Ongkir</span>
                                                <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between font-bold text-base border-t pt-2">
                                                <span>Total</span>
                                                <span class="text-green-600">Rp
                                                    {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                            </div>
                                        </div>

@if ($order->payment)
    <p class="text-xs text-gray-600">Pembayaran: {{ $order->payment->method_label }}</p>
    @if ($order->payment->status === 'paid')
        <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-xs mt-1">
            Sudah Dibayar
        </span>
    @elseif ($order->payment->method === 'cod')
        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs mt-1">
            Bayar Saat Terima
        </span>
    @else
        <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs mt-1">
            Menunggu Pembayaran
        </span>
    @endif
@endif
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                                <a href="{{ route('orders.show', $order) }}"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                    Lihat Detail
                                </a>

                                @if ($order->status === 'delivered')
                                    <button
                                        class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm font-medium">
                                        Beri Review
                                    </button>
                                @endif
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
            <!-- No Orders -->
            <div class="text-center py-16">
                <div class="mb-6">
                    <i class="fas fa-shopping-bag text-6xl text-gray-300"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Pesanan</h2>
                <p class="text-gray-600 mb-6">Yuk, mulai berbelanja dan buat pesanan pertama Anda!</p>
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
@endsection
