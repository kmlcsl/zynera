@extends('layouts.app')

@section('title', 'Tentang Kami - Zynera')

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
                    <span class="text-sm font-semibold text-emerald-700">⚡ Tentang Zynera</span>
                </div>

                <!-- Main Title -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    <span class="block text-slate-800">Menghubungkan</span>
                    <span
                        class="block bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 bg-clip-text text-transparent">Produsen
                        & Konsumen</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-xl lg:text-2xl text-slate-600 mb-8 leading-relaxed max-w-3xl mx-auto">
                    Platform distribusi energi terbarukan yang menghubungkan produsen energi dengan konsumen untuk akses energi bersih yang terpercaya dan efisien.
                </p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-emerald-600 mb-2">{{ $produsenEnergi }}+</div>
                    <div class="text-slate-600">Produsen Energi</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-teal-600 mb-2">{{ $konsumenAktif }}+</div>
                    <div class="text-slate-600">Konsumen Aktif</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-cyan-600 mb-2">{{ $totalOrders }}+</div>
                    <div class="text-slate-600">Transaksi Berhasil</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-orange-600 mb-2">{{ $wilayahDilayani }}+</div>
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
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-800 mb-6">Tentang Zynera</h2>
                    <div class="space-y-6 text-lg text-slate-600 leading-relaxed">
                        <p>
                            <strong class="text-emerald-600">Zynera</strong> adalah platform distribusi energi terbarukan yang menghubungkan produsen energi dengan konsumen untuk menciptakan akses energi bersih yang mudah dan efisien.
                        </p>
                        <p>
                            Melalui teknologi digital yang inovatif, kami membangun ekosistem energi berkelanjutan dengan transparansi penuh dalam setiap proses distribusi dan harga yang adil untuk semua pihak.
                        </p>
                    </div>
                </div>

                <!-- Visual -->
                <div class="relative">
                    <div class="rounded-3xl h-96 shadow-2xl overflow-hidden">
                        <img src="{{ asset('images/bg.jpg') }}" alt="Zynera Energy Platform" class="w-full h-full object-cover">
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
                            <span class="text-blue-500 mr-3 mt-1">⚡</span>
                            <p class="text-lg font-semibold">Platform distribusi energi terbarukan yang terpercaya dan efisien</p>
                        </div>
                    </div>
                </div>

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
                            <p>Menghubungkan produsen energi dengan konsumen</p>
                        </div>
                        <div class="flex items-start">
                            <span class="text-emerald-500 mr-3 mt-1">✓</span>
                            <p>Memudahkan akses energi bersih untuk semua</p>
                        </div>
                        <div class="flex items-start">
                            <span class="text-emerald-500 mr-3 mt-1">✓</span>
                            <p>Transparansi dan efisiensi dalam distribusi</p>
                        </div>
                        <div class="flex items-start">
                            <span class="text-emerald-500 mr-3 mt-1">✓</span>
                            <p>Mendukung pertumbuhan industri energi terbarukan</p>
                        </div>
                        <div class="flex items-start">
                            <span class="text-emerald-500 mr-3 mt-1">✓</span>
                            <p>Inovasi teknologi platform berkelanjutan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Why Choose Us -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-800 mb-4">Mengapa Memilih Zynera?</h2>
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

@endsection

