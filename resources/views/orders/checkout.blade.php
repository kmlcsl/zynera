@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8">Checkout</h1>

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Checkout Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Shipping Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold mb-4">Informasi Pengiriman</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Penerima *
                                </label>
                                <input type="text" name="recipient_name" id="recipient_name" required
                                    value="{{ old('recipient_name', auth()->user()->name) }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                @error('recipient_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="recipient_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon *
                                </label>
                                <input type="tel" name="recipient_phone" id="recipient_phone" required
                                    value="{{ old('recipient_phone') }}" placeholder="08xxxxxxxxxx"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                @error('recipient_phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Lengkap *
                            </label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" required
                                placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Provinsi, Kode Pos"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan (Opsional)
                            </label>
                            <textarea name="notes" id="notes" rows="2" placeholder="Catatan untuk pesanan (warna, ukuran, dll)"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold mb-4">Metode Pembayaran</h2>

                        <div class="space-y-3">
                            <label class="flex items-center p-4 border rounded-lg cursor-not-allowed bg-gray-50 opacity-60">
                                <input type="radio" name="payment_method" value="cod" disabled
                                    class="text-gray-400 cursor-not-allowed">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-500">Bayar di Tempat (COD)</span>
                                            <span
                                                class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">
                                                Coming Soon
                                            </span>
                                        </div>
                                        <i class="fas fa-money-bill-wave text-gray-400"></i>
                                    </div>
                                    <p class="text-sm text-gray-500">Sedang dalam perbaikan sistem</p>
                                </div>
                            </label>

                            <label
                                class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="payment_method" value="transfer"
                                    class="text-green-600 focus:ring-green-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium">Transfer Bank</span>
                                        <i class="fas fa-university text-blue-600"></i>
                                    </div>
                                    <p class="text-sm text-gray-600">Transfer ke rekening toko</p>
                                </div>
                            </label>

                            <label
                                class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="payment_method" value="qris"
                                    class="text-green-600 focus:ring-green-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium">QRIS</span>
                                        <i class="fas fa-qrcode text-purple-600"></i>
                                    </div>
                                    <p class="text-sm text-gray-600">Scan QR Code untuk bayar</p>
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Items Review -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold mb-4">Review Pesanan</h2>

                        <div class="space-y-4">
                            @foreach ($carts as $cart)
                                @php
                                    $images = [];
                                    if ($cart->product->images) {
                                        if (is_array($cart->product->images)) {
                                            $images = $cart->product->images;
                                        } elseif (is_string($cart->product->images)) {
                                            $decoded = json_decode($cart->product->images, true);
                                            $images = is_array($decoded) ? $decoded : [];
                                        }
                                    }
                                    $imageCount = is_array($images) ? count($images) : 0;
                                @endphp

                                <div class="flex items-start gap-4 p-4 border rounded-lg">
                                    <!-- Product Image -->
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                        @if ($imageCount > 0)
                                            <img src="{{ asset('storage/' . $images[0]) }}"
                                                alt="{{ $cart->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <i class="fas fa-image text-sm"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1">
                                        <h3 class="font-semibold">{{ $cart->product->name }}</h3>
                                        <p class="text-gray-600 text-sm">{{ $cart->product->category->name }}</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-sm text-gray-600">{{ $cart->quantity }} x Rp
                                                {{ number_format($cart->product->price, 0, ',', '.') }}</span>
                                            <span class="font-bold text-green-600">Rp
                                                {{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                        <h2 class="text-xl font-bold mb-4">Ringkasan Pesanan</h2>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span>Subtotal ({{ $carts->sum('quantity') }} item)</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Biaya Layanan (2%)</span>
                                <span>Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Ongkos Kirim</span>
                                <span>Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg border-t pt-3">
                                <span>Total Pembayaran</span>
                                <span class="text-green-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-medium">
                            <i class="fas fa-check mr-2"></i>
                            Buat Pesanan
                        </button>

                        <div class="mt-4 text-center">
                            <a href="{{ route('cart.index') }}"
                                class="text-green-600 hover:text-green-700 text-sm font-medium">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali ke Keranjang
                            </a>
                        </div>

                        <!-- Payment Information -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="font-medium text-gray-900 mb-2">Informasi Penting:</h3>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Pesanan akan diproses setelah pembayaran dikonfirmasi</li>
                                <li>• Estimasi pengiriman 1-3 hari kerja</li>
                                <li>• Hubungi customer service jika ada kendala</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
