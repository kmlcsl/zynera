@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8">Keranjang Belanja</h1>

        @if ($carts->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6 border-b">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-bold">Produk ({{ $carts->count() }} item)</h2>
                                <button onclick="selectAll()" id="selectAllBtn"
                                    class="text-green-600 hover:text-green-700 text-sm font-medium">
                                    Pilih Semua
                                </button>
                            </div>
                        </div>

                        <div class="divide-y">
                            @foreach ($carts as $cart)
                                <div class="p-6 cart-item" data-id="{{ $cart->id }}"
                                    data-price="{{ $cart->product->price }}">
                                    <div class="flex items-start gap-4">
                                        <!-- Checkbox -->
                                        <div class="flex items-center pt-2">
                                            <input type="checkbox"
                                                class="item-checkbox rounded border-gray-300 text-green-600 focus:border-green-500 focus:ring-green-500"
                                                value="{{ $cart->id }}" onchange="updateTotal()" checked>
                                        </div>

                                        <!-- Product Image -->
                                        <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                            @if ($cart->product->main_image_url)
                                                <img src="{{ $cart->product->main_image_url }}"
                                                    alt="{{ $cart->product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Product Info -->
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-lg">{{ $cart->product->name }}</h3>
                                            <p class="text-gray-600 text-sm">{{ $cart->product->category->name }}</p>
                                            <p class="text-gray-500 text-sm">Penjual: {{ $cart->product->user->name }}</p>

                                            <!-- Stock Check -->
                                            @if ($cart->product->stock < $cart->quantity)
                                                <div
                                                    class="mt-2 p-2 bg-red-50 border border-red-200 rounded text-red-700 text-sm">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Stok tidak mencukupi! Tersedia: {{ $cart->product->stock }}
                                                </div>
                                            @endif

                                            <div class="mt-4 flex items-center justify-between">
                                                <!-- Quantity Controls -->
                                                <div class="flex items-center gap-3">
                                                    <div class="flex items-center border rounded-lg">
                                                        <button type="button"
                                                            onclick="updateQuantity({{ $cart->id }}, 'decrease')"
                                                            class="px-3 py-1 text-gray-600 hover:bg-gray-100">-</button>
                                                        <span
                                                            class="px-3 py-1 quantity-display">{{ $cart->quantity }}</span>
                                                        <button type="button"
                                                            onclick="updateQuantity({{ $cart->id }}, 'increase')"
                                                            class="px-3 py-1 text-gray-600 hover:bg-gray-100">+</button>
                                                    </div>

                                                    <button onclick="removeItem({{ $cart->id }})"
                                                        class="text-red-600 hover:text-red-700 text-sm">
                                                        <i class="fas fa-trash mr-1"></i>
                                                        Hapus
                                                    </button>
                                                </div>

                                                <!-- Price -->
                                                <div class="text-right">
                                                    <p class="text-green-600 font-bold text-lg">
                                                        Rp
                                                        {{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}
                                                    </p>
                                                    <p class="text-gray-500 text-sm">
                                                        Rp {{ number_format($cart->product->price, 0, ',', '.') }} / item
                                                    </p>
                                                </div>
                                            </div>
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
                        <h2 class="text-xl font-bold mb-4">Ringkasan Belanja</h2>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span>Total Produk (<span id="selected-count">0</span> item)</span>
                                <span id="subtotal">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Ongkos Kirim</span>
                                <span>Rp {{ number_format(5000, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg border-t pt-3">
                                <span>Total Pembayaran</span>
                                <span class="text-green-600" id="total">Rp
                                    {{ number_format(5000, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <form action="{{ route('checkout') }}" method="GET" id="checkoutForm">
                            <input type="hidden" name="items" id="selectedItems">
                            <button type="submit" id="checkoutBtn" disabled
                                class="w-full bg-green-600 text-white py-3 rounded-lg font-medium transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed hover:bg-green-700">
                                Beli Sekarang
                            </button>
                        </form>

                        <div class="mt-4 text-center space-y-2">
                            <a href="{{ route('products.index') }}"
                                class="block text-green-600 hover:text-green-700 text-sm font-medium">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Lanjut Belanja
                            </a>

                            <a href="{{ route('orders.index') }}"
                                class="block text-blue-600 hover:text-blue-700 text-sm font-medium">
                                <i class="fas fa-list-alt mr-1"></i>
                                Lihat Pesanan Saya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-16">
                <div class="mb-6">
                    <i class="fas fa-shopping-cart text-6xl text-gray-300"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Keranjang Belanja Kosong</h2>
                <p class="text-gray-600 mb-6">Yuk, isi keranjangmu dengan produk-produk fresh dari Green Fresh!</p>
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>

    <script>
        let selectedItems = [];
        const shippingCost = 5000;

        function selectAll() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const selectAllBtn = document.getElementById('selectAllBtn');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });

            selectAllBtn.textContent = !allChecked ? 'Batal Pilih Semua' : 'Pilih Semua';
            updateTotal();
        }

        function updateTotal() {
            const checkboxes = document.querySelectorAll('.item-checkbox:checked');
            selectedItems = [];
            let subtotal = 0;

            checkboxes.forEach(checkbox => {
                const cartItem = checkbox.closest('.cart-item');
                const price = parseInt(cartItem.dataset.price);
                const quantity = parseInt(cartItem.querySelector('.quantity-display').textContent);
                const total = price * quantity;

                selectedItems.push(checkbox.value);
                subtotal += total;
            });

            const total = subtotal + (selectedItems.length > 0 ? shippingCost : 0);

            document.getElementById('selected-count').textContent = selectedItems.length;
            document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('selectedItems').value = selectedItems.join(',');

            const checkoutBtn = document.getElementById('checkoutBtn');
            checkoutBtn.disabled = selectedItems.length === 0;

            // Update select all button text
            const allCheckboxes = document.querySelectorAll('.item-checkbox');
            const selectAllBtn = document.getElementById('selectAllBtn');
            const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
            selectAllBtn.textContent = allChecked ? 'Batal Pilih Semua' : 'Pilih Semua';
        }

        function updateQuantity(cartId, action) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PATCH');
            formData.append('action', action);

            fetch(`/cart/${cartId}`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengupdate quantity');
                });
        }

        function removeItem(cartId) {
            if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'DELETE');

                fetch(`/cart/${cartId}`, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus item');
                    });
            }
        }

        // Initialize total calculation on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set all checkboxes to checked by default
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });

            // Update total calculation
            updateTotal();

            // Update select all button text
            const selectAllBtn = document.getElementById('selectAllBtn');
            selectAllBtn.textContent = 'Batal Pilih Semua';
        });
    </script>
@endsection
