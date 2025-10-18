@extends('layouts.app')

@section('title', 'Produk Minyak Jelantah Zynera - Marketplace Supplier Terpercaya')

@section('content')
    <!-- Compact Hero Section -->
    <section class="relative py-12 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-8 left-8 w-16 h-16 bg-emerald-200 rounded-full opacity-60 animate-pulse"></div>
        <div class="absolute top-20 right-16 w-24 h-24 bg-teal-200 rounded-full opacity-40 animate-bounce"></div>
        <div class="absolute bottom-8 left-1/4 w-12 h-12 bg-cyan-200 rounded-full opacity-50 animate-pulse"
            style="animation-delay: 1s;"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <!-- Badge -->
                <div
                    class="inline-flex items-center bg-white/80 backdrop-blur-sm border border-emerald-200 rounded-full px-4 py-2 mb-4 shadow-lg">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                    <span class="text-sm font-semibold text-emerald-700">🔥 Marketplace Zynera Terpercaya</span>
                </div>

                <!-- Main Title -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 leading-tight">
                    <span class="block text-slate-800">Jelajahi Produk</span>
                    <span
                        class="block bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 bg-clip-text text-transparent">Minyak Jelantah
                        Zynera</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-lg lg:text-xl text-slate-600 mb-6 leading-relaxed max-w-2xl mx-auto">
                    Temukan beragam minyak jelantah berkualitas premium langsung dari supplier terpercaya di platform Zynera
                    dengan kontrol kualitas ketat dan harga kompetitif.
                </p>

                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-w-2xl mx-auto">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-emerald-600 mb-1">{{ $products->total() }}+</div>
                        <div class="text-slate-600 text-xs">Produk Tersedia</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-teal-600 mb-1">{{ $categories->count() }}+</div>
                        <div class="text-slate-600 text-xs">Kategori</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-orange-600 mb-1">100%</div>
                        <div class="text-slate-600 text-xs">Fresh Quality</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Compact Filter Section dengan Toggle -->
    <section class="py-4 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="bg-gradient-to-r from-white via-emerald-50 to-white rounded-xl shadow-md border border-emerald-100 p-4">

                <!-- Header dengan Toggle Button -->
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center mr-3 shadow-md">
                            <span class="text-lg">🔍</span>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Filter & Pencarian Zynera</h2>
                            <p class="text-slate-600 text-sm">Temukan minyak jelantah yang Anda cari dengan mudah</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Hasil Pencarian Counter -->
                        <div class="hidden md:flex items-center text-xs text-slate-500">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                            {{ $products->total() }} produk ditemukan
                        </div>

                        <!-- Toggle Button -->
                        <button type="button" id="filterToggle"
                            class="flex items-center space-x-2 bg-white hover:bg-emerald-50 text-slate-700 px-4 py-2 rounded-lg border border-slate-200 hover:border-emerald-300 transition-all duration-300 shadow-sm hover:shadow-md">
                            <span id="filterToggleText" class="text-sm font-medium">Buka Filter</span>
                            <svg id="filterToggleIcon" class="w-4 h-4 transform rotate-0 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Filter Form - Collapsible -->
                <div id="filterForm" class="filter-content hidden">
                    <form method="GET" action="{{ route('products.index') }}" class="space-y-3">
                        <!-- Quick Search Bar -->
                        <div class="bg-white/70 rounded-lg p-3 border border-emerald-100">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                                <div class="lg:col-span-2">
                                    <label class="block text-xs font-medium text-slate-700 mb-1">
                                        <span class="flex items-center">
                                            <span class="mr-1">🔎</span>
                                            Cari Produk di Zynera
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            placeholder="Masukkan nama produk..."
                                            class="w-full px-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 transition-all duration-300 bg-white shadow-sm">
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                            <span class="text-slate-400 text-sm">⌨️</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">
                                        <span class="flex items-center">
                                            <span class="mr-1">📊</span>
                                            Urutkan
                                        </span>
                                    </label>
                                    <select name="sort"
                                        class="w-full px-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 transition-all duration-300 bg-white shadow-sm">
                                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru
                                        </option>
                                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                                            Harga Terendah</option>
                                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                                            Harga Tertinggi</option>
                                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating
                                            Tertinggi</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Advanced Filters -->
                        <div class="bg-white/70 rounded-lg p-3 border border-emerald-100">
                            <div class="grid grid-cols-1 gap-3">
                                <!-- Category Filter -->
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">
                                        <span class="flex items-center">
                                            <span class="mr-1">🏷️</span>
                                            Kategori
                                        </span>
                                    </label>
                                    <select name="category"
                                        class="w-full px-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-200 focus:border-emerald-500 transition-all duration-300 bg-white shadow-sm">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->slug }}"
                                                {{ request('category') == $category->slug ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 justify-center pt-2">
                            <button type="submit"
                                class="group bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-2.5 rounded-lg text-sm font-bold hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <span class="flex items-center justify-center">
                                    🔍 Cari Produk
                                    <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                            </button>

                            <a href="{{ route('products.index') }}"
                                class="group bg-slate-500 text-white px-6 py-2.5 rounded-lg text-sm font-bold hover:bg-slate-600 transition-all duration-300 shadow-md hover:shadow-lg text-center">
                                <span class="flex items-center justify-center">
                                    🔄 Reset Filter
                                    <svg class="ml-2 w-4 h-4 group-hover:rotate-180 transition-transform duration-500"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Filter Summary (Tampil ketika filter tersembunyi) -->
                <div id="filterSummary" class="">
                    <div class="flex flex-wrap items-center gap-2 text-sm">
                        <span class="text-slate-600 font-medium">Filter aktif:</span>
                        @if (request('search'))
                            <span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded-full text-xs font-medium">
                                📝 "{{ request('search') }}"
                            </span>
                        @endif
                        @if (request('category'))
                            @php
                                $selectedCategory = $categories->where('slug', request('category'))->first();
                            @endphp
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                🏷️ {{ $selectedCategory->name ?? 'Kategori' }}
                            </span>
                        @endif
                        @if (!request('search') && !request('category'))
                            <span class="text-slate-500 italic">Tidak ada filter aktif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Grid Section -->
    <section class="py-8 bg-gradient-to-br from-slate-50 to-emerald-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($products->count() > 0)
                <!-- Results Header -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-800 mb-2">Produk AgriConnect</h2>
                        <p class="text-sm sm:text-base text-slate-600 text-center">{{ $products->count() }}/{{ $products->total() }}
                            produk</p>
                    </div>
                    <div class="flex items-center mt-4 md:mt-0">
                        <span class="text-sm text-slate-500 mr-3">Halaman {{ $products->currentPage() }} dari
                            {{ $products->lastPage() }}</span>
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                    @foreach ($products as $product)
                        <div
                            class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2 hover:scale-105">
                            <!-- Product Image -->
                            <div class="relative h-56 overflow-hidden">
                                @if ($product->main_image_url)
                                    <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                        loading="lazy"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                    <!-- Fallback jika gambar error -->
                                    <div class="w-full h-full bg-gradient-to-br from-emerald-200 to-green-300 flex items-center justify-center group-hover:scale-110 transition-transform duration-500"
                                        style="display: none;">
                                        <div class="text-center">
                                            <span class="text-5xl opacity-80">
                                                @if ($product->category && str_contains($product->category->slug, 'sayur'))
                                                    🥬
                                                @elseif($product->category && str_contains($product->category->slug, 'buah'))
                                                    🍎
                                                @elseif($product->category && str_contains($product->category->slug, 'daging'))
                                                    🥩
                                                @else
                                                    🥒
                                                @endif
                                            </span>
                                            <p class="text-emerald-800 font-semibold mt-2 text-sm">
                                                {{ Str::limit($product->name, 20) }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-emerald-200 to-green-300 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                                        <div class="text-center">
                                            <span class="text-5xl opacity-80">
                                                @if ($product->category && str_contains($product->category->slug, 'sayur'))
                                                    🥬
                                                @elseif($product->category && str_contains($product->category->slug, 'buah'))
                                                    🍎
                                                @elseif($product->category && str_contains($product->category->slug, 'daging'))
                                                    🥩
                                                @else
                                                    🥒
                                                @endif
                                            </span>
                                            <p class="text-emerald-800 font-semibold mt-2 text-sm">
                                                {{ Str::limit($product->name, 20) }}</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Badges -->
                                <div class="absolute top-3 left-3 flex flex-col space-y-1">
                                    <span
                                        class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">FRESH</span>
                                    @if ($product->is_featured)
                                        <span
                                            class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">FEATURED</span>
                                    @endif
                                    @if ($product->has_images)
                                        <span
                                            class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">{{ count($product->image_urls) }}
                                            FOTO</span>
                                    @endif
                                </div>

                                <!-- Favorite Button -->
                                <button
                                    class="absolute top-3 right-3 w-10 h-10 bg-white/90 hover:bg-white rounded-xl flex items-center justify-center shadow-md transition-all duration-200 hover:scale-110 group">
                                    <svg class="w-4 h-4 text-slate-600 group-hover:text-red-500 transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                </button>

                                <!-- Stock Indicator -->
                                <div class="absolute bottom-3 left-3">
                                    @if ($product->stock > 10)
                                        <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Stok
                                            Banyak</span>
                                    @elseif($product->stock > 0)
                                        <span
                                            class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">Stok
                                            Terbatas</span>
                                    @else
                                        <span
                                            class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">Habis</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-3 sm:p-5">
                                <!-- Category & Rating -->
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-1 rounded-full">
                                        {{ $product->category->name ?? 'Produk' }}
                                    </span>
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-3 h-3 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                        <span
                                            class="text-xs font-semibold text-slate-600">{{ number_format($product->average_rating, 1) }}</span>
                                    </div>
                                </div>

                                <h3
                                    class="text-sm sm:text-lg font-bold text-slate-800 mb-2 group-hover:text-emerald-600 transition-colors leading-tight">
                                    {{ $product->name }}
                                </h3>

                                <p class="text-slate-600 text-xs sm:text-sm mb-3 line-clamp-2 leading-relaxed">
                                    {{ Str::limit($product->description, 50) }}
                                </p>

                                <!-- Location -->
                                @if ($product->village)
                                    <div class="flex items-center text-xs text-slate-500 mb-3">
                                        <span class="mr-1">📍</span>
                                        <span>{{ $product->village->name }}</span>
                                    </div>
                                @endif

                                <!-- Price & Actions -->
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-lg sm:text-xl font-bold text-emerald-600">Rp
                                            {{ number_format($product->price) }}</span>
                                        <span class="text-xs text-slate-500">per {{ $product->unit }}</span>
                                    </div>

                                    <a href="{{ route('products.show', $product->slug) }}"
                                        class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 flex items-center space-x-1 hover:scale-105 shadow-md hover:shadow-lg">
                                        <span>Detail</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    {{ $products->appends(request()->input())->links('custom.pagination') }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-emerald-200 to-teal-300 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <span class="text-4xl">🔍</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-3">Produk Tidak Ditemukan</h3>
                        <p class="text-slate-600 text-base mb-6 leading-relaxed">
                            Maaf, tidak ada produk yang sesuai dengan kriteria pencarian Anda di AgriConnect.
                            Coba ubah filter atau kata kunci pencarian.
                        </p>
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-3 rounded-xl text-base font-bold hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <span>🔄 Reset & Lihat Semua Produk</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Filter Functionality
            const filterToggle = document.getElementById('filterToggle');
            const filterForm = document.getElementById('filterForm');
            const filterSummary = document.getElementById('filterSummary');
            const filterToggleIcon = document.getElementById('filterToggleIcon');
            const filterToggleText = document.getElementById('filterToggleText');

            // Set initial state - filter terbuka by default
            let filterOpen = false;

            // Setup initial closed state
            filterForm.style.maxHeight = '0';
            filterForm.style.opacity = '0';
            filterForm.style.visibility = 'hidden';
            filterForm.classList.add('hidden');
            filterSummary.classList.remove('hidden');
            filterToggleIcon.style.transform = 'rotate(0deg)';
            filterToggleText.textContent = 'Buka Filter';

            filterToggle.addEventListener('click', function() {
                if (filterOpen) {
                    // Tutup filter
                    filterForm.style.maxHeight = filterForm.scrollHeight + 'px';
                    requestAnimationFrame(() => {
                        filterForm.style.maxHeight = '0';
                        filterForm.style.opacity = '0';
                        filterForm.style.visibility = 'hidden';
                    });

                    setTimeout(() => {
                        filterForm.classList.add('hidden');
                        filterSummary.classList.remove('hidden');
                    }, 300);

                    filterToggleIcon.style.transform = 'rotate(0deg)';
                    filterToggleText.textContent = 'Buka Filter';
                    filterOpen = false;
                } else {
                    // Buka filter
                    filterSummary.classList.add('hidden');
                    filterForm.classList.remove('hidden');
                    filterForm.style.visibility = 'visible';
                    filterForm.style.opacity = '0';
                    filterForm.style.maxHeight = '0';

                    requestAnimationFrame(() => {
                        filterForm.style.maxHeight = filterForm.scrollHeight + 'px';
                        filterForm.style.opacity = '1';
                    });

                    setTimeout(() => {
                        filterForm.style.maxHeight = 'none';
                    }, 300);

                    filterToggleIcon.style.transform = 'rotate(180deg)';
                    filterToggleText.textContent = 'Tutup Filter';
                    filterOpen = true;
                }
            });


            // Auto-close filter on mobile after search (optional UX enhancement)
            const searchForm = filterForm.querySelector('form');
            if (searchForm && window.innerWidth < 768) {
                searchForm.addEventListener('submit', function() {
                    setTimeout(() => {
                        filterToggle.click(); // Close filter after search on mobile
                    }, 100);
                });
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Filter Content Animation */
        .filter-content {
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top;
        }

        /* Line clamp for product descriptions */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Toggle button hover effects */
        #filterToggle:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Filter summary tags */
        .filter-tag {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Smooth transitions for all form elements */
        select,
        input[type="text"] {
            transition: all 0.2s ease-in-out;
        }

        select:focus,
        input[type="text"]:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #10b981, #0d9488);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #059669, #0f766e);
        }

        /* Mobile responsive improvements */
        @media (max-width: 768px) {
            .filter-content {
                margin-top: 0.5rem;
            }

            #filterToggle {
                font-size: 0.875rem;
                padding: 0.5rem 0.75rem;
            }
        }

        /* Enhanced button animations */
        button[type="submit"]:active {
            transform: translateY(0) scale(0.98);
        }

        .group:hover svg {
            animation: bounce 0.6s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-2px);
            }

            60% {
                transform: translateY(-1px);
            }
        }
    </style>
@endpush
