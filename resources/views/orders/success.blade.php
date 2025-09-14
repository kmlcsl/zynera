@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Success Hero Section -->
            <div class="text-center mb-12">
                <div class="relative inline-block">
                    <div
                        class="w-24 h-24 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg animate-pulse">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center animate-bounce">
                        <span class="text-yellow-800 text-lg">🎉</span>
                    </div>
                </div>
                <h1
                    class="text-4xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent mb-3">
                    Pesanan Berhasil Dibuat!
                </h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Terima kasih atas kepercayaan Anda. Berikut adalah ringkasan pesanan dan langkah selanjutnya.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Main Order Card -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Order Header -->
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold mb-1">{{ $order->order_number }}</h2>
                                    <p class="text-green-100 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $order->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>

                                @php
                                    // Determine display status based on payment status
                                    if ($order->payment && $order->payment->status === 'paid') {
                                        $displayStatus = 'Pembayaran Berhasil';
                                        $displayColor = 'bg-green-100 text-green-800 border-green-200';
                                        $statusIcon = '✓';
                                    } elseif ($order->payment && $order->payment->method === 'cod') {
                                        $displayStatus = 'Bayar Saat Terima';
                                        $displayColor = 'bg-blue-100 text-blue-800 border-blue-200';
                                        $statusIcon = '💰';
                                    } else {
                                        $displayStatus = 'Menunggu Pembayaran';
                                        $displayColor = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                                        $statusIcon = '⏳';
                                    }
                                @endphp

                                <div class="text-right">
                                    <div
                                        class="px-4 py-2 {{ $displayColor }} rounded-xl text-sm font-semibold border flex items-center">
                                        <span class="mr-2">{{ $statusIcon }}</span>
                                        {{ $displayStatus }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Produk Pesanan ({{ $order->orderItems->count() }} item)
                            </h3>

                            <div class="space-y-3">
                                @foreach ($order->orderItems as $item)
                                    <div
                                        class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
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

                                        <div
                                            class="w-16 h-16 bg-gray-200 rounded-xl overflow-hidden flex-shrink-0 shadow-sm">
                                            @if ($imageCount > 0)
                                                <img src="{{ asset('storage/' . $images[0]) }}"
                                                    alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">{{ $item->product->name }}</h4>
                                            <p class="text-sm text-gray-600">
                                                {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <div class="text-right">
                                            <p class="font-bold text-green-600 text-lg">
                                                Rp {{ number_format($item->total, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Total -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="space-y-2">
                                    <div class="flex justify-between text-gray-600">
                                        <span>Subtotal</span>
                                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    @if($order->service_fee > 0)
                                    <div class="flex justify-between text-gray-600">
                                        <span>Biaya Layanan (2%)</span>
                                        <span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                                    </div>
                                    @endif
                                    <div class="flex justify-between text-gray-600">
                                        <span>Ongkos Kirim</span>
                                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                    </div>
                                    <div
                                        class="flex justify-between text-xl font-bold text-gray-900 pt-2 border-t border-gray-200">
                                        <span>Total</span>
                                        <span class="text-green-600">Rp
                                            {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping & Payment Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Shipping Info -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Informasi Pengiriman
                            </h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex">
                                    <span class="font-medium text-gray-600 w-20">Penerima:</span>
                                    <span class="text-gray-900">{{ $order->recipient_name }}</span>
                                </div>
                                <div class="flex">
                                    <span class="font-medium text-gray-600 w-20">Telepon:</span>
                                    <span class="text-gray-900">{{ $order->recipient_phone }}</span>
                                </div>
                                <div class="flex">
                                    <span class="font-medium text-gray-600 w-20">Alamat:</span>
                                    <span class="text-gray-900">{{ $order->shipping_address }}</span>
                                </div>
                                @if ($order->notes)
                                    <div class="flex">
                                        <span class="font-medium text-gray-600 w-20">Catatan:</span>
                                        <span class="text-gray-900">{{ $order->notes }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Payment Info -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                                Informasi Pembayaran
                            </h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex">
                                    <span class="font-medium text-gray-600 w-20">Metode:</span>
                                    <span class="text-gray-900">
                                        @if ($order->payment)
                                            {{ $order->payment->method_label }}
                                        @else
                                            Belum dipilih
                                        @endif
                                    </span>
                                </div>
                                <div class="flex">
                                    <span class="font-medium text-gray-600 w-20">Status:</span>
                                    @if ($order->payment)
                                        @if ($order->payment->status === 'paid')
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-800 rounded-lg text-xs font-medium">
                                                ✓ Pembayaran Berhasil
                                            </span>
                                        @elseif ($order->payment->method === 'cod')
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-blue-800 rounded-lg text-xs font-medium">
                                                💰 Bayar Saat Terima
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-lg text-xs font-medium">
                                                ⏳ Menunggu Pembayaran
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-lg text-xs font-medium">
                                            Menunggu Konfirmasi
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">

                    <!-- Payment Instructions -->
                    @if ($order->payment && $order->payment->method !== 'cod' && $order->payment->status !== 'paid')
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl">
                            <h3 class="font-bold text-lg mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Instruksi Pembayaran
                            </h3>

                            @if ($order->payment->method === 'transfer')
                                <div class="space-y-4">
                                    <p class="text-blue-100">Silahkan transfer ke rekening berikut:</p>
                                    <div class="bg-white/20 backdrop-blur-sm p-4 rounded-xl border border-white/30">
                                        <div class="space-y-2">
                                            <p class="font-bold">🏦 Bank BSI</p>
                                            <p>No. Rekening: <span
                                                    class="font-mono bg-white/20 px-2 py-1 rounded">1234567890</span></p>
                                            <p>Atas Nama: <span class="font-semibold">Green Fresh Store</span></p>
                                            <p class="text-lg font-bold border-t border-white/30 pt-2 mt-2">
                                                💳 Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                        class="bg-yellow-100/20 border border-yellow-300/30 rounded-xl p-3 text-yellow-100 text-sm">
                                        <p>⚠️ Setelah transfer, silahkan upload bukti pembayaran di bawah ini.</p>
                                    </div>
                                </div>
                            @elseif ($order->payment->method === 'qris')
                                <div class="space-y-4">
                                    <p class="text-blue-100">Scan QR Code berikut untuk pembayaran:</p>
                                    <div
                                        class="bg-white/20 backdrop-blur-sm p-4 rounded-xl border border-white/30 text-center">
                                        <div
                                            class="w-40 h-40 bg-white/30 mx-auto rounded-xl flex items-center justify-center">
                                            <svg class="w-20 h-20 text-white/70" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="mt-3 font-bold text-lg">Rp
                                            {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    </div>
                                    <div
                                        class="bg-green-100/20 border border-green-300/30 rounded-xl p-3 text-green-100 text-sm">
                                        <p>✅ Pembayaran akan otomatis terkonfirmasi setelah berhasil.</p>
                                    </div>

                                    <!-- QRIS Simulation Button (for testing) -->
                                    <div class="text-center pt-2">
                                        <form action="{{ route('payments.simulate-qris', $order->payment) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-white/20 hover:bg-white/30 border border-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                                🧪 Simulasi Pembayaran (Testing)
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Upload Bukti Pembayaran -->
                        @if ($order->payment->method === 'transfer')
                            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                                <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    Upload Bukti Pembayaran
                                </h3>

                                @if ($order->payment->proof_image)
                                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                                        <p class="text-green-800 text-sm font-medium mb-2">✅ Bukti pembayaran sudah
                                            diupload</p>
                                        <img src="{{ asset('storage/' . $order->payment->proof_image) }}"
                                            alt="Bukti Pembayaran"
                                            class="w-full max-w-xs mx-auto rounded-lg shadow-sm border">
                                        <p class="text-green-700 text-xs mt-2 text-center">Menunggu konfirmasi admin</p>
                                    </div>
                                @else
                                    <form action="{{ route('payments.upload-proof', $order->payment) }}" method="POST"
                                        enctype="multipart/form-data" class="space-y-4">
                                        @csrf

                                        <div class="upload-area border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-orange-400 transition-colors cursor-pointer"
                                            onclick="document.getElementById('proof_image').click()">
                                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                </path>
                                            </svg>
                                            <p class="text-gray-600 font-medium">Klik untuk upload bukti transfer</p>
                                            <p class="text-gray-400 text-sm mt-1">JPG, PNG, GIF (Max: 2MB)</p>
                                        </div>

                                        <input type="file" id="proof_image" name="proof_image" class="hidden"
                                            accept="image/*" required onchange="previewImage(this)">

                                        <div id="image-preview" class="hidden">
                                            <img id="preview-img"
                                                class="w-full max-w-xs mx-auto rounded-lg shadow-sm border">
                                            <p class="text-sm text-gray-600 text-center mt-2">Preview gambar</p>
                                        </div>

                                        <button type="submit"
                                            class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white py-3 rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all transform hover:scale-105 shadow-lg">
                                            📤 Upload Bukti Pembayaran
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endif

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-4">Langkah Selanjutnya</h3>
                        <div class="space-y-3">
                            <a href="{{ route('orders.show', $order) }}"
                                class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all transform hover:scale-105 font-semibold shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Lihat Detail Pesanan
                            </a>

                            <a href="{{ route('orders.index') }}"
                                class="w-full flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                Semua Pesanan
                            </a>

                            <a href="{{ route('products.index') }}"
                                class="w-full flex items-center justify-center px-4 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                Lanjut Belanja
                            </a>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl p-6 text-white shadow-xl">
                        <h3 class="font-bold mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                            Tips
                        </h3>
                        <ul class="space-y-2 text-sm text-purple-100">
                            <li class="flex items-start">
                                <span class="mr-2">💡</span>
                                <span>Simpan nomor pesanan untuk tracking</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">⏰</span>
                                <span>Upload bukti transfer maksimal 1x24 jam</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">📱</span>
                                <span>Anda akan mendapat notifikasi via WhatsApp</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image preview function
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const previewDiv = document.getElementById('image-preview');
                    const previewImg = document.getElementById('preview-img');

                    previewImg.src = e.target.result;
                    previewDiv.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Upload area styling
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.querySelector('.upload-area');
            const fileInput = document.getElementById('proof_image');

            if (uploadArea && fileInput) {
                // Drag and drop functionality
                uploadArea.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    uploadArea.classList.add('border-orange-400', 'bg-orange-50');
                });

                uploadArea.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    uploadArea.classList.remove('border-orange-400', 'bg-orange-50');
                });

                uploadArea.addEventListener('drop', function(e) {
                    e.preventDefault();
                    uploadArea.classList.remove('border-orange-400', 'bg-orange-50');

                    if (e.dataTransfer.files.length > 0) {
                        fileInput.files = e.dataTransfer.files;
                        previewImage(fileInput);
                    }
                });
            }
        });

        // Auto check payment status for QRIS (polling every 10 seconds)
        @if ($order->payment && $order->payment->method === 'qris' && $order->payment->status === 'pending')
            setInterval(function() {
                fetch('{{ route('payments.check-status', $order->payment) }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'paid') {
                            location.reload();
                        }
                    })
                    .catch(error => console.log('Error checking payment status:', error));
            }, 10000);
        @endif
    </script>

@endsection
