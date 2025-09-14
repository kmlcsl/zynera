<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar - {{ config('app.name', 'AgriConnect') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .agriconnect-primary {
            background: linear-gradient(135deg, #10b981, #0d9488);
        }

        .agriconnect-secondary {
            background: linear-gradient(135deg, #059669, #0f766e);
        }

        .text-agriconnect-primary {
            color: #10b981;
        }

        .bg-agriconnect-pattern {
            background-image:
                radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(5, 150, 105, 0.1) 0%, transparent 50%);
        }
    </style>
</head>

<body class="font-poppins antialiased bg-slate-50">
    <div class="min-h-screen flex">
        <!-- Left Side - Hero -->
        <div class="hidden lg:flex lg:w-1/2 agriconnect-primary relative overflow-hidden">
            <div class="absolute inset-0 bg-agriconnect-pattern"></div>
            <div class="relative z-10 flex flex-col justify-center p-12 text-white">
                <!-- Logo -->
                <div class="flex items-center mb-8">
                    <img src="{{ asset('images/logo.jpg') }}" alt="AgriConnect"
                        class="w-12 h-12 object-contain mr-3 rounded-2xl bg-white/20 backdrop-blur-sm p-1">
                    <div>
                        <h1 class="text-2xl font-bold">AgriConnect</h1>
                        <p class="text-sm text-emerald-100 -mt-1">Fresh & Organic</p>
                    </div>
                </div>

                <div class="max-w-md">
                    <h2 class="text-3xl font-bold mb-4">Bergabung dengan Komunitas Kami!</h2>
                    <p class="text-emerald-100 text-lg leading-relaxed mb-8">
                        Daftar sekarang dan mulai menikmati produk organik segar langsung dari petani,
                        atau mulai berjualan sebagai produsen lokal.
                    </p>

                    <!-- Benefits -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-emerald-100">Gratis untuk pembeli dan mudah berbelanja</span>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-emerald-100">Platform lengkap untuk penjual dan produsen</span>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-emerald-100">Peluang menjadi kurir dan bergabung tim</span>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-emerald-100">Dukung ekonomi hijau berkelanjutan</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-20 right-20 w-32 h-32 bg-white bg-opacity-10 rounded-full animate-pulse"></div>
            <div class="absolute bottom-32 right-32 w-20 h-20 bg-white bg-opacity-5 rounded-full animate-bounce"></div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <!-- Mobile Logo -->
                <div class="flex justify-center lg:hidden mb-8">
                    <div class="flex items-center">
                        <div class="w-10 h-10 agriconnect-primary rounded-2xl flex items-center justify-center mr-3">
                            <span class="text-xl">🌿</span>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-agriconnect-primary">AgriConnect</h1>
                            <p class="text-xs text-slate-500 -mt-1">Fresh & Organic</p>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900">Buat Akun Baru</h2>
                    <p class="mt-2 text-sm text-slate-600">
                        Atau
                        <a href="{{ route('login') }}" class="font-medium text-agriconnect-primary hover:underline">
                            masuk ke akun yang sudah ada
                        </a>
                    </p>
                </div>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-slate-200">
                    <form method="POST" action="{{ route('register') }}" class="space-y-6" x-data="{ userType: 'konsumen' }">
                        @csrf

                        <!-- User Type Selection - DROPDOWN -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-3">
                                Daftar Sebagai
                            </label>
                            <div class="relative">
                                <select name="user_type" x-model="userType"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors bg-white text-slate-900 font-medium">
                                    <option value="konsumen">🛒 Pembeli - Berbelanja produk segar</option>
                                    <option value="produsen">🏪 Penjual - Jual produk pertanian</option>
                                    <option value="kurir">🚚 Kurir - Antar pesanan pelanggan</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Info berdasarkan pilihan -->
                            <div class="mt-3 p-3 bg-slate-50 rounded-lg">
                                <div x-show="userType === 'konsumen'" class="text-sm text-slate-600">
                                    <span class="font-medium text-emerald-600">Sebagai Pembeli:</span> Akses ke ribuan
                                    produk segar, pengiriman cepat, dan program loyalitas.
                                </div>
                                <div x-show="userType === 'produsen'" class="text-sm text-slate-600">
                                    <span class="font-medium text-emerald-600">Sebagai Penjual:</span> Jual produk Anda,
                                    kelola toko online, dan jangkau lebih banyak pelanggan.
                                </div>
                                <div x-show="userType === 'kurir'" class="text-sm text-slate-600">
                                    <span class="font-medium text-emerald-600">Sebagai Kurir:</span> Dapatkan
                                    penghasilan tambahan dengan mengantarkan pesanan di area Anda.
                                </div>
                            </div>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                Nama Lengkap
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="name" name="name" type="text" required autofocus
                                    value="{{ old('name') }}"
                                    class="w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    placeholder="Masukkan nama lengkap Anda">
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                        </path>
                                    </svg>
                                </div>
                                <input id="email" name="email" type="email" required
                                    value="{{ old('email') }}"
                                    class="w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    placeholder="Masukkan email Anda">
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                                Nomor WhatsApp
                                <span x-show="userType !== 'konsumen'" class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                                    class="w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    placeholder="08123456789">
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                <span x-show="userType === 'konsumen'">Opsional - untuk komunikasi pengiriman</span>
                                <span x-show="userType === 'produsen'">Wajib - untuk komunikasi dengan pembeli</span>
                                <span x-show="userType === 'kurir'">Wajib - untuk koordinasi pengiriman</span>
                            </p>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Conditional Fields Based on User Type -->

                        <!-- Location Fields for Produsen -->
                        <div x-show="userType === 'produsen'" x-transition class="space-y-4">
                            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-200">
                                <h4 class="text-sm font-medium text-emerald-800 mb-2">Informasi Lokasi Usaha</h4>
                                <p class="text-xs text-emerald-600 mb-3">Data ini akan membantu pembeli menemukan
                                    produk dari wilayah Anda</p>

                                <div class="grid grid-cols-1 gap-3">
                                    <div>
                                        <label for="village" class="block text-sm font-medium text-slate-700 mb-1">
                                            Desa/Kelurahan <span class="text-red-500">*</span>
                                        </label>
                                        <input id="village" name="village" type="text"
                                            value="{{ old('village') }}"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                            placeholder="Contoh: Desa Makmur">
                                        @error('village')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="district" class="block text-sm font-medium text-slate-700 mb-1">
                                            Kecamatan <span class="text-red-500">*</span>
                                        </label>
                                        <input id="district" name="district" type="text"
                                            value="{{ old('district') }}"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                            placeholder="Contoh: Cianjur">
                                        @error('district')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info for Kurir -->
                        <div x-show="userType === 'kurir'" x-transition class="space-y-4">
                            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                                <h4 class="text-sm font-medium text-blue-800 mb-2">Informasi Kurir</h4>
                                <p class="text-xs text-blue-600 mb-3">Sebagai kurir, Anda akan membantu mengantarkan
                                    pesanan ke pelanggan</p>

                                <div class="grid grid-cols-1 gap-3">
                                    <div>
                                        <label for="coverage_area"
                                            class="block text-sm font-medium text-slate-700 mb-1">
                                            Area Jangkauan <span class="text-red-500">*</span>
                                        </label>
                                        <input id="coverage_area" name="district" type="text"
                                            value="{{ old('district') }}"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            placeholder="Contoh: Aceh Barat, Aceh">
                                        <p class="mt-1 text-xs text-slate-500">Sebutkan kecamatan yang bisa Anda
                                            jangkau</p>
                                        @error('district')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-slate-700 mb-2">
                                Alamat Lengkap
                            </label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none"
                                placeholder="Masukkan alamat lengkap untuk pengiriman">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                                Password
                            </label>
                            <div class="relative" x-data="{ showPassword: false }">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="password" name="password" required
                                    :type="showPassword ? 'text' : 'password'"
                                    class="w-full pl-10 pr-12 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    placeholder="Minimal 8 karakter">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="text-slate-400 hover:text-slate-600">
                                        <svg x-show="!showPassword" class="h-5 w-5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <svg x-show="showPassword" class="h-5 w-5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">
                                Konfirmasi Password
                            </label>
                            <div class="relative" x-data="{ showPassword: false }">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" required
                                    :type="showPassword ? 'text' : 'password'"
                                    class="w-full pl-10 pr-12 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    placeholder="Ulangi password Anda">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" @click="showPassword = !showPassword"
                                        class="text-slate-400 hover:text-slate-600">
                                        <svg x-show="!showPassword" class="h-5 w-5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <svg x-show="showPassword" class="h-5 w-5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Terms Agreement -->
                        <div>
                            <div class="flex items-start">
                                <input id="terms" name="terms" type="checkbox" required
                                    class="mt-1 h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded">
                                <label for="terms" class="ml-2 block text-sm text-slate-700">
                                    Saya setuju dengan
                                    <a href="#"
                                        class="font-medium text-agriconnect-primary hover:underline">Syarat &
                                        Ketentuan</a>
                                    dan
                                    <a href="#"
                                        class="font-medium text-agriconnect-primary hover:underline">Kebijakan
                                        Privasi</a>
                                    AgriConnect
                                </label>
                            </div>

                            @error('terms')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white agriconnect-primary hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                    </path>
                                </svg>
                                <span x-show="userType === 'konsumen'">Daftar Sebagai Pembeli</span>
                                <span x-show="userType === 'produsen'">Daftar Sebagai Penjual</span>
                                <span x-show="userType === 'kurir'">Daftar Sebagai Kurir</span>
                            </button>
                        </div>
                    </form>

                    <!-- Social Register -->
                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-slate-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-slate-500">Atau daftar dengan</span>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Google OAuth Button -->
                            <a href="{{ route('auth.google') }}"
                                class="w-full inline-flex items-center justify-center py-3 px-4 border border-slate-300 rounded-xl shadow-sm bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition-colors">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                    <path fill="currentColor"
                                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                    <path fill="currentColor"
                                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                    <path fill="currentColor"
                                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                </svg>
                                <span class="ml-3 font-medium">Google</span>
                            </a>

                            <!-- Facebook Button -->
                            <button disabled
                                class="w-full inline-flex items-center justify-center py-3 px-4 border border-slate-200 rounded-xl shadow-sm bg-slate-50 text-sm font-medium text-slate-400 cursor-not-allowed relative">
                                <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                                <span class="ml-3 font-medium">Facebook</span>
                                <span
                                    class="absolute -top-1 -right-1 bg-yellow-400 text-yellow-900 text-xs px-1.5 py-0.5 rounded-full font-semibold">
                                    Soon
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Footer Links -->
                <p class="mt-8 text-center text-sm text-slate-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-medium text-agriconnect-primary hover:underline">
                        Masuk di sini
                    </a>
                </p>
                <p class="mt-2 text-center text-sm text-slate-600">
                    <a href="{{ route('home') }}"
                        class="font-medium text-slate-500 hover:text-agriconnect-primary hover:underline">
                        ← Kembali ke Beranda
                    </a>
                </p>

                <p class="mt-4 text-center text-xs text-slate-500">
                    <a href="{{ route('home') }}" class="hover:underline">Kembali ke beranda</a>
                    •
                    <a href="#" class="hover:underline">Kebijakan Privasi</a>
                    •
                    <a href="#" class="hover:underline">Syarat & Ketentuan</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
