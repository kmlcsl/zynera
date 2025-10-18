<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pilih Jenis Akun - {{ config('app.name', 'Zynera') }}</title>

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

        .Zynera-primary {
            background: linear-gradient(135deg, #10b981, #0d9488);
        }

        .text-Zynera-primary {
            color: #10b981;
        }

        .bg-Zynera-pattern {
            background-image:
                radial-gradient(circle at 25% 25%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(5, 150, 105, 0.1) 0%, transparent 50%);
        }
    </style>
</head>

<body class="font-poppins antialiased bg-slate-50">
    <div class="min-h-screen flex">
        <!-- Left Side - Hero -->
        <div class="hidden lg:flex lg:w-1/2 Zynera-primary relative overflow-hidden">
            <div class="absolute inset-0 bg-Zynera-pattern"></div>
            <div class="relative z-10 flex flex-col justify-center p-12 text-white">
                <!-- Logo -->
                <div class="flex items-center mb-8">
                    <img src="{{ asset('images/logo-zynera-v2.png') }}" alt="Zynera"
                        class="w-12 h-12 object-contain mr-3 rounded-2xl bg-white/20 backdrop-blur-sm p-1">
                    <div>
                        <h1 class="text-2xl font-bold">Zynera</h1>
                        <p class="text-sm text-emerald-100 -mt-1">Fresh & Organic</p>
                    </div>
                </div>

                <div class="max-w-md">
                    <h2 class="text-3xl font-bold mb-4">Hampir Selesai!</h2>
                    <p class="text-emerald-100 text-lg leading-relaxed mb-8">
                        Halo <span class="font-semibold">{{ $tempGoogleData['name'] }}</span>!
                        Silakan pilih jenis akun yang ingin Anda buat di Zynera.
                    </p>

                    <!-- Account Types Info -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm">🛒</span>
                            </div>
                            <span class="text-emerald-100">Pembeli - Berbelanja produk segar</span>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm">🏪</span>
                            </div>
                            <span class="text-emerald-100">Penjual - Jual produk pertanian</span>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm">🚚</span>
                            </div>
                            <span class="text-emerald-100">Kurir - Antar pesanan pelanggan</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-20 right-20 w-32 h-32 bg-white bg-opacity-10 rounded-full animate-pulse"></div>
            <div class="absolute bottom-32 right-32 w-20 h-20 bg-white bg-opacity-5 rounded-full animate-bounce"></div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-6 py-12 lg:px-8" x-data="{ userType: 'konsumen' }">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <!-- Mobile Logo -->
                <div class="flex justify-center lg:hidden mb-8">
                    <div class="flex items-center">
                        <div class="w-10 h-10 Zynera-primary rounded-2xl flex items-center justify-center mr-3">
                            <span class="text-xl">🌿</span>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-Zynera-primary">Zynera</h1>
                            <p class="text-xs text-slate-500 -mt-1">Fresh & Organic</p>
                        </div>
                    </div>
                </div>

                <!-- Header -->
                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center shadow-lg">
                            <img src="{{ $tempGoogleData['avatar'] }}" alt="Google Avatar"
                                class="w-12 h-12 rounded-xl object-cover">
                        </div>
                    </div>

                    <h2 class="text-3xl font-bold tracking-tight text-slate-900">Pilih Jenis Akun</h2>
                    <p class="mt-2 text-sm text-slate-600">
                        Halo <span class="font-medium text-Zynera-primary">{{ $tempGoogleData['name'] }}</span>!
                    </p>
                    <p class="text-xs text-slate-500">{{ $email }}</p>
                </div>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-slate-200">

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('google.user.type.selection.post') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">

                        <!-- User Type Selection -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-4">
                                Pilih Jenis Akun
                            </label>
                            <div class="space-y-3">
                                <!-- Konsumen -->
                                <div class="relative">
                                    <input type="radio" name="user_type" value="konsumen" x-model="userType"
                                        id="konsumen" class="absolute opacity-0">
                                    <label for="konsumen"
                                        class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-colors duration-200"
                                        :class="userType === 'konsumen' ? 'border-emerald-500 bg-emerald-50' :
                                            'border-slate-300 hover:border-emerald-300'">
                                        <div class="flex-shrink-0 mr-4">
                                            <div
                                                class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-xl">🛒</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-slate-900">Pembeli</h3>
                                            <p class="text-sm text-slate-600">Berbelanja produk segar dari petani lokal
                                            </p>
                                        </div>
                                    </label>
                                </div>

                                <!-- Produsen -->
                                <div class="relative">
                                    <input type="radio" name="user_type" value="produsen" x-model="userType"
                                        id="produsen" class="absolute opacity-0">
                                    <label for="produsen"
                                        class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-colors duration-200"
                                        :class="userType === 'produsen' ? 'border-emerald-500 bg-emerald-50' :
                                            'border-slate-300 hover:border-emerald-300'">
                                        <div class="flex-shrink-0 mr-4">
                                            <div
                                                class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <span class="text-xl">🏪</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-slate-900">Penjual/Produsen</h3>
                                            <p class="text-sm text-slate-600">Jual produk pertanian Anda</p>
                                        </div>
                                    </label>
                                </div>

                                <!-- Kurir -->
                                <div class="relative">
                                    <input type="radio" name="user_type" value="kurir" x-model="userType"
                                        id="kurir" class="absolute opacity-0">
                                    <label for="kurir"
                                        class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-colors duration-200"
                                        :class="userType === 'kurir' ? 'border-emerald-500 bg-emerald-50' :
                                            'border-slate-300 hover:border-emerald-300'">
                                        <div class="flex-shrink-0 mr-4">
                                            <div
                                                class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                                <span class="text-xl">🚚</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-slate-900">Kurir</h3>
                                            <p class="text-sm text-slate-600">Antar pesanan dan dapatkan penghasilan
                                            </p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            @error('user_type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Conditional Fields -->
                        <div x-show="userType !== 'konsumen'" x-transition class="space-y-4">
                            <!-- Phone (Required for Produsen & Kurir) -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nomor WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" id="phone"
                                    class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    placeholder="08123456789">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div>
                                <label for="address" class="block text-sm font-medium text-slate-700 mb-2">
                                    Alamat Lengkap
                                </label>
                                <textarea name="address" id="address" rows="2"
                                    class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none"
                                    placeholder="Alamat lengkap Anda"></textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Village & District for Produsen -->
                            <div x-show="userType === 'produsen'" class="grid grid-cols-1 gap-3">
                                <div>
                                    <label for="village" class="block text-sm font-medium text-slate-700 mb-1">
                                        Desa/Kelurahan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="village" id="village"
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
                                    <input type="text" name="district" id="district"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                        placeholder="Contoh: Cianjur">
                                    @error('district')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- District for Kurir -->
                            <div x-show="userType === 'kurir'">
                                <label for="district_kurir" class="block text-sm font-medium text-slate-700 mb-1">
                                    Area Jangkauan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="district" id="district_kurir"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    placeholder="Contoh: Aceh Barat, Aceh">
                                <p class="mt-1 text-xs text-slate-500">Sebutkan kecamatan yang bisa Anda jangkau</p>
                                @error('district')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit"
                                class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white Zynera-primary hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                Lanjutkan ke Verifikasi OTP
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-slate-600">
                        <a href="{{ route('login') }}" class="font-medium text-Zynera-primary hover:underline">
                            ← Kembali ke Login
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>



