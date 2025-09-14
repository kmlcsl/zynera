@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gampong-primary mb-4">Kategori Produk</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Jelajahi berbagai kategori produk pangan lokal berkualitas dari Aceh Barat
            </p>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-8 max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="text-2xl font-bold text-gampong-primary">{{ $stats['total_categories'] }}</div>
                    <div class="text-sm text-gray-600">Total Kategori</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="text-2xl font-bold text-gampong-primary">{{ $stats['total_products'] }}</div>
                    <div class="text-sm text-gray-600">Total Produk</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="text-2xl font-bold text-gampong-primary">{{ $stats['categories_with_products'] }}</div>
                    <div class="text-sm text-gray-600">Kategori Aktif</div>
                </div>
            </div>
        </div>

        <!-- Featured Categories Section -->
        @if ($featuredCategories->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gampong-primary mb-6">Kategori Populer</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach ($featuredCategories as $category)
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                            class="group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 hover:scale-105">
                            <div
                                class="aspect-square bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center relative overflow-hidden">
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <div class="w-12 h-12 bg-gampong-primary rounded-full flex items-center justify-center">
                                        <i class="fas fa-tag text-white text-lg"></i>
                                    </div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300">
                                </div>
                            </div>
                            <div class="p-3 text-center">
                                <h3
                                    class="font-semibold text-sm text-gampong-primary group-hover:text-green-700 transition-colors">
                                    {{ $category->name }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">{{ $category->products_count }} produk</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- All Categories Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gampong-primary mb-6">Semua Kategori</h2>

            @if ($categories->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($categories as $category)
                        <div
                            class="group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="block">
                                <!-- Category Image -->
                                <div class="h-48 bg-gradient-to-br from-green-100 to-green-200 relative overflow-hidden">
                                    @if ($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="flex items-center justify-center h-full">
                                            <div
                                                class="w-16 h-16 bg-gampong-primary rounded-full flex items-center justify-center">
                                                <i class="fas fa-tag text-white text-2xl"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300">
                                    </div>

                                    <!-- Product Count Badge -->
                                    <div
                                        class="absolute top-3 right-3 bg-white bg-opacity-90 backdrop-blur-sm rounded-full px-3 py-1">
                                        <span class="text-xs font-semibold text-gampong-primary">
                                            {{ $category->products_count }} produk
                                        </span>
                                    </div>
                                </div>

                                <!-- Category Info -->
                                <div class="p-6">
                                    <h3
                                        class="text-xl font-bold text-gampong-primary group-hover:text-green-700 transition-colors mb-2">
                                        {{ $category->name }}
                                    </h3>
                                    @if ($category->description)
                                        <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                            {{ Str::limit($category->description, 100) }}
                                        </p>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">
                                            <i class="fas fa-shopping-basket mr-1"></i>
                                            {{ $category->products_count }} produk tersedia
                                        </span>
                                        <div
                                            class="flex items-center text-gampong-primary group-hover:text-green-700 transition-colors">
                                            <span class="text-sm font-medium mr-1">Lihat produk</span>
                                            <i
                                                class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <!-- Quick Preview Button -->
                            <div class="px-6 pb-6">
                                <button onclick="showCategoryPreview('{{ $category->slug }}')"
                                    class="w-full bg-gray-100 hover:bg-gampong-secondary hover:text-white text-gray-700 py-2 px-4 rounded-lg transition-all duration-200 text-sm font-medium">
                                    <i class="fas fa-eye mr-2"></i>Pratinjau Cepat
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-tags text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Kategori</h3>
                    <p class="text-gray-400">Kategori produk akan ditampilkan di sini</p>
                </div>
            @endif
        </div>

        <!-- Call to Action -->
        <div class="bg-gradient-to-r from-gampong-primary to-green-700 rounded-lg p-8 text-center text-white">
            <h3 class="text-2xl font-bold mb-4">Tidak Menemukan yang Anda Cari?</h3>
            <p class="text-green-100 mb-6">Jelajahi semua produk atau gunakan pencarian untuk menemukan produk spesifik</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}"
                    class="bg-white text-gampong-primary px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    <i class="fas fa-shopping-basket mr-2"></i>Lihat Semua Produk
                </a>
                <a href="{{ route('products.index') }}"
                    class="border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-gampong-primary transition-colors">
                    <i class="fas fa-search mr-2"></i>Cari Produk
                </a>
            </div>
        </div>
    </div>

    <!-- Category Preview Modal -->
    <div id="categoryPreviewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75" onclick="closeCategoryPreview()"></div>
            </div>

            <div
                class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="closeCategoryPreview()"
                        class="bg-white rounded-md text-gray-400 hover:text-gray-600 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div id="categoryPreviewContent">
                    <!-- Content will be loaded via AJAX -->
                    <div class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                        <p class="text-gray-500 mt-2">Memuat pratinjau...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showCategoryPreview(slug) {
            const modal = document.getElementById('categoryPreviewModal');
            const content = document.getElementById('categoryPreviewContent');

            modal.classList.remove('hidden');

            // Load category details via AJAX
            fetch(`/categories/${slug}/details`)
                .then(response => response.json())
                .then(data => {
                    content.innerHTML = `
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-2xl font-bold text-gampong-primary mb-4">
                                    ${data.category.name}
                                </h3>

                                ${data.category.description ? `
                                            <p class="text-gray-600 mb-6">${data.category.description}</p>
                                        ` : ''}

                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                                        <div class="text-2xl font-bold text-gampong-primary">${data.total_products}</div>
                                        <div class="text-sm text-gray-600">Total Produk</div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                                        <div class="text-2xl font-bold text-gampong-primary">${data.sample_products.length}</div>
                                        <div class="text-sm text-gray-600">Pratinjau Produk</div>
                                    </div>
                                </div>

                                ${data.sample_products.length > 0 ? `
                                            <div class="mb-6">
                                                <h4 class="font-semibold text-lg mb-3">Contoh Produk:</h4>
                                                <div class="grid grid-cols-2 gap-3">
                                                    ${data.sample_products.map(product => `
                                                <div class="border rounded-lg p-3">
                                                    <h5 class="font-medium text-sm">${product.name}</h5>
                                                    <p class="text-xs text-gray-600">${product.user.name}</p>
                                                    <p class="text-sm font-bold text-gampong-primary">Rp ${new Intl.NumberFormat('id-ID').format(product.price)}</p>
                                                </div>
                                            `).join('')}
                                                </div>
                                            </div>
                                        ` : ''}

                                <div class="mt-6">
                                    <a href="/products?category=${data.category.slug}"
                                       class="w-full bg-gampong-primary text-white py-3 px-4 rounded-lg hover:bg-green-700 transition-colors inline-block text-center font-semibold">
                                        <i class="fas fa-shopping-basket mr-2"></i>Lihat Semua Produk
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    content.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-2xl text-red-400"></i>
                            <p class="text-red-500 mt-2">Gagal memuat pratinjau kategori</p>
                        </div>
                    `;
                });
        }

        function closeCategoryPreview() {
            document.getElementById('categoryPreviewModal').classList.add('hidden');
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCategoryPreview();
            }
        });
    </script>
@endsection
