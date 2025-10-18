@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8">Checkout</h1>

        <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Checkout Form -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Shipping Method -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-400">
                        <h2 class="text-xl font-bold mb-4 flex items-center">
                            <svg class="w-6 h-6 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Metode Pengiriman
                        </h2>
                        <p class="text-sm text-gray-600 mb-1"><strong>Langkah 1:</strong> Silakan pilih metode pengiriman terlebih dahulu</p>
                        <p class="text-xs text-orange-600 mb-4">Form lainnya akan muncul setelah Anda memilih metode pengiriman</p>

                        <div class="space-y-3">
                            <!-- Pickup Method -->
                            <label
                                class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors shipping-method-option"
                                data-cost="0">
                                <input type="radio" name="shipping_method" value="pickup" required
                                    class="text-green-600 focus:ring-green-500 shipping-method-input">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium">Jemput di Tempat</span>
                                        <div class="flex items-center">
                                            <span class="text-green-600 font-bold mr-2">GRATIS</span>
                                            <i class="fas fa-hand-holding text-green-600"></i>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600">Ambil pesanan langsung di lokasi penjual</p>
                                </div>
                            </label>

                            <!-- Courier Delivery -->
                            <label
                                class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors shipping-method-option"
                                data-cost="5000">
                                <input type="radio" name="shipping_method" value="courier" required
                                    class="text-green-600 focus:ring-green-500 shipping-method-input">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium">Kurir Antar</span>
                                        <div class="flex items-center">
                                            <span class="text-gray-900 font-bold mr-2">Rp 5.000</span>
                                            <i class="fas fa-motorcycle text-blue-600"></i>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600">Pesanan diantar langsung ke alamat Anda</p>
                                </div>
                            </label>
                        </div>
                        @error('shipping_method')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror

                        <!-- Pickup Information (Hidden by default) -->
                        <div id="pickup-info" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                            <h3 class="font-semibold text-blue-800 mb-3">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                Informasi Lokasi Jemput
                            </h3>
                            <div id="pickup-locations" class="space-y-3">
                                @foreach($carts as $cart)
                                    <div class="pickup-location-item bg-white p-3 rounded border">
                                        <div class="flex items-start gap-3">
                                            <div class="w-12 h-12 bg-gray-200 rounded overflow-hidden flex-shrink-0">
                                                @if($cart->product->main_image_url)
                                                    <img src="{{ $cart->product->main_image_url }}" alt="{{ $cart->product->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <i class="fas fa-image text-xs"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-800">{{ $cart->product->name }}</h4>
                                                <div class="mt-2 space-y-1">
                                                    @if($cart->product->village)
                                                        <div class="flex items-start gap-2 text-sm">
                                                            <i class="fas fa-map-marker-alt text-red-500 mt-0.5 flex-shrink-0"></i>
                                                            <span class="text-gray-700">{{ $cart->product->village->full_path }}</span>
                                                        </div>
                                                    @endif
                                                    @if($cart->product->user && $cart->product->user->phone)
                                                        <div class="flex items-center gap-2 text-sm">
                                                            <i class="fas fa-phone text-green-500 flex-shrink-0"></i>
                                                            <span class="text-gray-700">{{ $cart->product->user->phone }}</span>
                                                            <span class="text-gray-500">({{ $cart->product->user->name }})</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded">
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-info-circle text-yellow-600 mt-0.5 flex-shrink-0"></i>
                                    <div class="text-sm text-yellow-800">
                                        <p class="font-medium">Catatan Penting:</p>
                                        <ul class="mt-1 space-y-1 list-disc list-inside">
                                            <li>Silakan hubungi penjual untuk mengatur waktu pengambilan</li>
                                            <li>Bawa bukti pesanan saat mengambil barang</li>
                                            <li>Periksa kondisi barang sebelum membawa pulang</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Shipping Information (Initially Hidden) -->
                        <div id="shipping-info" class="bg-white rounded-lg shadow-md p-6 mt-6 hidden">
                            <h2 class="text-xl font-bold mb-4 flex items-center">
                                <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Langkah 2: Informasi Pengiriman</span>
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Penerima *
                                    </label>
                                    <input type="text" name="recipient_name" id="recipient_name"
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
                                    <input type="tel" name="recipient_phone" id="recipient_phone"
                                        value="{{ old('recipient_phone', auth()->user()->phone ?? '') }}" placeholder="08xxxxxxxxxx"
                                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                    @error('recipient_phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Shipping Address Field (for courier only) -->
                            <div id="shipping-address-field" class="mt-4">
                                <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat Lengkap *
                                </label>
                                <textarea name="shipping_address" id="shipping_address" rows="3"
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
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold mb-4">Metode Pembayaran</h2>

                        <div class="space-y-3">
                            <!-- Manual Payment -->
                            <label
                                class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="payment_method" value="manual" required
                                    class="text-green-600 focus:ring-green-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium">Manual</span>
                                        <i class="fas fa-money-bill-wave text-blue-600"></i>
                                    </div>
                                    <p class="text-sm text-gray-600">Pembayaran manual via transfer bank atau QRIS dengan upload bukti</p>
                                </div>
                            </label>

                            <!-- Midtrans Payment -->
                            <label
                                class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="payment_method" value="midtrans" required
                                    class="text-green-600 focus:ring-green-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium">Otomatis (Midtrans)</span>
                                        <i class="fas fa-credit-card text-purple-600"></i>
                                    </div>
                                    <p class="text-sm text-gray-600">Pembayaran otomatis dengan kartu kredit/debit, e-wallet, dan lainnya</p>
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror

                        <!-- Manual Payment Details (Initially Hidden) -->
                        <div id="manual-payment-details" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                            <h3 class="font-semibold text-blue-800 mb-3">
                                <i class="fas fa-university mr-2"></i>
                                Informasi Transfer Bank
                            </h3>
                            <div class="bg-white p-4 rounded border">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 bg-red-600 rounded flex items-center justify-center text-white font-bold">
                                        BSI
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-800">Bank Syariah Indonesia (BSI)</h4>
                                        <p class="text-gray-600 text-sm">Transfer ke rekening berikut:</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-credit-card text-blue-500 flex-shrink-0"></i>
                                        <span class="font-mono text-lg font-bold">7254348273</span>
                                        <button type="button" onclick="copyToClipboard('7254348273')" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-user text-green-500 flex-shrink-0"></i>
                                        <span class="text-gray-700">a.n. Zynera Market</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <label for="proof_image" class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Bukti Pembayaran *
                                </label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="proof_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-3"></i>
                                            <p class="mb-2 text-sm text-gray-500">
                                                <span class="font-semibold">Klik untuk upload</span> atau drag & drop
                                            </p>
                                            <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 2MB)</p>
                                        </div>
                                        <input id="proof_image" name="proof_image" type="file" class="hidden" accept="image/*" onchange="previewImage(event)">
                                    </label>
                                </div>
                                <div id="image-preview" class="mt-3 hidden">
                                    <img id="preview-img" src="" alt="Preview" class="max-w-48 h-32 object-cover rounded border">
                                    <button type="button" onclick="removeImage()" class="mt-2 text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash mr-1"></i>Hapus gambar
                                    </button>
                                </div>
                                @error('proof_image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded">
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-info-circle text-yellow-600 mt-0.5 flex-shrink-0"></i>
                                    <div class="text-sm text-yellow-800">
                                        <p class="font-medium">Petunjuk Pembayaran:</p>
                                        <ul class="mt-1 space-y-1 list-disc list-inside">
                                            <li>Transfer sesuai dengan total pembayaran</li>
                                            <li>Upload bukti transfer yang jelas dan lengkap</li>
                                            <li>Pesanan akan diproses setelah pembayaran terverifikasi</li>
                                            <li>Proses verifikasi maksimal 2x24 jam</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items Review -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold mb-4">Review Pesanan</h2>

                        <div class="space-y-4">
                            @foreach ($carts as $cart)
                                <div class="flex items-start gap-4 p-4 border rounded-lg">
                                    <!-- Product Image -->
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                        @if ($cart->product->main_image_url)
                                            <img src="{{ $cart->product->main_image_url }}"
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
                                <span id="shipping-cost-display">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg border-t pt-3">
                                <span>Total Pembayaran</span>
                                <span class="text-green-600" id="total-display">Rp {{ number_format($total, 0, ',', '.') }}</span>
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

    <script>
        // Utility functions
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Nomor rekening berhasil disalin!');
            }, function(err) {
                console.error('Error copying text: ', err);
            });
        }
        
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Check file size (2MB = 2097152 bytes)
                if (file.size > 2097152) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
        
        function removeImage() {
            const input = document.getElementById('proof_image');
            const preview = document.getElementById('image-preview');
            
            input.value = '';
            preview.classList.add('hidden');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const shippingMethodInputs = document.querySelectorAll('.shipping-method-input');
            const paymentMethodInputs = document.querySelectorAll('input[name="payment_method"]');
            const shippingCostDisplay = document.getElementById('shipping-cost-display');
            const totalDisplay = document.getElementById('total-display');
            const pickupInfo = document.getElementById('pickup-info');
            const shippingInfo = document.getElementById('shipping-info');
            const shippingAddressField = document.getElementById('shipping-address-field');
            const shippingAddressTextarea = document.getElementById('shipping_address');
            const recipientNameInput = document.getElementById('recipient_name');
            const recipientPhoneInput = document.getElementById('recipient_phone');
            const manualPaymentDetails = document.getElementById('manual-payment-details');
            const proofImageInput = document.getElementById('proof_image');
            
            // Initial values from backend
            const subtotal = {{ $subtotal }};
            const serviceFee = {{ $serviceFee }};
            const defaultShippingCost = {{ $shippingCost }};
            
            function updateOrderSummary() {
                const selectedShippingMethod = document.querySelector('input[name="shipping_method"]:checked');
                let shippingCost = defaultShippingCost; // Default shipping cost
                
                if (selectedShippingMethod) {
                    const shippingOption = selectedShippingMethod.closest('.shipping-method-option');
                    shippingCost = parseInt(shippingOption.dataset.cost);
                    
                    // Show shipping information form
                    shippingInfo.classList.remove('hidden');
                    
                    // Show/hide components based on selected method
                    if (selectedShippingMethod.value === 'pickup') {
                        // Pickup method selected
                        pickupInfo.classList.remove('hidden');
                        shippingAddressField.classList.add('hidden');
                        
                        // Remove required attribute from shipping address for pickup
                        shippingAddressTextarea.removeAttribute('required');
                        shippingAddressTextarea.value = ''; // Clear address field
                        
                        // Set required for basic info
                        recipientNameInput.setAttribute('required', 'required');
                        recipientPhoneInput.setAttribute('required', 'required');
                        
                        // Smooth scroll to pickup info
                        setTimeout(() => {
                            pickupInfo.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }, 300);
                    } else {
                        // Courier method selected
                        pickupInfo.classList.add('hidden');
                        shippingAddressField.classList.remove('hidden');
                        
                        // Set required attributes for courier delivery
                        shippingAddressTextarea.setAttribute('required', 'required');
                        recipientNameInput.setAttribute('required', 'required');
                        recipientPhoneInput.setAttribute('required', 'required');
                    }
                } else {
                    // No method selected - hide everything
                    shippingInfo.classList.add('hidden');
                    pickupInfo.classList.add('hidden');
                }
                
                const total = subtotal + serviceFee + shippingCost;
                
                // Update displays
                shippingCostDisplay.textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
                totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }
            
            // Handle payment method changes
            function handlePaymentMethodChange() {
                const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
                
                if (selectedPaymentMethod && selectedPaymentMethod.value === 'manual') {
                    manualPaymentDetails.classList.remove('hidden');
                    proofImageInput.setAttribute('required', 'required');
                } else {
                    manualPaymentDetails.classList.add('hidden');
                    proofImageInput.removeAttribute('required');
                    removeImage(); // Clear any uploaded image
                }
            }
            
            // Add event listeners to shipping method inputs
            shippingMethodInputs.forEach(function(input) {
                input.addEventListener('change', updateOrderSummary);
            });
            
            // Add event listeners to payment method inputs
            paymentMethodInputs.forEach(function(input) {
                input.addEventListener('change', handlePaymentMethodChange);
            });
            
            // Don't pre-select any shipping method - force user to choose
            // Initial state: everything hidden until user selects shipping method
            updateOrderSummary();
            handlePaymentMethodChange();
        });
    </script>
@endsection
