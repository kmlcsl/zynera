@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('products.index') }}"
                class="inline-flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar Produk
            </a>
        </div>

        <!-- Product Detail -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Product Images -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                    @if ($product->main_image_url)
                        <img id="mainImage" src="{{ $product->main_image_url }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <i class="fas fa-image text-6xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Images -->
                @if (count($product->image_urls) > 1)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach ($product->image_urls as $index => $imageUrl)
                            <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden cursor-pointer border-2 hover:border-green-500 transition-colors"
                                onclick="changeMainImage('{{ $imageUrl }}', this)">
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
                    <p class="text-gray-600">{{ $product->category->name }}</p>
                    <p class="text-sm text-gray-500">Penjual: {{ $product->user->name }}</p>
                </div>

                <!-- Price -->
                <div class="border-b pb-4">
                    <p class="text-3xl font-bold text-green-600">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Stock Info -->
                <div class="flex items-center gap-4">
                    <span class="text-gray-600">Stok:</span>
                    <span class="font-medium {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $product->stock > 0 ? $product->stock . ' tersedia' : 'Habis' }}
                    </span>
                </div>

                <!-- Rating and Share -->
                <div class="flex items-center justify-between">
                    <div>
                        @if ($product->reviews->count() > 0)
                            <div class="flex items-center gap-2">
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star text-sm {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600">
                                    {{ number_format($product->average_rating, 1) }} ({{ $product->reviews->count() }} review)
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Share Button -->
                    <button onclick="openShareModal()"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-share-alt"></i>
                        <span>Bagikan</span>
                    </button>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="font-semibold mb-2">Deskripsi</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                </div>

                <!-- Add to Cart Form -->
                @auth
                    @if ($product->stock > 0)
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <label class="text-gray-600">Jumlah:</label>
                                <div class="flex items-center border rounded-lg">
                                    <button type="button" onclick="decreaseQuantity()"
                                        class="px-3 py-2 text-gray-600 hover:bg-gray-100">-</button>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1"
                                        max="{{ $product->stock }}" class="w-16 text-center border-0 focus:ring-0">
                                    <button type="button" onclick="increaseQuantity({{ $product->stock }})"
                                        class="px-3 py-2 text-gray-600 hover:bg-gray-100">+</button>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <!-- Add to Cart Button -->
                                <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" id="cart_quantity" value="1">
                                    <button type="submit"
                                        class="w-full bg-gray-100 text-gray-800 py-3 rounded-lg hover:bg-gray-200 transition-colors font-medium border">
                                        <i class="fas fa-cart-plus mr-2"></i>
                                        Tambah ke Keranjang
                                    </button>
                                </form>

                                <!-- Buy Now Button -->
                                <form action="{{ route('products.buy-now') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" id="buy_quantity" value="1">
                                    <button type="submit"
                                        class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors font-medium">
                                        <i class="fas fa-bolt mr-2"></i>
                                        Beli Sekarang
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-800 font-medium">Produk ini sedang tidak tersedia</p>
                        </div>
                    @endif
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-blue-800">
                            <a href="{{ route('login') }}" class="font-medium underline">Login</a>
                            untuk menambahkan produk ke keranjang
                        </p>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Product Reviews -->
        @if ($product->reviews->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-bold mb-4">Review Produk</h2>
                <div class="space-y-4">
                    @foreach ($product->reviews as $review)
                        <div class="border-b pb-4 last:border-b-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-3">
                                    <span class="font-medium">{{ $review->user->name }}</span>
                                    <div class="flex items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</span>
                            </div>
                            @if ($review->comment)
                                <p class="text-gray-700">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Related Products -->
        @if ($relatedProducts->count() > 0)
            <div>
                <h2 class="text-2xl font-bold mb-6">Produk Serupa</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($relatedProducts as $relatedProduct)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}">
                                <div class="aspect-square bg-gray-200">
                                    @if ($relatedProduct->main_image_url)
                                        <img src="{{ $relatedProduct->main_image_url }}"
                                            alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i class="fas fa-image text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <h3 class="font-semibold mb-1 line-clamp-2">{{ $relatedProduct->name }}</h3>
                                    <p class="text-green-600 font-bold">
                                        Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Share Modal -->
        <div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Bagikan Produk</h3>
                        <button onclick="closeShareModal()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="space-y-3">
                        <!-- WhatsApp Share -->
                        <button onclick="shareToWhatsApp()"
                            class="w-full flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="fab fa-whatsapp text-white"></i>
                            </div>
                            <span>Bagikan ke WhatsApp</span>
                        </button>

                        <!-- Instagram Share -->
                        <button onclick="shareToInstagram()"
                            class="w-full flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                <i class="fab fa-instagram text-white"></i>
                            </div>
                            <span>Bagikan ke Instagram</span>
                        </button>

                        <!-- Copy Link -->
                        <button onclick="copyProductLink()"
                            class="w-full flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-link text-white"></i>
                            </div>
                            <span id="copyLinkText">Salin Link</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
<script>
    function changeMainImage(src, element) {
        document.getElementById('mainImage').src = src;

        // Remove active border from all thumbnails
        document.querySelectorAll('.grid > div').forEach(div => {
            div.classList.remove('border-green-500');
            div.classList.add('border-transparent');
        });

        // Add active border to clicked thumbnail
        element.classList.add('border-green-500');
        element.classList.remove('border-transparent');
    }

    function increaseQuantity(maxStock) {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        if (currentValue < maxStock) {
            const newValue = currentValue + 1;
            quantityInput.value = newValue;
            document.getElementById('cart_quantity').value = newValue;
            document.getElementById('buy_quantity').value = newValue;
        }
    }

    function decreaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            const newValue = currentValue - 1;
            quantityInput.value = newValue;
            document.getElementById('cart_quantity').value = newValue;
            document.getElementById('buy_quantity').value = newValue;
        }
    }

    // Update hidden inputs when quantity input changes manually
    document.getElementById('quantity').addEventListener('input', function() {
        const value = this.value;
        document.getElementById('cart_quantity').value = value;
        document.getElementById('buy_quantity').value = value;
    });

    // Share Modal Functions
    function openShareModal() {
        document.getElementById('shareModal').classList.remove('hidden');
    }

    function closeShareModal() {
        document.getElementById('shareModal').classList.add('hidden');
    }

    function shareToWhatsApp() {
        const productName = {!! json_encode($product->name) !!};
        const productPrice = {!! json_encode('Rp ' . number_format($product->price, 0, ',', '.')) !!};
        const productUrl = window.location.href;
        const message = `Lihat produk ini: ${productName}\nHarga: ${productPrice}\n\n${productUrl}`;
        const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
    }

    function shareToInstagram() {
        copyProductLink();
        alert('Link telah disalin! Buka Instagram dan tempel link di story atau post Anda.');
        window.open('https://www.instagram.com/', '_blank');
    }

    function copyProductLink() {
        const productUrl = window.location.href;
        navigator.clipboard.writeText(productUrl).then(function() {
            const copyButton = document.getElementById('copyLinkText');
            const originalText = copyButton.textContent;
            copyButton.textContent = 'Tersalin!';
            copyButton.parentElement.classList.add('bg-green-50', 'border-green-200');

            setTimeout(function() {
                copyButton.textContent = originalText;
                copyButton.parentElement.classList.remove('bg-green-50', 'border-green-200');
            }, 2000);
        }).catch(function(err) {
            console.error('Could not copy text: ', err);
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = productUrl;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Link berhasil disalin!');
        });
    }

    // Close modal when clicking outside
    document.getElementById('shareModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeShareModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeShareModal();
        }
    });
</script>
@endpush
