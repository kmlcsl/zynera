@extends('layouts.app')

@section('title', 'Edukasi Pertanian - AgriConnect')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-emerald-200 rounded-full opacity-30 animate-pulse"></div>
        <div class="absolute top-32 right-20 w-16 h-16 bg-teal-200 rounded-full opacity-40 animate-bounce"></div>
        <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-cyan-200 rounded-full opacity-50 animate-pulse"
            style="animation-delay: 1s;"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <!-- Badge -->
                <div
                    class="inline-flex items-center bg-white/80 backdrop-blur-sm border border-emerald-200 rounded-full px-6 py-3 mb-6 shadow-lg">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-3 animate-pulse"></span>
                    <span class="text-sm font-semibold text-emerald-700">📚 Edukasi Pertanian Modern</span>
                </div>

                <!-- Main Title -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    <span class="block text-slate-800">Belajar Bertani</span>
                    <span
                        class="block bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 bg-clip-text text-transparent">Modern
                        & Berkelanjutan</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-xl lg:text-2xl text-slate-600 mb-8 leading-relaxed max-w-3xl mx-auto">
                    Tingkatkan pengetahuan pertanian Anda dengan panduan praktis, tips modern, dan teknik berkelanjutan
                    untuk hasil panen yang optimal.
                </p>

                <!-- CTA Button -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="#articles"
                        class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-8 py-4 rounded-2xl text-lg font-bold hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        🌱 Mulai Belajar
                    </a>
                    <a href="#tips"
                        class="bg-white text-emerald-600 px-8 py-4 rounded-2xl text-lg font-bold border-2 border-emerald-200 hover:bg-emerald-50 transition-all duration-300">
                        💡 Lihat Tips
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-emerald-600 mb-2">50+</div>
                    <div class="text-slate-600">Artikel Edukasi</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-teal-600 mb-2">100+</div>
                    <div class="text-slate-600">Tips Praktis</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-cyan-600 mb-2">25+</div>
                    <div class="text-slate-600">Video Tutorial</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-orange-600 mb-2">1000+</div>
                    <div class="text-slate-600">Petani Teredukasi</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <section id="articles" class="py-20 bg-gradient-to-br from-slate-50 to-emerald-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-800 mb-4">Artikel Edukasi Pertanian</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Pelajari teknik-teknik modern dalam bertani yang ramah lingkungan dan menguntungkan
                </p>
            </div>

            <!-- Articles Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">

                <!-- Article 1 - Featured -->
                <div
                    class="lg:col-span-2 bg-white rounded-3xl shadow-xl overflow-hidden transform hover:-translate-y-2 transition-all duration-300">
                    <div class="relative h-64 lg:h-80">
                        <div
                            class="w-full h-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                            <div class="text-center text-white">
                                <span class="text-6xl mb-4 block">🌱</span>
                                <h3 class="text-2xl font-bold">Pertanian Organik Modern</h3>
                            </div>
                        </div>
                        <div class="absolute top-4 left-4">
                            <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full">FEATURED</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="flex items-center text-sm text-slate-500 mb-3">
                            <span class="mr-4">📅 15 Des 2024</span>
                            <span class="mr-4">👤 Tim AgriConnect</span>
                            <span>⏱️ 5 min baca</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-4">Panduan Lengkap Pertanian Organik untuk Pemula
                        </h3>
                        <p class="text-slate-600 mb-6 leading-relaxed">
                            Pelajari cara memulai pertanian organik dari nol dengan teknik-teknik ramah lingkungan yang
                            terbukti meningkatkan kualitas hasil panen dan kesehatan tanah secara berkelanjutan.
                        </p>
                        <div class="flex flex-wrap gap-2 mb-6">
                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm">Organik</span>
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">Pemula</span>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Berkelanjutan</span>
                        </div>
                        <a href="#"
                            class="inline-flex items-center bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-200">
                            Baca Selengkapnya
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Article 2 -->
                <div
                    class="bg-white rounded-3xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition-all duration-300">
                    <div class="relative h-48">
                        <div
                            class="w-full h-full bg-gradient-to-br from-blue-400 to-cyan-500 flex items-center justify-center">
                            <span class="text-4xl text-white">💧</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-xs text-slate-500 mb-2">
                            <span class="mr-3">📅 12 Des 2024</span>
                            <span>⏱️ 3 min</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Sistem Irigasi Tetes Hemat Air</h3>
                        <p class="text-slate-600 mb-4 text-sm leading-relaxed">
                            Teknologi irigasi modern yang menghemat air hingga 50% sambil meningkatkan efisiensi penyiraman.
                        </p>
                        <div class="flex flex-wrap gap-1 mb-4">
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs">Irigasi</span>
                            <span class="bg-cyan-100 text-cyan-700 px-2 py-1 rounded-full text-xs">Teknologi</span>
                        </div>
                        <a href="#" class="text-emerald-600 font-semibold text-sm hover:underline">
                            Baca artikel →
                        </a>
                    </div>
                </div>

                <!-- Article 3 -->
                <div
                    class="bg-white rounded-3xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition-all duration-300">
                    <div class="relative h-48">
                        <div
                            class="w-full h-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
                            <span class="text-4xl text-white">🐛</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-xs text-slate-500 mb-2">
                            <span class="mr-3">📅 10 Des 2024</span>
                            <span>⏱️ 4 min</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Pengendalian Hama Alami</h3>
                        <p class="text-slate-600 mb-4 text-sm leading-relaxed">
                            Cara mengatasi hama tanaman menggunakan bahan-bahan alami tanpa pestisida kimia berbahaya.
                        </p>
                        <div class="flex flex-wrap gap-1 mb-4">
                            <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded-full text-xs">Hama</span>
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Alami</span>
                        </div>
                        <a href="#" class="text-emerald-600 font-semibold text-sm hover:underline">
                            Baca artikel →
                        </a>
                    </div>
                </div>

                <!-- Article 4 -->
                <div
                    class="bg-white rounded-3xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition-all duration-300">
                    <div class="relative h-48">
                        <div
                            class="w-full h-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                            <span class="text-4xl text-white">🌿</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-xs text-slate-500 mb-2">
                            <span class="mr-3">📅 8 Des 2024</span>
                            <span>⏱️ 6 min</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Kompos Organik Berkualitas</h3>
                        <p class="text-slate-600 mb-4 text-sm leading-relaxed">
                            Panduan membuat pupuk kompos organik sendiri untuk nutrisi tanaman yang optimal dan ramah
                            lingkungan.
                        </p>
                        <div class="flex flex-wrap gap-1 mb-4">
                            <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-full text-xs">Kompos</span>
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">DIY</span>
                        </div>
                        <a href="#" class="text-emerald-600 font-semibold text-sm hover:underline">
                            Baca artikel →
                        </a>
                    </div>
                </div>

                <!-- Article 5 -->
                <div
                    class="bg-white rounded-3xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition-all duration-300">
                    <div class="relative h-48">
                        <div
                            class="w-full h-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center">
                            <span class="text-4xl text-white">📱</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-xs text-slate-500 mb-2">
                            <span class="mr-3">📅 5 Des 2024</span>
                            <span>⏱️ 4 min</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Smart Farming dengan IoT</h3>
                        <p class="text-slate-600 mb-4 text-sm leading-relaxed">
                            Memanfaatkan teknologi Internet of Things untuk monitoring dan otomasi sistem pertanian modern.
                        </p>
                        <div class="flex flex-wrap gap-1 mb-4">
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">IoT</span>
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs">Smart</span>
                        </div>
                        <a href="#" class="text-emerald-600 font-semibold text-sm hover:underline">
                            Baca artikel →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tips Section -->
            <div id="tips" class="bg-white rounded-3xl shadow-xl p-8 lg:p-12">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-slate-800 mb-4">💡 Tips Praktis Sehari-hari</h2>
                    <p class="text-lg text-slate-600">
                        Tips singkat dan mudah diterapkan untuk meningkatkan hasil pertanian Anda
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-emerald-50 p-6 rounded-2xl border-l-4 border-emerald-500">
                        <div class="flex items-center mb-3">
                            <span class="text-2xl mr-3">🌱</span>
                            <h3 class="font-bold text-slate-800">Penanaman Optimal</h3>
                        </div>
                        <p class="text-slate-600 text-sm">
                            Tanam bibit pada pagi hari (06.00-08.00) untuk mengurangi stress tanaman dan meningkatkan
                            tingkat keberhasilan tumbuh.
                        </p>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-2xl border-l-4 border-blue-500">
                        <div class="flex items-center mb-3">
                            <span class="text-2xl mr-3">💧</span>
                            <h3 class="font-bold text-slate-800">Penyiraman Efektif</h3>
                        </div>
                        <p class="text-slate-600 text-sm">
                            Siram tanaman pada sore hari untuk mengurangi penguapan dan memastikan air terserap optimal oleh
                            akar.
                        </p>
                    </div>

                    <div class="bg-orange-50 p-6 rounded-2xl border-l-4 border-orange-500">
                        <div class="flex items-center mb-3">
                            <span class="text-2xl mr-3">🌡️</span>
                            <h3 class="font-bold text-slate-800">Kontrol Suhu</h3>
                        </div>
                        <p class="text-slate-600 text-sm">
                            Gunakan mulsa organik untuk menjaga suhu tanah tetap stabil dan mencegah pertumbuhan gulma.
                        </p>
                    </div>

                    <div class="bg-purple-50 p-6 rounded-2xl border-l-4 border-purple-500">
                        <div class="flex items-center mb-3">
                            <span class="text-2xl mr-3">🧪</span>
                            <h3 class="font-bold text-slate-800">pH Tanah Ideal</h3>
                        </div>
                        <p class="text-slate-600 text-sm">
                            Cek pH tanah secara berkala. Kebanyakan tanaman tumbuh optimal pada pH 6.0-7.0.
                        </p>
                    </div>

                    <div class="bg-teal-50 p-6 rounded-2xl border-l-4 border-teal-500">
                        <div class="flex items-center mb-3">
                            <span class="text-2xl mr-3">🔄</span>
                            <h3 class="font-bold text-slate-800">Rotasi Tanaman</h3>
                        </div>
                        <p class="text-slate-600 text-sm">
                            Lakukan rotasi tanaman setiap musim untuk menjaga kesuburan tanah dan mencegah hama.
                        </p>
                    </div>

                    <div class="bg-green-50 p-6 rounded-2xl border-l-4 border-green-500">
                        <div class="flex items-center mb-3">
                            <span class="text-2xl mr-3">📊</span>
                            <h3 class="font-bold text-slate-800">Monitoring Rutin</h3>
                        </div>
                        <p class="text-slate-600 text-sm">
                            Periksa tanaman setiap hari untuk deteksi dini penyakit atau hama yang dapat merusak panen.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Coming Soon Section for Producers -->
            <div class="mt-16 relative overflow-hidden">
                <div
                    class="bg-gradient-to-br from-purple-600 via-pink-500 to-orange-500 rounded-3xl p-8 lg:p-12 shadow-2xl">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-4 left-4 w-16 h-16 bg-white rounded-full animate-pulse"></div>
                        <div class="absolute top-20 right-8 w-12 h-12 bg-white rounded-full animate-bounce"></div>
                        <div class="absolute bottom-8 left-1/3 w-8 h-8 bg-white rounded-full animate-pulse"
                            style="animation-delay: 1s;"></div>
                        <div class="absolute bottom-16 right-1/4 w-20 h-20 bg-white rounded-full animate-pulse"
                            style="animation-delay: 2s;"></div>
                    </div>

                    <div class="relative z-10 text-center text-white">
                        <!-- Coming Soon Badge -->
                        <div
                            class="inline-flex items-center bg-white/20 backdrop-blur-sm border border-white/30 rounded-full px-6 py-3 mb-6 shadow-lg">
                            <span class="w-3 h-3 bg-yellow-300 rounded-full mr-3 animate-pulse"></span>
                            <span class="text-sm font-bold">🚀 SEGERA HADIR</span>
                        </div>

                        <!-- Title -->
                        <h2 class="text-3xl sm:text-4xl font-bold mb-6">
                            <span class="block">Fitur Khusus untuk</span>
                            <span class="block text-yellow-300">Produsen & Petani</span>
                        </h2>

                        <!-- Description -->
                        <p class="text-lg sm:text-xl mb-8 max-w-3xl mx-auto leading-relaxed opacity-90">
                            Platform edukasi lengkap dengan video tutorial dan galeri foto untuk membantu Anda
                            <strong class="text-yellow-300">mempromosikan produk</strong> dan
                            <strong class="text-yellow-300">meningkatkan penjualan</strong> secara online!
                        </p>

                        <!-- Features Preview -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                                <div class="text-4xl mb-3">📹</div>
                                <h3 class="font-bold text-lg mb-2">Video Tutorial</h3>
                                <p class="text-sm opacity-90">Buat video edukasi untuk promosi produk Anda</p>
                            </div>

                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                                <div class="text-4xl mb-3">📸</div>
                                <h3 class="font-bold text-lg mb-2">Galeri Foto</h3>
                                <p class="text-sm opacity-90">Showcase produk dengan foto berkualitas tinggi</p>
                            </div>

                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                                <div class="text-4xl mb-3">📈</div>
                                <h3 class="font-bold text-lg mb-2">Analitik Penjualan</h3>
                                <p class="text-sm opacity-90">Pantau performa dan tingkatkan strategi marketing</p>
                            </div>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                            <button
                                class="bg-yellow-400 text-purple-800 px-8 py-4 rounded-2xl text-lg font-bold hover:bg-yellow-300 transition-all duration-300 transform hover:-translate-y-1 shadow-xl">
                                🔔 Beritahu Saya
                            </button>
                            <a href="#articles"
                                class="bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-2xl text-lg font-bold border-2 border-white/30 hover:bg-white/30 transition-all duration-300">
                                📚 Pelajari Dulu
                            </a>
                        </div>

                        <!-- Timeline Info -->
                        <div class="mt-8 bg-white/10 backdrop-blur-sm rounded-xl p-4 inline-block">
                            <p class="text-sm font-medium">
                                ⏰ <strong>Timeline:</strong> Diluncurkan Q1 2025 |
                                👥 <strong>Early Access:</strong> Petani terdaftar mendapat prioritas
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="text-center mt-16">
                <h3 class="text-2xl font-bold text-slate-800 mb-4">Siap Terapkan Ilmu Pertanian Modern?</h3>
                <p class="text-lg text-slate-600 mb-8">
                    Bergabunglah dengan ribuan petani yang sudah merasakan manfaat teknik pertanian berkelanjutan
                </p>
                <a href="{{ route('products.index') }}"
                    class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-8 py-4 rounded-2xl text-lg font-bold hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    🛒 Mulai Belanja Produk Berkualitas
                </a>
            </div>
        </div>
    </section>
@endsection
