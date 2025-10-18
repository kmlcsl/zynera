@extends('layouts.app')

@section('title', 'Edukasi Lingkungan - Zynera')

@section('content')
    <!--  Section -->
    <section id="edukasi-lingkungan" class="py-20 bg-gradient-to-br from-green-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Edukasi <span class="text-green-600">Lingkungan</span>
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Pelajari dampak minyak jelantah terhadap lingkungan dan bagaimana kita dapat berkontribusi untuk masa depan yang lebih bersih dan berkelanjutan.
                </p>
            </div>

            <!-- Dampak Negatif Minyak Jelantah -->
            <div class="mb-16">
                <div class="bg-red-50 rounded-2xl p-8 border-2 border-red-200">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Bahaya Minyak Jelantah Bagi Lingkungan</h3>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg p-6 shadow-md">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">Pencemaran Air</h4>
                                    <p class="text-gray-700 text-sm leading-relaxed">
                                        1 liter minyak jelantah dapat mencemari hingga <span class="font-bold text-red-600">1 juta liter air</span>. Ketika dibuang ke saluran air, minyak membentuk lapisan yang menghalangi oksigen masuk ke ekosistem air, membunuh kehidupan akuatik.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-6 shadow-md">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">Kerusakan Tanah</h4>
                                    <p class="text-gray-700 text-sm leading-relaxed">
                                        Minyak jelantah yang meresap ke tanah merusak struktur tanah, mengurangi kesuburan, dan membunuh mikroorganisme penting. Proses pemulihan tanah yang tercemar dapat memakan waktu <span class="font-bold text-red-600">puluhan tahun</span>.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-6 shadow-md">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">Penyumbatan Saluran</h4>
                                    <p class="text-gray-700 text-sm leading-relaxed">
                                        Minyak jelantah yang mengeras dalam pipa dan saluran air menyebabkan penyumbatan serius. Di Indonesia, <span class="font-bold text-red-600">60% masalah saluran pembuangan</span> disebabkan oleh pembuangan minyak jelantah yang tidak tepat.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-6 shadow-md">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-2">Emisi Gas Rumah Kaca</h4>
                                    <p class="text-gray-700 text-sm leading-relaxed">
                                        Pembakaran dan dekomposisi minyak jelantah yang tidak terkontrol menghasilkan gas rumah kaca dan zat beracun seperti dioksin. Ini berkontribusi pada <span class="font-bold text-red-600">pemanasan global dan polusi udara</span>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manfaat Daur Ulang -->
            <div class="mb-16">
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 border-2 border-green-300">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Manfaat Mendaur Ulang Minyak Jelantah</h3>
                    </div>

                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-xl transition duration-300">
                            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-3 text-lg">Melindungi Lingkungan</h4>
                            <p class="text-gray-700 leading-relaxed">
                                Mencegah pencemaran air dan tanah, mengurangi polusi, serta melindungi ekosistem dan kehidupan akuatik dari kerusakan jangka panjang.
                            </p>
                        </div>

                        <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-xl transition duration-300">
                            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-3 text-lg">Nilai Ekonomi</h4>
                            <p class="text-gray-700 leading-relaxed">
                                Mengubah limbah menjadi produk bernilai ekonomi tinggi seperti biodiesel, sabun, dan lilin. Menciptakan peluang usaha dan pendapatan tambahan.
                            </p>
                        </div>

                        <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-xl transition duration-300">
                            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-3 text-lg">Energi Terbarukan</h4>
                            <p class="text-gray-700 leading-relaxed">
                                Biodiesel dari minyak jelantah mengurangi ketergantungan pada bahan bakar fosil dan menghasilkan emisi karbon 78% lebih rendah.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Proses Daur Ulang -->
            <div class="mb-16">
                <div class="text-center mb-10">
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                        Proses Daur Ulang Minyak Jelantah
                    </h3>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Perjalanan transformasi minyak jelantah menjadi produk ramah lingkungan yang bernilai
                    </p>
                </div>

                <div class="grid md:grid-cols-4 gap-6">
                    <!-- Step 1 -->
                    <div class="relative">
                        <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-green-200 hover:border-green-400 transition duration-300">
                            <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-xl mb-4 mx-auto">
                                1
                            </div>
                            <h4 class="font-bold text-gray-900 mb-3 text-center">Pengumpulan</h4>
                            <p class="text-gray-600 text-sm text-center leading-relaxed">
                                Minyak jelantah dikumpulkan dari rumah tangga, restoran, dan hotel melalui sistem pickup terjadwal.
                            </p>
                        </div>
                        <!-- Arrow -->
                        <div class="hidden md:block absolute top-1/2 -right-3 transform -translate-y-1/2">
                            <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative">
                        <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-green-200 hover:border-green-400 transition duration-300">
                            <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-xl mb-4 mx-auto">
                                2
                            </div>
                            <h4 class="font-bold text-gray-900 mb-3 text-center">Penyaringan</h4>
                            <p class="text-gray-600 text-sm text-center leading-relaxed">
                                Minyak disaring untuk menghilangkan kotoran dan partikel padat menggunakan filter bertingkat.
                            </p>
                        </div>
                        <!-- Arrow -->
                        <div class="hidden md:block absolute top-1/2 -right-3 transform -translate-y-1/2">
                            <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative">
                        <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-green-200 hover:border-green-400 transition duration-300">
                            <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-xl mb-4 mx-auto">
                                3
                            </div>
                            <h4 class="font-bold text-gray-900 mb-3 text-center">Pengolahan</h4>
                            <p class="text-gray-600 text-sm text-center leading-relaxed">
                                Proses kimia transesterifikasi mengubah minyak menjadi biodiesel dan gliserin sebagai produk sampingan.
                            </p>
                        </div>
                        <!-- Arrow -->
                        <div class="hidden md:block absolute top-1/2 -right-3 transform -translate-y-1/2">
                            <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-green-200 hover:border-green-400 transition duration-300">
                        <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center font-bold text-xl mb-4 mx-auto">
                            4
                        </div>
                        <h4 class="font-bold text-gray-900 mb-3 text-center">Distribusi</h4>
                        <p class="text-gray-600 text-sm text-center leading-relaxed">
                            Produk jadi didistribusikan ke berbagai industri dan konsumen untuk penggunaan ramah lingkungan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Produk Hasil Daur Ulang -->
            <div class="mb-16">
                <div class="text-center mb-10">
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                        Produk Hasil Daur Ulang
                    </h3>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Berbagai produk bernilai tinggi yang dihasilkan dari minyak jelantah
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 shadow-md hover:shadow-xl transition duration-300">
                        <div class="w-16 h-16 bg-blue-500 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2 text-center">Biodiesel</h4>
                        <p class="text-gray-700 text-sm text-center">
                            Bahan bakar ramah lingkungan untuk kendaraan dan mesin diesel
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 shadow-md hover:shadow-xl transition duration-300">
                        <div class="w-16 h-16 bg-purple-500 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2 text-center">Sabun & Deterjen</h4>
                        <p class="text-gray-700 text-sm text-center">
                            Produk pembersih biodegradable dan ramah lingkungan
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 shadow-md hover:shadow-xl transition duration-300">
                        <div class="w-16 h-16 bg-yellow-500 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2 text-center">Lilin Organik</h4>
                        <p class="text-gray-700 text-sm text-center">
                            Lilin aromaterapi dan dekoratif yang eco-friendly
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 shadow-md hover:shadow-xl transition duration-300">
                        <div class="w-16 h-16 bg-green-500 rounded-lg flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-2 text-center">Pakan Ternak</h4>
                        <p class="text-gray-700 text-sm text-center">
                            Suplemen nutrisi berkualitas tinggi untuk hewan ternak
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tips Pengelolaan -->
            <div class="bg-gradient-to-br from-green-50 to-white rounded-2xl p-8 shadow-xl">
                <div class="text-center mb-8">
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                        Tips Pengelolaan Minyak Jelantah di Rumah
                    </h3>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Langkah sederhana yang bisa Anda lakukan untuk berkontribusi pada lingkungan
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg p-6 shadow-md border-l-4 border-green-600">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                                <span class="text-green-600 font-bold text-lg">1</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">Simpan dengan Benar</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Setelah minyak dingin, tuang ke dalam wadah tertutup seperti botol plastik bekas. Jangan tuang ke saluran air atau tanah.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-6 shadow-md border-l-4 border-green-600">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                                <span class="text-green-600 font-bold text-lg">2</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">Saring Sebelum Simpan</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Gunakan saringan halus untuk memisahkan sisa makanan dari minyak. Ini membuat minyak lebih mudah didaur ulang.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-6 shadow-md border-l-4 border-green-600">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                                <span class="text-green-600 font-bold text-lg">3</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">Kumpulkan dalam Jumlah Cukup</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Kumpulkan minimal 1-2 liter sebelum diserahkan ke pengumpul. Ini lebih efisien untuk proses daur ulang.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-6 shadow-md border-l-4 border-green-600">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                                <span class="text-green-600 font-bold text-lg">4</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">Serahkan ke Pengumpul Terpercaya</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Gunakan platform Zynera untuk menjual minyak jelantah Anda dengan harga terbaik dan memastikan daur ulang yang tepat.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-6 shadow-md border-l-4 border-green-600">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                                <span class="text-green-600 font-bold text-lg">5</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">Kurangi Penggunaan</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Pertimbangkan metode memasak alternatif seperti mengukus, memanggang, atau air fryer untuk mengurangi produksi minyak jelantah.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-6 shadow-md border-l-4 border-green-600">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0 mt-1">
                                <span class="text-green-600 font-bold text-lg">6</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-2">Edukasi Keluarga</h4>
                                <p class="text-gray-700 leading-relaxed">
                                    Ajak anggota keluarga untuk ikut serta dalam pengelolaan minyak jelantah yang benar dan ramah lingkungan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection