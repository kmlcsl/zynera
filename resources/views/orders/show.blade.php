@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50 py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Enhanced Hero Header --}}
        <div
            class="relative rounded-3xl p-6 sm:p-8 mb-6 sm:mb-8 text-white overflow-hidden bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-700 via-purple-700 to-blue-700 shadow-xl">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute -right-32 -top-32 w-72 h-72 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute -left-24 -bottom-24 w-56 h-56 bg-white/5 rounded-full blur-3xl"></div>

            @php
            $delivery = $order->delivery;
            $status = $delivery ? $delivery->status : null;

            $statusMap = [
            'assigned' => ['label' => 'Ditugaskan ke Kurir', 'icon' => '👤', 'bg' => 'bg-blue-500/90'],
            'picked_up' => ['label' => 'Barang Diambil Kurir', 'icon' => '📦', 'bg' => 'bg-yellow-500/90'],
            'in_transit' => ['label' => 'Dalam Pengiriman', 'icon' => '🚚', 'bg' => 'bg-purple-500/90'],
            'delivered' => ['label' => 'Barang Terkirim', 'icon' => '✅', 'bg' => 'bg-green-500/90'],
            'failed' => ['label' => 'Pengiriman Gagal', 'icon' => '❌', 'bg' => 'bg-red-500/90'],
            ];

            $meta = isset($statusMap[$status]) ? $statusMap[$status] : ['label' => $order->status_label, 'icon' => '📋',
            'bg' => 'bg-gray-500/90'];

            $stages = [
            ['key' => 'assigned', 'label' => 'Ditugaskan'],
            ['key' => 'picked_up', 'label' => 'Diambil'],
            ['key' => 'in_transit', 'label' => 'Dikirim'],
            ['key' => 'delivered', 'label' => 'Selesai'],
            ];

            $keys = array_column($stages, 'key');
            $currentIdx = null;
            $completedStages = [];

            if ($status && is_string($status) && in_array($status, $keys, true)) {
            $currentIdx = array_search($status, $keys, true);

            // Jika delivered, semua stage sebelumnya sudah completed
            if ($status === 'delivered') {
            $completedStages = array_slice($keys, 0, count($keys)); // Semua stage completed
            $currentIdx = count($keys) - 1; // Index terakhir
            } else {
            // Stage sebelumnya yang sudah completed
            $completedStages = array_slice($keys, 0, $currentIdx);
            }
            }
            @endphp

            <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4 backdrop-blur-sm border border-white/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-1">Detail Pesanan</h1>
                            <p class="text-white/90 text-lg font-medium">{{ $order->order_number }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-white/80 text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full lg:w-auto">
                    <div
                        class="px-4 sm:px-6 py-3 {{ $meta['bg'] }} rounded-2xl backdrop-blur-sm border border-white/20 flex items-center shadow-lg min-w-0 flex-1 sm:flex-none">
                        <span class="mr-2 text-lg">{{ $meta['icon'] }}</span>
                        <span class="font-semibold truncate">{{ $meta['label'] }}</span>
                    </div>

                    @if ($order->canBeCancelled())
                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')"
                            class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-red-500/90 hover:bg-red-600/90 rounded-2xl backdrop-blur-sm border border-white/20 transition-all transform hover:scale-105 flex items-center justify-center font-semibold shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batalkan
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Enhanced Mini Stepper --}}
            @if($order->delivery)
            <div class="relative z-10 mt-8">
                <div class="flex items-center justify-between">
                    @foreach($stages as $i => $stage)
                    <div class="flex items-center {{ $loop->last ? '' : 'flex-1' }}">
                        @php
                        $isCompleted = in_array($stage['key'], $completedStages);
                        $isCurrent = ($currentIdx !== null && $i === $currentIdx && $status !== 'delivered');
                        $isDelivered = ($status === 'delivered' && $stage['key'] === 'delivered');
                        @endphp

                        <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-lg border-2 transition-all duration-300
                @if($isCompleted || $isDelivered) bg-emerald-500 border-emerald-400 text-white
                @elseif($isCurrent) bg-white border-white text-indigo-700 shadow-xl
                @else bg-white/20 border-white/40 text-white/70 @endif">

                            @if($isCompleted || $isDelivered)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            @elseif($isCurrent)
                            <div class="w-3 h-3 bg-indigo-700 rounded-full animate-pulse"></div>
                            @else
                            <div class="w-2 h-2 bg-current rounded-full"></div>
                            @endif
                        </div>

                        @if(!$loop->last)
                        <div class="flex-1 h-1 mx-3 rounded-full transition-all duration-300
                @if($isCompleted || ($status === 'delivered' && $i < 3)) bg-emerald-400 @else bg-white/30 @endif">
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <div class="mt-3 grid grid-cols-4 text-xs sm:text-sm tracking-wide">
                    @foreach($stages as $i => $stage)
                    <div
                        class="text-center transition-all duration-300 {{ $currentIdx !== null && $i === $currentIdx ? 'font-bold text-white scale-105' : 'text-white/80' }}">
                        {{ $stage['label'] }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 lg:gap-8">

            {{-- Left Content --}}
            <div class="xl:col-span-3 space-y-6 lg:space-y-8">

                {{-- Order Items Section --}}
                <div
                    class="bg-white rounded-3xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6 sm:p-8 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center">
                            <div
                                class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-3 shadow-md">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            Produk Pesanan
                            <span
                                class="ml-3 px-3 py-1 bg-blue-100 text-blue-800 rounded-xl text-sm font-medium shadow-sm">
                                {{ $order->orderItems->count() }} item
                            </span>
                        </h2>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach ($order->orderItems as $item)
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

                        $canReview = $order->status === 'delivered';
                        $existingReview = null;
                        if (class_exists('App\Models\Review')) {
                        $existingReview = \App\Models\Review::where('user_id', auth()->id())
                        ->where('product_id', $item->product_id)
                        ->where('order_id', $order->id)
                        ->first();
                        }
                        @endphp

                        <div class="p-6 sm:p-8 hover:bg-gray-50 transition-all duration-200">
                            <div class="flex items-start gap-4 sm:gap-6">
                                {{-- Product Image --}}
                                <div
                                    class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-200 rounded-2xl overflow-hidden flex-shrink-0 shadow-md group">
                                    @if ($imageCount > 0)
                                    <img src="{{ asset('storage/' . $images[0]) }}" alt="{{ $item->product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    @endif
                                </div>

                                {{-- Product Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-bold text-lg sm:text-xl text-gray-900 mb-2 line-clamp-2">
                                                {{ $item->product->name }}
                                            </h3>
                                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-3">
                                                <span
                                                    class="px-3 py-1 bg-blue-100 text-blue-800 rounded-lg font-medium">
                                                    {{ $item->product->category->name }}
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                        </path>
                                                    </svg>
                                                    {{ $item->product->user->name }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="text-left sm:text-right flex-shrink-0">
                                            <div class="text-sm text-gray-600 mb-1">
                                                {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',',
                                                '.')
                                                }}
                                            </div>
                                            <div
                                                class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">
                                                Rp {{ number_format($item->total, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Review Section --}}
                                    @if ($existingReview)
                                    <div
                                        class="mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl">
                                        <div class="flex items-center gap-3 mb-3">
                                            <span class="text-sm font-semibold text-green-800 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Review Diberikan
                                            </span>
                                            <div class="flex items-center">
                                                @for ($i = 1; $i <= 5; $i++) <svg
                                                    class="w-4 h-4 {{ $i <= $existingReview->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                    </path>
                                                    </svg>
                                                    @endfor
                                            </div>
                                        </div>
                                        @if ($existingReview->comment)
                                        <p class="text-sm text-gray-700 italic">"{{ $existingReview->comment }}"</p>
                                        @endif
                                    </div>
                                    @elseif($canReview)
                                    <div class="mt-6">
                                        <button
                                            onclick="openReviewModal({{ $item->product_id }}, '{{ $item->product->name }}')"
                                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-2xl hover:from-yellow-500 hover:to-orange-600 transition-all transform hover:scale-105 font-semibold shadow-lg">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            Berikan Review
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Shipping Information --}}
                <div
                    class="bg-white rounded-3xl shadow-lg p-6 sm:p-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 shadow-md">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        Informasi Pengiriman
                    </h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div
                            class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100 hover:shadow-md transition-shadow">
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2m-7 0h2m-2 0H6m2 0h2M9 7h6m-6 4h6m-6 4h6">
                                    </path>
                                </svg>
                                Alamat Pengiriman
                            </h3>
                            <p class="text-gray-700 leading-relaxed">{{ $order->shipping_address }}</p>
                        </div>

                        <div
                            class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-100 hover:shadow-md transition-shadow">
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                                Penerima
                            </h3>
                            <div class="space-y-1">
                                <p class="text-gray-700 font-medium">{{ $order->recipient_name }}</p>
                                <p class="text-gray-600">{{ $order->recipient_phone }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($order->notes)
                    <div
                        class="mt-6 p-6 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl border border-yellow-100 hover:shadow-md transition-shadow">
                        <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                </path>
                            </svg>
                            Catatan Pesanan
                        </h3>
                        <p class="text-gray-700 italic">"{{ $order->notes }}"</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right Sidebar --}}
            <div class="xl:col-span-1 space-y-6">

                {{-- Order Summary --}}
                <div
                    class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100 sticky top-8 hover:shadow-xl transition-shadow duration-300">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <div
                            class="w-6 h-6 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3 shadow-md">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        Ringkasan
                    </h2>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600 text-sm">Subtotal ({{ $order->total_items }} item)</span>
                            <span class="font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($order->service_fee > 0)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600 text-sm">Biaya Layanan (2%)</span>
                            <span class="font-medium">Rp {{ number_format($order->service_fee, 0, ',', '.')
                                }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600 text-sm">Biaya Pengiriman</span>
                            <span class="font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.')
                                }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center py-3 bg-gradient-to-r from-green-50 to-emerald-50 -mx-2 px-2 rounded-xl border border-green-100">
                            <span class="font-bold text-lg text-gray-900">Total</span>
                            <span
                                class="font-bold text-xl bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Payment Information --}}
                @if ($order->payment)
                <div
                    class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <div
                            class="w-6 h-6 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center mr-3 shadow-md">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </div>
                        Pembayaran
                    </h2>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">Metode:</span>
                            <span class="font-medium">{{ $order->payment->method_label }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">Status:</span>
                            <div>
                                @if ($order->payment->status === 'paid')
                                <span
                                    class="px-3 py-1 bg-green-100 text-green-800 rounded-xl text-sm font-medium flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Sudah Dibayar
                                </span>
                                @elseif ($order->payment->method === 'cod')
                                <span
                                    class="px-3 py-1 bg-blue-100 text-blue-800 rounded-xl text-sm font-medium flex items-center">
                                    <span class="mr-1">💰</span>
                                    Bayar Saat Terima
                                </span>
                                @else
                                <span
                                    class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-xl text-sm font-medium flex items-center">
                                    <svg class="w-3 h-3 mr-1 animate-spin" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Menunggu
                                </span>
                                @endif
                            </div>
                        </div>

                        @if ($order->payment->reference_number)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">Ref:</span>
                            <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{
                                $order->payment->reference_number }}</span>
                        </div>
                        @endif

                        @if ($order->payment->paid_at)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">Dibayar:</span>
                            <span class="text-sm">{{ $order->payment->paid_at->format('d M Y H:i') }}</span>
                        </div>
                        @endif
                    </div>

                    @if ($order->payment->proof_image)
                    <div class="mt-6">
                        <span class="text-gray-600 text-sm font-medium">Bukti Pembayaran:</span>
                        <img src="{{ asset('storage/' . $order->payment->proof_image) }}" alt="Bukti Pembayaran"
                            class="mt-3 w-full rounded-2xl border border-gray-200 cursor-pointer hover:shadow-lg transition-all duration-300"
                            onclick="showImageModal(this.src)">
                    </div>
                    @endif

                    {{-- Upload Proof for Transfer --}}
                    @if ($order->payment->method === 'transfer' && $order->payment->status === 'pending')
                    <div
                        class="mt-6 p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100">
                        @if (!$order->payment->proof_image)
                        <h4 class="font-semibold text-blue-900 mb-4">Upload Bukti Transfer</h4>
                        <div class="bg-white p-4 rounded-xl border mb-4 text-sm">
                            <div class="font-semibold text-gray-900 mb-2">💳 Bank BCA</div>
                            <div class="space-y-1 text-gray-700">
                                <p>No. Rekening: <span class="font-mono bg-gray-100 px-1 rounded">1234567890</span>
                                </p>
                                <p>Atas Nama: <span class="font-medium">Green Fresh Store</span></p>
                                <p class="text-lg font-bold text-green-600 mt-2">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <form action="{{ route('payments.upload-proof', $order->payment) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="proof_image" accept="image/*" required
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 mb-3">
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 rounded-2xl hover:from-blue-600 hover:to-indigo-700 transition-all font-semibold shadow-lg">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                Upload Bukti
                            </button>
                        </form>
                        @else
                        <h4 class="font-semibold text-blue-900 mb-3">✅ Bukti Terupload</h4>
                        <p class="text-sm text-blue-700 mb-4">Menunggu konfirmasi admin...</p>
                        <img src="{{ asset('storage/' . $order->payment->proof_image) }}" alt="Bukti Transfer"
                            class="w-full rounded-xl border cursor-pointer" onclick="showImageModal(this.src)">
                        @endif
                    </div>
                    @endif

                    {{-- QRIS Payment Section --}}
                    @if ($order->payment->method === 'qris' && $order->payment->status === 'pending')
                    <div
                        class="mt-6 p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-100">
                        <h4 class="font-semibold text-purple-900 mb-4">📱 Pembayaran QRIS</h4>
                        <div class="bg-white p-4 rounded-xl border text-center mb-4">
                            <div
                                class="w-32 h-32 bg-gray-100 mx-auto rounded-2xl flex items-center justify-center mb-3">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                    </path>
                                </svg>
                            </div>
                            <p class="font-bold text-purple-600 text-lg">Rp {{ number_format($order->total_amount,
                                0,
                                ',', '.') }}</p>
                            <p class="text-xs text-gray-600 mt-1">Scan dengan e-wallet</p>
                        </div>

                        <div id="qris-status" class="text-center mb-4">
                            <p class="text-sm text-purple-700 flex items-center justify-center">
                                <svg class="animate-spin w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Menunggu pembayaran...
                            </p>
                        </div>

                        <form action="{{ route('payments.simulate-qris', $order->payment) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-purple-500 to-pink-600 text-white py-3 rounded-2xl hover:from-purple-600 hover:to-pink-700 transition-all font-semibold shadow-lg">
                                ⚡ Simulasi Berhasil
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 mt-2 text-center">*Testing purposes</p>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Delivery Info --}}
                @if ($order->delivery)
                <div
                    class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <div
                            class="w-6 h-6 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-3 shadow-md">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        Pengiriman
                    </h2>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">Status:</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-xl text-sm font-medium">
                                {{ $order->delivery->status_label }}
                            </span>
                        </div>

                        @if ($order->delivery->courier)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">Kurir:</span>
                            <span class="font-medium">{{ $order->delivery->courier->name }}</span>
                        </div>
                        @endif

                        @if ($order->delivery->tracking_number)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm">No. Resi:</span>
                            <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{
                                $order->delivery->tracking_number }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-100">
                        <p class="text-sm text-blue-800 font-medium flex items-center">
                            <span class="mr-2">📦</span>
                            {{ $order->delivery->status_label }}
                        </p>
                    </div>
                </div>
                @endif

                {{-- Back Button --}}
                <div
                    class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <a href="{{ route('orders.index') }}"
                        class="w-full inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-2xl hover:from-gray-700 hover:to-gray-800 transition-all transform hover:scale-105 font-semibold shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Review Modal --}}
<div id="reviewModal"
    class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 w-96 max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Berikan Review</h3>
                <button onclick="closeReviewModal()"
                    class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="reviewForm" action="#" method="POST">
                @csrf
                <input type="hidden" name="product_id" id="review_product_id">
                <input type="hidden" name="order_id" value="{{ $order->id }}">

                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-1">Produk:</p>
                    <p class="font-semibold text-gray-900" id="review_product_name"></p>
                </div>

                {{-- Rating --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Rating *</label>
                    <div class="flex items-center gap-2 justify-center">
                        @for ($i = 1; $i <= 5; $i++) <button type="button" onclick="setRating({{ $i }})"
                            class="rating-star text-3xl text-gray-300 hover:text-yellow-400 transition-all transform hover:scale-110">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            </button>
                            @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating_value" required>
                    <p class="text-xs text-gray-500 mt-2 text-center">Berikan penilaian untuk produk ini</p>
                </div>

                {{-- Comment --}}
                <div class="mb-6">
                    <label for="comment" class="block text-sm font-semibold text-gray-700 mb-3">
                        Komentar & Saran (Opsional)
                    </label>
                    <textarea name="comment" id="comment" rows="4"
                        class="w-full rounded-2xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                        placeholder="Bagikan pengalaman Anda dengan produk ini..."></textarea>
                    <p class="text-xs text-gray-500 mt-2">Komentar akan membantu pembeli lain</p>
                </div>

                <div class="flex gap-4">
                    <button type="button" onclick="closeReviewModal()"
                        class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-2xl hover:bg-gray-200 transition-colors font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-2xl hover:from-blue-600 hover:to-indigo-700 transition-all font-semibold shadow-lg">
                        Kirim Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div id="imageModal"
    class="hidden fixed inset-0 bg-gray-900 bg-opacity-90 backdrop-blur-sm overflow-y-auto h-full w-full z-50"
    onclick="closeImageModal()">
    <div class="relative top-10 mx-auto p-5 w-auto max-w-4xl">
        <img id="modalImage" src="" alt="Bukti Pembayaran" class="w-full rounded-3xl shadow-2xl">
    </div>
</div>

<script>
    let currentRating = 0;

    function openReviewModal(productId, productName) {
        document.getElementById('review_product_id').value = productId;
        document.getElementById('review_product_name').textContent = productName;
        document.getElementById('reviewForm').action = `/products/${productId}/reviews`;
        document.getElementById('reviewModal').classList.remove('hidden');
        resetRating();
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
        document.getElementById('reviewForm').reset();
        resetRating();
    }

    function setRating(rating) {
        currentRating = rating;
        document.getElementById('rating_value').value = rating;

        const stars = document.querySelectorAll('.rating-star');
        stars.forEach((star, index) => {
            const svg = star.querySelector('svg');
            if (index < rating) {
                svg.classList.remove('text-gray-300');
                svg.classList.add('text-yellow-400');
            } else {
                svg.classList.remove('text-yellow-400');
                svg.classList.add('text-gray-300');
            }
        });
    }

    function resetRating() {
        currentRating = 0;
        document.getElementById('rating_value').value = '';
        const stars = document.querySelectorAll('.rating-star');
        stars.forEach(star => {
            const svg = star.querySelector('svg');
            svg.classList.remove('text-yellow-400');
            svg.classList.add('text-gray-300');
        });
    }

    function showImageModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    // QRIS Payment Status Polling
    @if ($order->payment && $order->payment->method === 'qris' && $order->payment->status === 'pending')
        function checkQrisPaymentStatus() {
            fetch(`/payments/{{ $order->payment->id }}/status`)
                .then(response => response.json())
                .then(data => {
                    const statusDiv = document.getElementById('qris-status');

                    if (data.status === 'paid') {
                        statusDiv.innerHTML = `
                            <p class="text-sm text-green-700 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Pembayaran berhasil!
                            </p>
                        `;
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else if (data.status === 'failed') {
                        statusDiv.innerHTML = `
                            <p class="text-sm text-red-700 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Pembayaran gagal!
                            </p>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error checking payment status:', error);
                });
        }

        // Check payment status every 5 seconds
        const paymentChecker = setInterval(checkQrisPaymentStatus, 5000);

        // Stop checking after 10 minutes
        setTimeout(() => {
            clearInterval(paymentChecker);
        }, 600000);
    @endif
</script>
@endsection
