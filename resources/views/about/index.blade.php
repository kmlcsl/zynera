@extends('layouts.app')

@section('title', 'Tentang Kami - AgriConnect')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-emerald-200 rounded-full opacity-30 animate-pulse"></div>
        <div class="absolute top-32 right-20 w-16 h-16 bg-teal-200 rounded-full opacity-40 animate-bounce"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <!-- Badge -->
                <div
                    class="inline-flex items-center bg-white/80 backdrop-blur-sm border border-emerald-200 rounded-full px-6 py-3 mb-6 shadow-lg">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-3 animate-pulse"></span>
                    <span class="text-sm font-semibold text-emerald-700">🌾 Tentang AgriConnect</span>
                </div>

                <!-- Main Title -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    <span class="block text-slate-800">Menghubungkan</span>
                    <span
                        class="block bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 bg-clip-text text-transparent">Petani
                        & Konsumen</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-xl lg:text-2xl text-slate-600 mb-8 leading-relaxed max-w-3xl mx-auto">
                    Platform e-commerce yang menghubungkan petani lokal dengan konsumen untuk produk segar berkualitas
                    premium.
                </p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-emerald-600 mb-2">100+</div>
                    <div class="text-slate-600">Petani Mitra</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-teal-600 mb-2">150+</div>
                    <div class="text-slate-600">Konsumen Aktif</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-cyan-600 mb-2">500+</div>
                    <div class="text-slate-600">Produk Terjual</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-orange-600 mb-2">10</div>
                    <div class="text-slate-600">Wilayah Dilayani</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us -->
    <section class="py-20 bg-gradient-to-br from-slate-50 to-emerald-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Content -->
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-800 mb-6">Tentang AgriConnect</h2>
                    <div class="space-y-6 text-lg text-slate-600 leading-relaxed">
                        <p>
                            <strong class="text-emerald-600">AgriConnect</strong> adalah platform e-commerce yang didirikan
                            pada tahun 2024
                            untuk menghubungkan petani lokal dengan konsumen di seluruh Indonesia.
                        </p>
                        <p>
                            Kami menyediakan akses mudah ke produk segar berkualitas premium langsung dari petani,
                            dengan harga yang adil dan transparan untuk semua pihak.
                        </p>
                        <p>
                            Melalui teknologi digital yang inovatif, kami membangun ekosistem pertanian berkelanjutan
                            yang menguntungkan petani, konsumen, dan lingkungan.
                        </p>
                    </div>
                </div>

                <!-- Visual -->
                <div class="relative">
                    <div
                        class="bg-gradient-to-br from-emerald-400 to-teal-500 rounded-3xl h-96 flex items-center justify-center shadow-2xl">
                        <div class="text-center text-white">
                            <span class="text-8xl mb-4 block">🌾</span>
                            <h3 class="text-2xl font-bold">Sejak 2024</h3>
                            <p class="text-emerald-100">Melayani Indonesia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-800 mb-4">Misi & Visi Kami</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Mission -->
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-3xl p-8 lg:p-12">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center mr-4">
                            <span class="text-2xl text-white">🎯</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800">Misi Kami</h3>
                    </div>
                    <div class="space-y-4 text-slate-700">
                        <div class="flex items-start">
                            <span class="text-emerald-500 mr-3 mt-1">✓</span>
                            <p>Menghubungkan petani dan UMKM dengan pasar urban melalui sistem e-commerce yang mudah diakses
                                dan efisien</p>
                        </div>
                        <div class="flex items-start">
                            <span class="text-emerald-500 mr-3 mt-1">✓</span>
                            <p>Mengembangkan sistem logistik desa berbasis kemitraan lokal untuk mempercepat distribusi dan
                                menekan biaya transportasi</p>
                        </div>
                        <div class="flex items-start">
                            <span class="text-emerald-500 mr-3 mt-1">✓</span>
                            <p>Menyediakan fitur edukasi digital bagi pelaku usaha pangan agar mampu bersaing di era
                                teknologi</p>
                        </div>
                        <div class="flex items-start">
                            <span class="text-emerald-500 mr-3 mt-1">✓</span>
                            <p>Mengintegrasikan konsep keberlanjutan melalui fitur seperti jejak karbon dan harga adil
                                real-time</p>
                        </div>
                    </div>
                </div>

                <!-- Vision -->
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-3xl p-8 lg:p-12">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center mr-4">
                            <span class="text-2xl text-white">👁️</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800">Visi Kami</h3>
                    </div>
                    <div class="space-y-4 text-slate-700">
                        <div class="flex items-start">
                            <span class="text-blue-500 mr-3 mt-1">★</span>
                            <p>Menjadi platform e-commerce agribisnis terdepan di Indonesia yang memperkuat ekosistem pangan
                                lokal berbasis teknologi, memberdayakan petani dan UMKM, serta memperpendek rantai pasok
                                pangan secara berkelanjutan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="py-20 bg-gradient-to-br from-slate-50 to-emerald-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-800 mb-4">Nilai-Nilai Kami</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Value 1 -->
                <div class="bg-white rounded-3xl p-8 shadow-lg text-center hover:shadow-xl transition-all duration-300">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                        <span class="text-2xl text-white">🤝</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Transparansi</h3>
                    <p class="text-slate-600 text-sm">
                        Informasi yang jelas dan honest mengenai produk dan harga di platform kami.
                    </p>
                </div>

                <!-- Value 2 -->
                <div class="bg-white rounded-3xl p-8 shadow-lg text-center hover:shadow-xl transition-all duration-300">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                        <span class="text-2xl text-white">⚡</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Kualitas</h3>
                    <p class="text-slate-600 text-sm">
                        Produk yang telah melalui seleksi ketat untuk memastikan kesegaran terbaik.
                    </p>
                </div>

                <!-- Value 3 -->
                <div class="bg-white rounded-3xl p-8 shadow-lg text-center hover:shadow-xl transition-all duration-300">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                        <span class="text-2xl text-white">🌱</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Keberlanjutan</h3>
                    <p class="text-slate-600 text-sm">
                        Mendukung praktik pertanian ramah lingkungan dan berkelanjutan.
                    </p>
                </div>

                <!-- Value 4 -->
                <div class="bg-white rounded-3xl p-8 shadow-lg text-center hover:shadow-xl transition-all duration-300">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                        <span class="text-2xl text-white">💡</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Inovasi</h3>
                    <p class="text-slate-600 text-sm">
                        Terus berinovasi dengan teknologi untuk pengalaman terbaik.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-800 mb-4">Mengapa Memilih AgriConnect?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Advantage 1 -->
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-6 border border-emerald-100">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-xl text-white">🚚</span>
                        </div>
                        <h3 class="font-bold text-slate-800">Pengiriman Cepat</h3>
                    </div>
                    <p class="text-slate-600 text-sm">
                        Sistem logistik yang memastikan produk segar sampai dengan cepat.
                    </p>
                </div>

                <!-- Advantage 2 -->
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-6 border border-blue-100">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-xl text-white">💰</span>
                        </div>
                        <h3 class="font-bold text-slate-800">Harga Terjangkau</h3>
                    </div>
                    <p class="text-slate-600 text-sm">
                        Harga langsung dari petani tanpa markup berlebihan.
                    </p>
                </div>

                <!-- Advantage 3 -->
                <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-6 border border-orange-100">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-xl text-white">✅</span>
                        </div>
                        <h3 class="font-bold text-slate-800">Jaminan Kualitas</h3>
                    </div>
                    <p class="text-slate-600 text-sm">
                        Quality control ketat dengan standar keamanan pangan.
                    </p>
                </div>

                <!-- Advantage 4 -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-xl text-white">📱</span>
                        </div>
                        <h3 class="font-bold text-slate-800">Platform Mudah</h3>
                    </div>
                    <p class="text-slate-600 text-sm">
                        Interface yang user-friendly dan mudah digunakan.
                    </p>
                </div>

                <!-- Advantage 5 -->
                <div class="bg-gradient-to-br from-teal-50 to-green-50 rounded-2xl p-6 border border-teal-100">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-teal-500 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-xl text-white">🌿</span>
                        </div>
                        <h3 class="font-bold text-slate-800">Produk Organik</h3>
                    </div>
                    <p class="text-slate-600 text-sm">
                        Pilihan lengkap produk organik bersertifikat.
                    </p>
                </div>

                <!-- Advantage 6 -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mr-4">
                            <span class="text-xl text-white">🎯</span>
                        </div>
                        <h3 class="font-bold text-slate-800">Support 24/7</h3>
                    </div>
                    <p class="text-slate-600 text-sm">
                        Tim customer service yang responsive siap membantu.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-gradient-to-br from-slate-50 to-emerald-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-3xl p-12 text-white">
                <h2 class="text-3xl sm:text-4xl font-bold mb-6">Bergabunglah dengan AgriConnect!</h2>
                <p class="text-xl text-emerald-100 mb-8 leading-relaxed">
                    Dapatkan produk segar berkualitas langsung dari petani atau mulai jual hasil panen Anda dengan mudah.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                    <a href="{{ route('register') }}"
                        class="bg-white text-emerald-600 px-8 py-4 rounded-2xl text-lg font-bold hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        🚀 Daftar Sekarang
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="bg-emerald-700 text-white px-8 py-4 rounded-2xl text-lg font-bold border-2 border-emerald-400 hover:bg-emerald-600 transition-all duration-300">
                        🛒 Mulai Belanja
                    </a>
                </div>

                <!-- Contact Info -->
                <div class="pt-8 border-t border-emerald-400">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-2xl mb-2">📧</div>
                            <p class="text-emerald-100">info@agriconnect.click</p>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl mb-2">📞</div>
                            <p class="text-emerald-100">+62 822-9706-7565</p>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl mb-2">📍</div>
                            <p class="text-emerald-100">Aceh, Indonesia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
