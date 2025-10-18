@extends('layouts.app')

@section('title', 'Zynera - Platform Minyak Jelantah Berkualitas')

@push('styles')
<style>
    body {
        font-family: 'Inter', sans-serif;
    }
    .hero-gradient {
        background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
    }
    .card-hover {
        transition: all 0.3s ease;
        transform: translateY(0);
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero-gradient text-white relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-10 right-10 w-32 h-32 bg-white/10 rounded-full opacity-50 animate-pulse"></div>
        <div class="absolute bottom-20 left-20 w-24 h-24 bg-white/5 rounded-full opacity-60 animate-bounce"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left">
                    <!-- Badge -->
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm border border-white/30 rounded-full px-6 py-3 mb-8">
                        <span class="w-2 h-2 bg-white rounded-full mr-3 animate-pulse"></span>
                        <span class="text-sm font-semibold text-white">🛢️ Platform Minyak Jelantah #1</span>
                    </div>

                    <!-- Main Heading -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-8 leading-tight">
                        <span class="block text-white">Jual Beli</span>
                        <span class="block text-yellow-300">Minyak Jelantah</span>
                        <span class="block text-white/90 text-3xl sm:text-4xl lg:text-5xl mt-2">Berkualitas Tinggi</span>
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-xl lg:text-2xl text-white/90 mb-10 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                        Platform marketplace terpercaya untuk
                        <span class="font-semibold text-yellow-300">minyak jelantah berkualitas</span>
                        dengan sistem kualitas kontrol ketat dan pengiriman aman.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-12">
                        @auth
                            <a href="{{ route('products.index') }}"
                                class="group bg-white text-green-600 px-8 py-4 rounded-2xl text-lg font-bold hover:bg-yellow-50 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                <span class="flex items-center justify-center">
                                    🛢️ Lihat Produk Minyak
                                    <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </span>
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                                class="group bg-white text-green-600 px-8 py-4 rounded-2xl text-lg font-bold hover:bg-yellow-50 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                <span class="flex items-center justify-center">
                                    ✨ Bergabung Sekarang
                                    <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </span>
                            </a>
                        @endauth

                        <a href="#produk"
                            class="group bg-transparent text-white border-2 border-white px-8 py-4 rounded-2xl text-lg font-bold hover:bg-white hover:text-green-600 transition-all duration-300">
                            <span class="flex items-center justify-center">
                                🔍 Jelajahi Kategori
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
                            <div class="text-3xl lg:text-4xl font-bold text-yellow-300 mb-1">{{ $farmerPartners }}+</div>
                            <div class="text-white/80 text-sm">Supplier Terpercaya</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-3xl lg:text-4xl font-bold text-yellow-300 mb-1">{{ $availableProducts }}+</div>
                            <div class="text-white/80 text-sm">Produk Tersedia</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-3xl lg:text-4xl font-bold text-yellow-300 mb-1">{{ $satisfiedCustomers }}+</div>
                            <div class="text-white/80 text-sm">Pelanggan Puas</div>
                        </div>
                        <div class="text-center lg:text-left">
                            <div class="text-3xl lg:text-4xl font-bold text-yellow-300 mb-1">24/7</div>
                            <div class="text-white/80 text-sm">Customer Support</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Hero Dashboard Preview -->
                <div class="relative">
                    <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 shadow-2xl">
                        <!-- Dashboard Stats Cards -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-white rounded-2xl p-4 text-center card-hover">
                                <div class="text-3xl mb-2">🛢️</div>
                                <h3 class="text-lg font-bold text-gray-900">Grade A</h3>
                                <p class="text-sm text-gray-600">Premium Quality</p>
                                <p class="text-green-600 font-bold">Rp 45,000/20L</p>
                            </div>
                            <div class="bg-white rounded-2xl p-4 text-center card-hover">
                                <div class="text-3xl mb-2">🛢️</div>
                                <h3 class="text-lg font-bold text-gray-900">Grade B</h3>
                                <p class="text-sm text-gray-600">Standard Quality</p>
                                <p class="text-green-600 font-bold">Rp 35,000/25L</p>
                            </div>
                        </div>
                        
                        <!-- Quality Indicators -->
                        <div class="bg-white/20 rounded-2xl p-4 text-center">
                            <h3 class="text-xl font-bold text-white mb-2">Kontrol Kualitas Ketat</h3>
                            <div class="flex justify-center space-x-4 text-sm text-white/90">
                                <span>✓ Filtered</span>
                                <span>✓ Tested</span>
                                <span>✓ Certified</span>
                            </div>
                        </div>
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
    <section id="produk" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center bg-green-100 text-green-700 px-6 py-3 rounded-full text-sm font-bold mb-6">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3 animate-pulse"></span>
                    🛢️ Grade Minyak Jelantah
                </div>
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                    Pilih Grade
                    <span class="block bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Sesuai Kebutuhan</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Dari minyak jelantah premium grade A hingga raw material untuk industri besar,
                    semua tersedia dengan jaminan kualitas dan sertifikasi standar internasional.
                </p>
            </div>

            <!-- Oil Grades Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @if (isset($categories) && $categories->count() > 0)
                    @foreach ($categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}"
                            class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden card-hover">
                            @php
                                $gradient = match (true) {
                                    str_contains(strtolower($category->name), 'grade a') => 'from-green-400 to-emerald-600',
                                    str_contains(strtolower($category->name), 'grade b') => 'from-blue-400 to-cyan-600', 
                                    str_contains(strtolower($category->name), 'mentah') => 'from-yellow-400 to-orange-600',
                                    default => 'from-gray-400 to-gray-600',
                                };
                            @endphp

                            <!-- Header Gradient -->
                            <div class="bg-gradient-to-r {{ $gradient }} p-6 text-white">
                                <div class="text-4xl mb-3">🛢️</div>
                                <h3 class="text-2xl font-bold mb-2">{{ $category->name }}</h3>
                                <p class="text-white/90 text-sm">{{ $category->description }}</p>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <div class="mb-4">
                                    <h4 class="font-bold text-gray-900 mb-2">Karakteristik:</h4>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        @if(str_contains(strtolower($category->name), 'grade a'))
                                            <li>✓ Sudah difilter berkali-kali</li>
                                            <li>✓ Bebas kontaminan</li>
                                            <li>✓ Siap untuk biodiesel premium</li>
                                        @elseif(str_contains(strtolower($category->name), 'grade b'))
                                            <li>✓ Kualitas standard</li>
                                            <li>✓ Cocok untuk industri</li>
                                            <li>✓ Harga ekonomis</li>
                                        @else
                                            <li>✓ Bahan mentah</li>
                                            <li>✓ Perlu pengolahan lanjut</li>
                                            <li>✓ Harga paling kompetitif</li>
                                        @endif
                                    </ul>
                                </div>
                                
                                <div class="inline-flex items-center text-green-600 font-bold">
                                    <span>Lihat Produk</span>
                                    <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <!-- Default Oil Grades -->
                    <a href="{{ route('products.index') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden card-hover">
                        <div class="bg-gradient-to-r from-green-400 to-emerald-600 p-6 text-white">
                            <div class="text-4xl mb-3">🛢️</div>
                            <h3 class="text-2xl font-bold mb-2">Minyak Jelantah Grade A</h3>
                            <p class="text-white/90 text-sm">Premium quality, sudah difilter</p>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="font-bold text-gray-900 mb-2">Karakteristik:</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>✓ Sudah difilter berkali-kali</li>
                                    <li>✓ Bebas kontaminan</li>
                                    <li>✓ Siap untuk biodiesel premium</li>
                                </ul>
                            </div>
                            <div class="inline-flex items-center text-green-600 font-bold">
                                <span>Lihat Produk</span>
                                <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('products.index') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden card-hover">
                        <div class="bg-gradient-to-r from-blue-400 to-cyan-600 p-6 text-white">
                            <div class="text-4xl mb-3">🛢️</div>
                            <h3 class="text-2xl font-bold mb-2">Minyak Jelantah Grade B</h3>
                            <p class="text-white/90 text-sm">Standard quality, cocok industri</p>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="font-bold text-gray-900 mb-2">Karakteristik:</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>✓ Kualitas standard</li>
                                    <li>✓ Cocok untuk industri</li>
                                    <li>✓ Harga ekonomis</li>
                                </ul>
                            </div>
                            <div class="inline-flex items-center text-green-600 font-bold">
                                <span>Lihat Produk</span>
                                <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('products.index') }}" class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden card-hover">
                        <div class="bg-gradient-to-r from-yellow-400 to-orange-600 p-6 text-white">
                            <div class="text-4xl mb-3">🛢️</div>
                            <h3 class="text-2xl font-bold mb-2">Minyak Jelantah Mentah</h3>
                            <p class="text-white/90 text-sm">Raw material, harga kompetitif</p>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="font-bold text-gray-900 mb-2">Karakteristik:</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>✓ Bahan mentah</li>
                                    <li>✓ Perlu pengolahan lanjut</li>
                                    <li>✓ Harga paling kompetitif</li>
                                </ul>
                            </div>
                            <div class="inline-flex items-center text-green-600 font-bold">
                                <span>Lihat Produk</span>
                                <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
@endif
            </div>
        </div>
    </section>

    <!-- Quality Assurance Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Jaminan Kualitas Zynera</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Setiap tetes minyak jelantah melalui proses kontrol kualitas ketat untuk memastikan standar terbaik</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center p-6 bg-green-50 rounded-2xl card-hover">
                    <div class="w-16 h-16 bg-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl text-white">🔍</span>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Inspeksi Ketat</h3>
                    <p class="text-gray-600 text-sm">Setiap batch minyak diinspeksi secara detail</p>
                </div>
                
                <div class="text-center p-6 bg-blue-50 rounded-2xl card-hover">
                    <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl text-white">⚙️</span>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Proses Filterasi</h3>
                    <p class="text-gray-600 text-sm">Teknologi filtering modern untuk kemurnian optimal</p>
                </div>
                
                <div class="text-center p-6 bg-yellow-50 rounded-2xl card-hover">
                    <div class="w-16 h-16 bg-yellow-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl text-white">🏆</span>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Sertifikasi</h3>
                    <p class="text-gray-600 text-sm">Bersertifikat ISO dan standar lingkungan</p>
                </div>
                
                <div class="text-center p-6 bg-purple-50 rounded-2xl card-hover">
                    <div class="w-16 h-16 bg-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl text-white">🚚</span>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Pengiriman Aman</h3>
                    <p class="text-gray-600 text-sm">Kemasan khusus dan logistik terpercaya</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="hero-gradient py-16 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl font-bold mb-6">Siap Bergabung dengan Zynera?</h2>
                <p class="text-xl mb-8 opacity-90">Dapatkan minyak jelantah berkualitas premium dengan harga terbaik. Daftar sekarang dan nikmati kemudahan bertransaksi.</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-white text-green-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 shadow-lg">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('products.index') }}" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-green-600 transition-all duration-300">
                        Lihat Produk
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="text-2xl font-bold text-green-400 mb-4">Zynera</div>
                <p class="text-gray-400 mb-6">Platform terpercaya untuk minyak jelantah berkualitas premium</p>
                <div class="flex justify-center space-x-6 text-gray-400">
                    <span>© {{ date('Y') }} Zynera. All rights reserved.</span>
                </div>
            </div>
        </div>
    </footer>

</div>

@endsection
