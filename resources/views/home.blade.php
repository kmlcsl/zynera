@extends('layouts.app')

@section('title', 'AgriConnect - Bahan Makanan Segar Langsung Dari Petani')

@section('content')
    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50"></div>

        <!-- Decorative Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-emerald-200 rounded-full opacity-60 animate-pulse"></div>
        <div class="absolute top-40 right-20 w-32 h-32 bg-teal-200 rounded-full opacity-40 animate-bounce"></div>
        <div class="absolute bottom-20 left-1/4 w-16 h-16 bg-cyan-200 rounded-full opacity-50 animate-pulse"
            style="animation-delay: 1s;"></div>

        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left">
                    <!-- Badge -->
                    <div
                        class="inline-flex items-center bg-white/80 backdrop-blur-sm border border-emerald-200 rounded-full px-6 py-3 mb-8 shadow-lg">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full mr-3 animate-pulse"></span>
                        <span class="text-sm font-semibold text-emerald-700">🚀 #UpStreamToDownStream</span>
                    </div>

                    <!-- Main Heading -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold mb-8 leading-tight">
                        <span class="block text-slate-800">Belanja</span>
                        <span
                            class="block bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 bg-clip-text text-transparent">Bahan
                            Segar</span>
                        <span class="block text-slate-700 text-3xl sm:text-4xl lg:text-5xl mt-2">Langsung dari Petani</span>
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-xl lg:text-2xl text-slate-600 mb-10 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Nikmati kesegaran produk organik berkualitas premium dengan
                        <span class="font-semibold text-emerald-600">pengiriman express</span>
                        dan jaminan 100% fresh ke rumah Anda.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-12">
                        @auth
                            <a href="{{ route('products.index') }}"
                                class="group bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-8 py-4 rounded-2xl text-lg font-bold hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                <span class="flex items-center justify-center">
                                    🛒 Mulai Belanja Sekarang
                                    <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </span>
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                                class="group bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-8 py-4 rounded-2xl text-lg font-bold hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                <span class="flex items-center justify-center">
                                    ✨ Daftar Gratis Sekarang
                                    <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </span>
                            </a>
                        @endauth

                        <a href="#kategori"
                            class="group bg-white text-slate-700 border-2 border-slate-300 px-8 py-4 rounded-2xl text-lg font-bold hover:bg-slate-50 hover:border-emerald-300 transition-all duration-300 shadow-lg">
                            <span class="flex items-center justify-center">
                                🔍 Jelajahi Produk
                                <svg class="ml-2 w-5 h-5 group-hover:translate-y-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                            </span>
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center lg:text-left">
                            <div class="text-3xl lg:text-4xl font-bold text-emerald-600 mb-1">120+</div>
                            <div class="text-slate-600 text-sm">Pelanggan Setia</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-3xl lg:text-4xl font-bold text-teal-600 mb-1">100%</div>
                            <div class="text-slate-600 text-sm">Produk Fresh</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-3xl lg:text-4xl font-bold text-cyan-600 mb-1">100+</div>
                            <div class="text-slate-600 text-sm">Partner Petani</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-3xl lg:text-4xl font-bold text-orange-600 mb-1">24/7</div>
                            <div class="text-slate-600 text-sm">Customer Support</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Hero Image -->
                <div class="relative">
                    <div class="relative z-10">
                        <!-- Main Image Container -->
                        <div
                            class="bg-white rounded-3xl shadow-2xl p-8 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                            <div
                                class="bg-gradient-to-br from-emerald-100 to-teal-100 rounded-2xl p-8 h-96 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="text-8xl mb-4">🥬</div>
                                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Sayuran Organik</h3>
                                    <p class="text-slate-600">Segar dari kebun</p>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Cards -->
                        <div
                            class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-xl p-4 transform -rotate-12 hover:rotate-0 transition-transform duration-300">
                            <div class="text-4xl mb-2">🍎</div>
                            <p class="text-sm font-semibold text-slate-700">Buah Segar</p>
                        </div>

                        <div
                            class="absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-xl p-4 transform rotate-12 hover:rotate-0 transition-transform duration-300">
                            <div class="text-4xl mb-2">🥩</div>
                            <p class="text-sm font-semibold text-slate-700">Daging Premium</p>
                        </div>
                    </div>

                    <!-- Background Decoration -->
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-emerald-200 to-teal-200 rounded-3xl transform rotate-6 opacity-20">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Indicators -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-slate-600 font-medium text-lg">Dipercaya oleh komunitas petani dan konsumen lokal</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="flex flex-col items-center p-6 bg-slate-50 rounded-2xl hover:bg-emerald-50 transition-colors">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mb-4">
                        <span class="text-2xl">🏆</span>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-2">Top Rated</h3>
                    <p class="text-slate-600 text-sm text-center">Rating 4.9/5 dari customer</p>
                </div>

                <div class="flex flex-col items-center p-6 bg-slate-50 rounded-2xl hover:bg-emerald-50 transition-colors">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-4">
                        <span class="text-2xl">⚡</span>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-2">Fast Delivery</h3>
                    <p class="text-slate-600 text-sm text-center">Pengiriman dalam 1-2 jam</p>
                </div>

                <div class="flex flex-col items-center p-6 bg-slate-50 rounded-2xl hover:bg-emerald-50 transition-colors">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-4">
                        <span class="text-2xl">🌱</span>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-2">100% Organic</h3>
                    <p class="text-slate-600 text-sm text-center">Produk organik bersertifikat</p>
                </div>

                <div class="flex flex-col items-center p-6 bg-slate-50 rounded-2xl hover:bg-emerald-50 transition-colors">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center mb-4">
                        <span class="text-2xl">🛡️</span>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-2">Guaranteed Fresh</h3>
                    <p class="text-slate-600 text-sm text-center">Jaminan kesegaran 100%</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="kategori" class="py-20 bg-gradient-to-br from-slate-50 to-emerald-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center bg-emerald-100 text-emerald-700 px-6 py-3 rounded-full text-sm font-bold mb-6">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-3 animate-pulse"></span>
                    🌾 Kategori Unggulan
                </div>
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-800 mb-6">
                    Pilih Kategori
                    <span class="block bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">Favorit
                        Anda</span>
                </h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                    Dari sayuran organik segar hingga protein berkualitas premium,
                    semua kebutuhan dapur keluarga tersedia dengan kualitas terbaik.
                </p>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @if (isset($categories) && $categories->count() > 0)
                    @foreach ($categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}"
                            class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden transform hover:-translate-y-3 hover:scale-105">
                            @php
                                $gradient = match ($category->slug) {
                                    'sayuran' => 'from-emerald-400 to-green-600',
                                    'buah-buahan' => 'from-orange-400 to-red-500',
                                    'daging' => 'from-red-500 to-pink-600',
                                    default => 'from-blue-400 to-cyan-600',
                                };
                                $iconGradient = match ($category->slug) {
                                    'sayuran' => 'from-emerald-100 to-green-100',
                                    'buah-buahan' => 'from-orange-100 to-red-100',
                                    'daging' => 'from-red-100 to-pink-100',
                                    default => 'from-blue-100 to-cyan-100',
                                };
                            @endphp

                            <!-- Background Gradient -->
                            <div
                                class="absolute inset-0 bg-gradient-to-br {{ $gradient }} opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            </div>

                            <!-- Content -->
                            <div class="relative z-10 p-8 text-center">
                                <!-- Icon -->
                                <div
                                    class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br {{ $iconGradient }} rounded-3xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                    <span class="text-5xl">
                                        @if ($category->slug === 'sayuran')
                                            🥬
                                        @elseif($category->slug === 'buah-buahan')
                                            🍎
                                        @elseif($category->slug === 'daging')
                                            🥩
                                        @else
                                            🐟
                                        @endif
                                    </span>
                                </div>

                                <!-- Title -->
                                <h3
                                    class="text-2xl font-bold text-slate-800 group-hover:text-white mb-4 transition-colors duration-300">
                                    {{ $category->name }}
                                </h3>

                                <!-- Description -->
                                <p
                                    class="text-slate-600 group-hover:text-white/90 mb-6 transition-colors duration-300 leading-relaxed">
                                    {{ $category->description ?? 'Kategori produk segar berkualitas tinggi' }}
                                </p>

                                <!-- CTA -->
                                <div
                                    class="inline-flex items-center text-emerald-600 group-hover:text-white font-bold transition-colors duration-300">
                                    <span>Jelajahi Produk</span>
                                    <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Decorative Elements -->
                            <div
                                class="absolute top-4 right-4 w-3 h-3 bg-white/30 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                            <div class="absolute bottom-4 left-4 w-2 h-2 bg-white/20 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                style="animation-delay: 0.1s;"></div>
                        </a>
                    @endforeach
                @else
                    <!-- Default Categories -->
                    <a href="{{ route('products.index') }}?category=sayuran"
                        class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden transform hover:-translate-y-3 hover:scale-105">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-emerald-400 to-green-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <div class="relative z-10 p-8 text-center">
                            <div
                                class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-emerald-100 to-green-100 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <span class="text-5xl">🥬</span>
                            </div>
                            <h3
                                class="text-2xl font-bold text-slate-800 group-hover:text-white mb-4 transition-colors duration-300">
                                Sayuran Segar</h3>
                            <p
                                class="text-slate-600 group-hover:text-white/90 mb-6 transition-colors duration-300 leading-relaxed">
                                Sayuran organik segar langsung dari kebun</p>
                            <div
                                class="inline-flex items-center text-emerald-600 group-hover:text-white font-bold transition-colors duration-300">
                                <span>Jelajahi Produk</span>
                                <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('products.index') }}?category=buah"
                        class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden transform hover:-translate-y-3 hover:scale-105">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-orange-400 to-red-500 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <div class="relative z-10 p-8 text-center">
                            <div
                                class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-orange-100 to-red-100 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <span class="text-5xl">🍎</span>
                            </div>
                            <h3
                                class="text-2xl font-bold text-slate-800 group-hover:text-white mb-4 transition-colors duration-300">
                                Buah-buahan</h3>
                            <p
                                class="text-slate-600 group-hover:text-white/90 mb-6 transition-colors duration-300 leading-relaxed">
                                Buah segar manis dan bergizi tinggi</p>
                            <div
                                class="inline-flex items-center text-emerald-600 group-hover:text-white font-bold transition-colors duration-300">
                                <span>Jelajahi Produk</span>
                                <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('products.index') }}?category=daging"
                        class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden transform hover:-translate-y-3 hover:scale-105">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-red-500 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <div class="relative z-10 p-8 text-center">
                            <div
                                class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-red-100 to-pink-100 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <span class="text-5xl">🥩</span>
                            </div>
                            <h3
                                class="text-2xl font-bold text-slate-800 group-hover:text-white mb-4 transition-colors duration-300">
                                Daging & Protein</h3>
                            <p
                                class="text-slate-600 group-hover:text-white/90 mb-6 transition-colors duration-300 leading-relaxed">
                                Daging segar berkualitas premium</p>
                            <div
                                class="inline-flex items-center text-emerald-600 group-hover:text-white font-bold transition-colors duration-300">
                                <span>Jelajahi Produk</span>
                                <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('products.index') }}?category=seafood"
                        class="group relative bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden transform hover:-translate-y-3 hover:scale-105">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-blue-400 to-cyan-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <div class="relative z-10 p-8 text-center">
                            <div
                                class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-cyan-100 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <span class="text-5xl">🐟</span>
                            </div>
                            <h3
                                class="text-2xl font-bold text-slate-800 group-hover:text-white mb-4 transition-colors duration-300">
                                Seafood</h3>
                            <p
                                class="text-slate-600 group-hover:text-white/90 mb-6 transition-colors duration-300 leading-relaxed">
                                Hasil laut segar kaya protein</p>
                            <div
                                class="inline-flex items-center text-emerald-600 group-hover:text-white font-bold transition-colors duration-300">
                                <span>Jelajahi Produk</span>
                                <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center bg-orange-100 text-orange-700 px-6 py-3 rounded-full text-sm font-bold mb-6">
                    <span class="w-2 h-2 bg-orange-500 rounded-full mr-3 animate-pulse"></span>
                    ⭐ Produk Terlaris
                </div>
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-800 mb-6">
                    Produk
                    <span class="bg-gradient-to-r from-orange-500 to-red-500 bg-clip-text text-transparent">Unggulan</span>
                </h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                    Produk-produk terbaik dan terlaris yang dipilih khusus berdasarkan
                    review dan rating tertinggi dari customer setia kami.
                </p>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @if (isset($featuredProducts) && $featuredProducts->count() > 0)
                    @foreach ($featuredProducts as $product)
                        <div
                            class="group bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                            <!-- Product Image -->
                            <div class="relative h-64 overflow-hidden">
                                @if ($product->main_image_url)
                                    <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}"
                                        class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500"
                                        loading="lazy"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                    <!-- Fallback Image -->
                                    <div class="h-full w-full bg-gradient-to-br from-emerald-200 to-green-300 flex items-center justify-center group-hover:scale-110 transition-transform duration-500"
                                        style="display: none;">
                                        <div class="text-center">
                                            <span class="text-6xl opacity-80">
                                                @if ($product->category && str_contains(strtolower($product->category->slug ?? ''), 'sayur'))
                                                    🥬
                                                @elseif($product->category && str_contains(strtolower($product->category->slug ?? ''), 'buah'))
                                                    🍎
                                                @elseif($product->category && str_contains(strtolower($product->category->slug ?? ''), 'daging'))
                                                    🥩
                                                @elseif($product->category && str_contains(strtolower($product->category->slug ?? ''), 'seafood'))
                                                    🐟
                                                @else
                                                    🥒
                                                @endif
                                            </span>
                                            <p class="text-emerald-800 font-semibold mt-2 text-sm">
                                                {{ Str::limit($product->name, 20) }}</p>
                                        </div>
                                    </div>
                                @elseif($product->images && is_array($product->images) && count($product->images) > 0)
                                    <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}"
                                        class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-500"
                                        loading="lazy"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                    <!-- Fallback for alternative image -->
                                    <div class="h-full w-full bg-gradient-to-br from-emerald-200 to-green-300 flex items-center justify-center group-hover:scale-110 transition-transform duration-500"
                                        style="display: none;">
                                        <div class="text-center">
                                            <span class="text-6xl opacity-80">
                                                @if ($product->category && str_contains(strtolower($product->category->slug ?? ''), 'sayur'))
                                                    🥬
                                                @elseif($product->category && str_contains(strtolower($product->category->slug ?? ''), 'buah'))
                                                    🍎
                                                @elseif($product->category && str_contains(strtolower($product->category->slug ?? ''), 'daging'))
                                                    🥩
                                                @elseif($product->category && str_contains(strtolower($product->category->slug ?? ''), 'seafood'))
                                                    🐟
                                                @else
                                                    🥒
                                                @endif
                                            </span>
                                            <p class="text-emerald-800 font-semibold mt-2 text-sm">
                                                {{ Str::limit($product->name, 20) }}</p>
                                        </div>
                                    </div>
                                @else
                                    <!-- Default placeholder -->
                                    <div
                                        class="h-full w-full bg-gradient-to-br from-emerald-200 to-green-300 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                                        <div class="text-center">
                                            <span class="text-6xl opacity-80">
                                                @if ($product->category && str_contains(strtolower($product->category->slug ?? ''), 'sayur'))
                                                    🥬
                                                @elseif($product->category && str_contains(strtolower($product->category->slug ?? ''), 'buah'))
                                                    🍎
                                                @elseif($product->category && str_contains(strtolower($product->category->slug ?? ''), 'daging'))
                                                    🥩
                                                @elseif($product->category && str_contains(strtolower($product->category->slug ?? ''), 'seafood'))
                                                    🐟
                                                @else
                                                    🥒
                                                @endif
                                            </span>
                                            <p class="text-emerald-800 font-semibold mt-2 text-sm">
                                                {{ Str::limit($product->name, 20) }}</p>
                                            <p class="text-emerald-600 text-xs mt-1">Gambar akan segera tersedia</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Badges -->
                                <div class="absolute top-4 left-4 flex flex-col space-y-2">
                                    <span
                                        class="bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">FRESH</span>
                                    @if ($loop->index < 2)
                                        <span
                                            class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">BEST
                                            SELLER</span>
                                    @endif
                                    @if ($product->has_images)
                                        <span
                                            class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                            {{ is_array($product->images) ? count($product->images) : 0 }} FOTO
                                        </span>
                                    @endif
                                </div>

                                <!-- Favorite Button -->
                                <button
                                    class="absolute top-4 right-4 w-12 h-12 bg-white/90 hover:bg-white rounded-2xl flex items-center justify-center shadow-lg transition-all duration-200 hover:scale-110 group">
                                    <svg class="w-5 h-5 text-slate-600 group-hover:text-red-500 transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Product Info -->
                            <div class="p-6">
                                <!-- Category & Rating -->
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                                        {{ $product->category->name ?? 'Produk' }}
                                    </span>
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                        <span class="text-sm font-semibold text-slate-600">4.{{ $loop->index + 6 }}</span>
                                    </div>
                                </div>

                                <!-- Product Name -->
                                <h3
                                    class="text-xl font-bold text-slate-800 mb-3 group-hover:text-emerald-600 transition-colors leading-tight">
                                    {{ $product->name }}
                                </h3>

                                <!-- Description -->
                                <p class="text-slate-600 text-sm mb-6 line-clamp-2 leading-relaxed">
                                    {{ Str::limit($product->description, 80) }}
                                </p>

                                <!-- Price & Actions -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex flex-col">
                                        <span class="text-2xl font-bold text-emerald-600">Rp
                                            {{ number_format($product->price) }}</span>
                                        <span class="text-sm text-slate-500">per {{ $product->unit }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-3">
                                    <!-- Detail Button -->
                                    <a href="{{ route('products.show', $product->slug) }}"
                                        class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-800 px-4 py-3 rounded-2xl font-bold transition-all duration-200 flex items-center justify-center space-x-2 hover:scale-105 shadow-md hover:shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <span>Detail</span>
                                    </a>

                                    <!-- Add to Cart Button -->
                                    @auth
                                        <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit"
                                                class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-4 py-3 rounded-2xl font-bold transition-all duration-200 flex items-center justify-center space-x-2 hover:scale-105 shadow-lg hover:shadow-xl">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9">
                                                    </path>
                                                </svg>
                                                <span>Add</span>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}"
                                            class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-4 py-3 rounded-2xl font-bold transition-all duration-200 flex items-center justify-center space-x-2 hover:scale-105 shadow-lg hover:shadow-xl">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9">
                                                </path>
                                            </svg>
                                            <span>Add</span>
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center py-12">
                        <div class="text-6xl mb-4">🛒</div>
                        <p class="text-slate-500 text-lg">Tidak ada produk yang tersedia saat ini.</p>
                    </div>
                @endif
            </div>

            <!-- View All Products Button -->
            <div class="text-center mt-16">
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-10 py-5 rounded-2xl text-lg font-bold transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                    <span>Lihat Semua Produk</span>
                    <svg class="ml-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-20 bg-gradient-to-br from-slate-50 to-emerald-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center bg-blue-100 text-blue-700 px-6 py-3 rounded-full text-sm font-bold mb-6">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-3 animate-pulse"></span>
                    💎 Keunggulan Kami
                </div>
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-800 mb-6">
                    Mengapa Pilih
                    <span
                        class="block bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">AgriConnect?</span>
                </h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                    Kami berkomitmen memberikan pengalaman belanja terbaik dengan inovasi teknologi
                    dan layanan prima untuk kepuasan pelanggan.
                </p>
            </div>

            <!-- Main Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div
                    class="group text-center p-8 bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3">
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-emerald-400 to-green-600 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-xl">
                        <span class="text-4xl">🚀</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Pengiriman Express</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Produk fresh diantar dalam 1-2 jam dengan sistem cold chain technology
                        untuk menjaga kesegaran optimal hingga sampai di rumah Anda.
                    </p>
                </div>

                <div
                    class="group text-center p-8 bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3">
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-blue-400 to-cyan-600 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-xl">
                        <span class="text-4xl">🌱</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">100% Organik & Fresh</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Semua produk dipilih langsung dari petani organik bersertifikat dengan
                        standar kualitas internasional tanpa pestisida berbahaya.
                    </p>
                </div>

                <div
                    class="group text-center p-8 bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3">
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-purple-400 to-pink-600 rounded-3xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-xl">
                        <span class="text-4xl">💰</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Harga Terjangkau</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Dapatkan produk berkualitas premium dengan harga kompetitif langsung
                        dari sumbernya tanpa markup berlebihan dari middleman.
                    </p>
                </div>
            </div>

            <!-- Additional Features -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🛡️</span>
                    </div>
                    <h4 class="font-bold text-slate-800 mb-2">Garansi Fresh</h4>
                    <p class="text-slate-600 text-sm">100% garansi kesegaran atau uang kembali</p>
                </div>

                <div class="text-center p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">📱</span>
                    </div>
                    <h4 class="font-bold text-slate-800 mb-2">Easy Shopping</h4>
                    <p class="text-slate-600 text-sm">Interface mudah digunakan di semua device</p>
                </div>

                <div class="text-center p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🏆</span>
                    </div>
                    <h4 class="font-bold text-slate-800 mb-2">Premium Quality</h4>
                    <p class="text-slate-600 text-sm">Produk grade A pilihan terbaik</p>
                </div>

                <div class="text-center p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🤝</span>
                    </div>
                    <h4 class="font-bold text-slate-800 mb-2">24/7 Support</h4>
                    <p class="text-slate-600 text-sm">Customer service responsif kapan saja</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center bg-pink-100 text-pink-700 px-6 py-3 rounded-full text-sm font-bold mb-6">
                    <span class="w-2 h-2 bg-pink-500 rounded-full mr-3 animate-pulse"></span>
                    💬 Testimoni Pelanggan
                </div>
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-800 mb-6">
                    Apa Kata
                    <span class="bg-gradient-to-r from-pink-600 to-red-600 bg-clip-text text-transparent">Pelanggan
                        Kami?</span>
                </h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                    Dengarkan pengalaman nyata dari ribuan pelanggan setia yang telah merasakan
                    kualitas produk dan layanan terbaik dari AgriConnect.
                </p>
            </div>

            <!-- Testimonials Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div
                    class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-3xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            S
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="font-bold text-slate-800 text-lg">Sari Dewi</h4>
                            <p class="text-slate-600 text-sm">Ibu Rumah Tangga</p>
                        </div>
                        <div class="flex space-x-1">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <blockquote class="text-slate-700 italic leading-relaxed">
                        "Sayuran yang diantar selalu segar dan berkualitas. Pengiriman cepat dan packaging rapi.
                        Sudah langganan 6 bulan dan sangat puas dengan pelayanannya!"
                    </blockquote>
                </div>

                <!-- Testimonial 2 -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-3xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            B
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="font-bold text-slate-800 text-lg">Budi Santoso</h4>
                            <p class="text-slate-600 text-sm">Chef Restaurant</p>
                        </div>
                        <div class="flex space-x-1">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <blockquote class="text-slate-700 italic leading-relaxed">
                        "Untuk kebutuhan restaurant, saya selalu order di AgriConnect. Kualitas bahan baku premium
                        dengan harga yang sangat kompetitif. Highly recommended!"
                    </blockquote>
                </div>

                <!-- Testimonial 3 -->
                <div
                    class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-3xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            L
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="font-bold text-slate-800 text-lg">Lisa Amanda</h4>
                            <p class="text-slate-600 text-sm">Working Mom</p>
                        </div>
                        <div class="flex space-x-1">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <blockquote class="text-slate-700 italic leading-relaxed">
                        "Sebagai working mom, AgriConnect sangat membantu. Bisa order kapan saja,
                        diantar cepat, dan tidak perlu repot ke pasar lagi. Love it!"
                    </blockquote>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative py-20 bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 text-white overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60"
                height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="%23ffffff"
                fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="20" /%3E%3Ccircle cx="10"
                cy="10" r="10" /%3E%3Ccircle cx="50" cy="50" r="10" /%3E%3C/g%3E%3C/svg%3E');">
            </div>
        </div>

        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-pulse"></div>
        <div class="absolute bottom-20 right-20 w-32 h-32 bg-white/5 rounded-full animate-bounce"></div>
        <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-white/10 rounded-full animate-pulse"
            style="animation-delay: 1s;"></div>

        <div class="relative z-10 max-w-5xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <!-- Badge -->
            <div
                class="inline-flex items-center bg-white/20 backdrop-blur-sm border border-white/30 px-8 py-4 rounded-full text-sm font-bold mb-8">
                <span class="w-2 h-2 bg-white rounded-full mr-3 animate-pulse"></span>
                🎉 Bergabung dengan 50,000+ Pelanggan Bahagia
            </div>

            <!-- Main Heading -->
            <h2 class="text-4xl sm:text-5xl lg:text-7xl font-bold mb-8 leading-tight">
                <span class="block">Siap Mulai Hidup</span>
                <span class="block bg-gradient-to-r from-yellow-200 to-orange-300 bg-clip-text text-transparent">Lebih
                    Sehat?</span>
            </h2>

            <!-- Subtitle -->
            <p class="text-xl lg:text-2xl text-emerald-100 mb-12 leading-relaxed max-w-4xl mx-auto">
                Bergabunglah dengan ribuan pelanggan yang sudah merasakan kemudahan berbelanja
                produk segar berkualitas di AgriConnect. Mulai hidup sehat dari sekarang juga!
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mb-12">
                @auth
                    <a href="{{ route('products.index') }}"
                        class="group bg-white text-emerald-600 px-10 py-5 rounded-2xl text-xl font-bold hover:bg-emerald-50 transition-all duration-300 shadow-2xl hover:shadow-3xl transform hover:-translate-y-1">
                        <span class="flex items-center">
                            🛒 Mulai Belanja Sekarang
                            <svg class="ml-3 w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </span>
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="group bg-white text-emerald-600 px-10 py-5 rounded-2xl text-xl font-bold hover:bg-emerald-50 transition-all duration-300 shadow-2xl hover:shadow-3xl transform hover:-translate-y-1">
                        <span class="flex items-center">
                            ✨ Daftar Gratis Sekarang
                            <svg class="ml-3 w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </span>
                    </a>
                @endauth

                <a href="/about"
                    class="group border-2 border-white text-white px-10 py-5 rounded-2xl text-xl font-bold hover:bg-white hover:text-emerald-600 transition-all duration-300">
                    <span class="flex items-center">
                        📖 Pelajari Lebih Lanjut
                        <svg class="ml-3 w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </span>
                </a>
            </div>

            <!-- Quick Contact -->
            <div class="text-center">
                <p class="text-emerald-100 mb-6 text-lg">Butuh bantuan? Hubungi customer service kami</p>
                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-8">
                    <a href="tel:+6281234567890"
                        class="flex items-center justify-center text-white hover:text-yellow-200 transition-colors group">
                        <div
                            class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                            <span class="text-xl">📞</span>
                        </div>
                        <div class="text-left">
                            <p class="font-semibold text-lg">+62 812-3456-7890</p>
                            <p class="text-emerald-200 text-sm">Telepon & WhatsApp</p>
                        </div>
                    </a>

                    <a href="mailto:support@agriconnect.com"
                        class="flex items-center justify-center text-white hover:text-yellow-200 transition-colors group">
                        <div
                            class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                            <span class="text-xl">📧</span>
                        </div>
                        <div class="text-left">
                            <p class="font-semibold text-lg">support@agriconnect.com</p>
                            <p class="text-emerald-200 text-sm">Email Support</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
