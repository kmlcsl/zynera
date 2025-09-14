@extends('layouts.app')

@section('title', 'Tracking Pesanan')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8">Status Pengiriman</h1>

        @if ($activeOrders->count() > 0)
            <div class="space-y-6">
                @foreach ($activeOrders as $order)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold">{{ $order->order_number }}</h3>
                                <p class="text-gray-600">{{ $order->created_at->format('d M Y') }}</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                {{ $order->status_label }}
                            </span>
                        </div>

                        <!-- Progress Steps -->
                        <div class="flex items-center mb-6 overflow-x-auto">
                            <!-- Step 1: Dibayar -->
                            <div class="flex items-center flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <span class="ml-2 text-sm font-medium">Dibayar</span>
                            </div>

                            <div class="w-16 h-1 bg-gray-300 mx-2 flex-shrink-0"></div>

                            <!-- Step 2: Diproses -->
                            <div class="flex items-center flex-shrink-0">
                                <div
                                    class="w-8 h-8 rounded-full {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                                    <i class="fas fa-box text-white text-sm"></i>
                                </div>
                                <span class="ml-2 text-sm font-medium">Diproses</span>
                            </div>

                            <div class="w-16 h-1 bg-gray-300 mx-2 flex-shrink-0"></div>

                            <!-- Step 3: Dikirim -->
                            <div class="flex items-center flex-shrink-0">
                                <div
                                    class="w-8 h-8 rounded-full {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                                    <i class="fas fa-truck text-white text-sm"></i>
                                </div>
                                <span class="ml-2 text-sm font-medium">Dikirim</span>
                            </div>

                            <div class="w-16 h-1 bg-gray-300 mx-2 flex-shrink-0"></div>

                            <!-- Step 4: Terkirim -->
                            <div class="flex items-center flex-shrink-0">
                                <div
                                    class="w-8 h-8 rounded-full {{ $order->status == 'delivered' ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center">
                                    <i class="fas fa-home text-white text-sm"></i>
                                </div>
                                <span class="ml-2 text-sm font-medium">Terkirim</span>
                            </div>
                        </div>

                        <!-- Delivery Info -->
                        @if ($order->delivery && $order->delivery->courier)
                            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                                <h4 class="font-medium text-blue-900 mb-2">📦 Informasi Kurir</h4>
                                <p class="text-blue-800">Kurir: {{ $order->delivery->courier->name }}</p>
                                @if ($order->delivery->courier->phone)
                                    <p class="text-blue-800">Telepon: {{ $order->delivery->courier->phone }}</p>
                                @endif
                                @if ($order->delivery->notes)
                                    <p class="text-blue-700 text-sm mt-2">{{ $order->delivery->notes }}</p>
                                @endif
                            </div>
                        @else
                            <div class="bg-yellow-50 rounded-lg p-4 mb-4">
                                <p class="text-yellow-800">⏳ Menunggu penugasan kurir oleh produsen</p>
                            </div>
                        @endif

                        <div class="flex justify-end">
                            <a href="{{ route('orders.show', $order) }}"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Tidak Ada Pesanan Aktif</h2>
                <p class="text-gray-600 mb-6">Pesanan yang sedang diproses akan muncul di sini</p>
                <a href="{{ route('orders.index') }}"
                    class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Lihat Semua Pesanan
                </a>
            </div>
        @endif
    </div>
@endsection
