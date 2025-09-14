<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>@yield('title', 'AgriConnect - Bahan Makanan Segar Langsung Dari Petani')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Custom Colors */
        .agriconnect-primary {
            background: linear-gradient(135deg, #10b981, #0d9488);
        }

        .agriconnect-secondary {
            background: linear-gradient(135deg, #059669, #0f766e);
        }

        .text-agriconnect-primary {
            color: #10b981;
        }

        .text-agriconnect-secondary {
            color: #059669;
        }

        /* Active menu styling */
        .nav-active {
            color: #059669 !important;
            background-color: #ecfdf5 !important;
        }

        .nav-active-mobile {
            color: #059669 !important;
            background-color: #dcfce7 !important;
        }

        /* Navbar effects */
        .navbar-scrolled {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Mobile menu animation */
        .mobile-menu-enter {
            transform: translateY(-100%);
            opacity: 0;
        }

        .mobile-menu-enter-active {
            transform: translateY(0);
            opacity: 1;
            transition: all 0.3s ease-in-out;
        }

        /* Cart badge animation */
        .cart-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        /* Logo animation */
        .logo-bounce {
            animation: bounce 3s infinite;
        }

        /* Smooth transitions */
        .transition-all {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }

        /* Hover effects */
        .hover-lift {
            transition: transform 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #10b981, #0d9488);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="bg-slate-50 font-poppins antialiased" x-data="{
    mobileMenuOpen: false,
    isScrolled: false
}" x-init="window.addEventListener('scroll', () => {
    const currentScrollY = window.scrollY;
    isScrolled = currentScrollY > 50;
});">

    <!-- Navigation -->
    <header
        class="bg-white shadow-sm border-b border-emerald-100 fixed top-0 left-0 right-0 z-50 transition-all duration-300"
        :class="{
            'navbar-scrolled': isScrolled
        }">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 hover-lift">
                        <div class="relative">
                            <div
                                class="w-12 h-12 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                                <img src="{{ asset('images/logo.jpg') }}" alt="AgriConnect Logo"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-orange-400 rounded-full animate-pulse">
                            </div>
                        </div>
                        <div>
                            <span class="text-2xl font-bold gradient-text">AgriConnect</span>
                            <p class="text-xs text-slate-500 -mt-1">Fresh & Organic</p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="{{ route('home') }}"
                        class="px-3 lg:px-6 py-3 text-sm lg:text-base text-slate-700 hover:text-emerald-600 font-semibold rounded-xl transition-all duration-200 hover:bg-emerald-50 {{ request()->routeIs('home') ? 'nav-active' : '' }}">
                        <i class="fas fa-home mr-1 lg:mr-2"></i>Beranda
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="px-3 lg:px-6 py-3 text-sm lg:text-base text-slate-700 hover:text-emerald-600 font-semibold rounded-xl transition-all duration-200 hover:bg-emerald-50 {{ request()->routeIs('products.*') ? 'nav-active' : '' }}">
                        <i class="fas fa-shopping-basket mr-1 lg:mr-2"></i>Produk
                    </a>
                    <a href="/education"
                        class="px-3 lg:px-6 py-3 text-sm lg:text-base text-slate-700 hover:text-emerald-600 font-semibold rounded-xl transition-all duration-200 hover:bg-emerald-50 {{ request()->is('education*') ? 'nav-active' : '' }}">
                        <i class="fas fa-graduation-cap mr-1 lg:mr-2"></i>Edukasi
                    </a>
                    <a href="/about"
                        class="px-3 lg:px-6 py-3 text-sm lg:text-base text-slate-700 hover:text-emerald-600 font-semibold rounded-xl transition-all duration-200 hover:bg-emerald-50 {{ request()->is('about*') ? 'nav-active' : '' }}">
                        <i class="fas fa-info-circle mr-1 lg:mr-2"></i>Tentang Kami
                    </a>
                </nav>

                <!-- Right Side Actions -->
                <div class="flex items-center space-x-2 lg:space-x-3">
                    @auth
                        <!-- Cart Icon -->
                        <a href="{{ route('cart.index') }}"
                            class="relative p-3 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all duration-200 hover-lift {{ request()->routeIs('cart.*') ? 'text-emerald-600 bg-emerald-50' : '' }}"
                            title="Keranjang Belanja">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h9"></path>
                            </svg>
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', auth()->id())
                                    ->whereHas('product', function ($query) {
                                        $query->where('is_active', true);
                                    })
                                    ->sum('quantity');
                            @endphp
                            @if ($cartCount > 0)
                                <span id="cart-badge"
                                    class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-semibold cart-badge">
                                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                                </span>
                            @endif
                        </a>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center space-x-3 bg-slate-100 hover:bg-emerald-50 px-4 py-3 rounded-2xl transition-all duration-200 hover-lift">
                                @if (auth()->user()->avatar)
                                    <img class="w-8 h-8 rounded-xl object-cover border-2 border-emerald-200"
                                        src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                        alt="{{ auth()->user()->name }}"
                                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=10b981&color=fff'">
                                @else
                                    <div class="w-8 h-8 agriconnect-primary rounded-xl flex items-center justify-center">
                                        <span
                                            class="text-white text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="hidden sm:block text-left">
                                    <p class="text-slate-800 font-semibold text-sm">
                                        {{ Str::limit(Auth::user()->name, 15) }}</p>
                                    <p class="text-slate-500 text-xs">
                                        {{ ucfirst(str_replace('_', ' ', Auth::user()->user_type)) }}</p>
                                </div>
                                <svg class="w-4 h-4 text-slate-500 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden z-50">

                                <!-- User Info Header -->
                                <div class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-slate-200">
                                    <div class="flex items-center space-x-3">
                                        @if (auth()->user()->avatar)
                                            <img class="w-12 h-12 rounded-2xl object-cover border-2 border-emerald-200"
                                                src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                                alt="{{ auth()->user()->name }}">
                                        @else
                                            <div
                                                class="w-12 h-12 agriconnect-primary rounded-2xl flex items-center justify-center">
                                                <span
                                                    class="text-white font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                                            <p class="text-sm text-slate-600 break-words">{{ Auth::user()->email }}</p>
                                            <p class="text-xs text-emerald-600 mt-1 font-medium capitalize">
                                                {{ ucfirst(str_replace('_', ' ', Auth::user()->user_type)) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-2">
                                    <a href="{{ route('profile.show') }}"
                                        class="flex items-center px-4 py-3 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors {{ request()->routeIs('profile.show') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                                        <i class="fas fa-user-circle w-5 h-5 mr-3 text-emerald-500"></i>
                                        <span>Profil Saya</span>
                                    </a>

                                    <a href="{{ route('orders.index') }}"
                                        class="flex items-center px-4 py-3 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors {{ request()->routeIs('orders.*') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                                        <i class="fas fa-shopping-bag w-5 h-5 mr-3 text-blue-500"></i>
                                        <span>Pesanan Saya</span>
                                    </a>

                                    @if (in_array(Auth::user()->user_type, ['admin', 'produsen', 'kurir']))
                                        <div class="border-t border-slate-200 my-2"></div>
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="flex items-center px-4 py-3 text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors {{ request()->routeIs('admin.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                                            @if (Auth::user()->user_type === 'admin')
                                                <i class="fas fa-tachometer-alt w-5 h-5 mr-3 text-blue-500"></i>
                                                <span>Dashboard Admin</span>
                                            @elseif(Auth::user()->user_type === 'produsen')
                                                <i class="fas fa-store w-5 h-5 mr-3 text-green-500"></i>
                                                <span>Dashboard Produsen</span>
                                            @elseif(Auth::user()->user_type === 'kurir')
                                                <i class="fas fa-truck w-5 h-5 mr-3 text-yellow-500"></i>
                                                <span>Dashboard Kurir</span>
                                            @endif
                                        </a>
                                    @endif

                                    <div class="border-t border-slate-200 my-2"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center w-full px-4 py-3 text-left text-red-600 hover:bg-red-50 transition-colors">
                                            <i class="fas fa-sign-out-alt w-5 h-5 mr-3 text-red-500"></i>
                                            <span>Keluar</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Hide "Masuk" button on mobile, show only on desktop -->
                        <a href="{{ route('login') }}"
                            class="hidden md:flex px-3 lg:px-6 py-3 text-sm lg:text-base text-slate-700 hover:text-emerald-600 font-semibold transition-colors rounded-xl hover:bg-emerald-50 {{ request()->routeIs('login') ? 'nav-active' : '' }}">
                            Masuk
                        </a>
                        <!-- Keep "Daftar" button visible on all screen sizes -->
                        <a href="{{ route('register') }}"
                            class="agriconnect-primary text-white px-4 md:px-6 py-3 rounded-2xl hover:shadow-lg font-semibold transition-all duration-200 transform hover:-translate-y-1 {{ request()->routeIs('register') ? 'shadow-lg' : '' }}">
                            Daftar
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden p-2 rounded-xl text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition-all duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2" @click.away="mobileMenuOpen = false"
            class="md:hidden absolute top-full left-0 right-0 bg-white border-b border-slate-200 shadow-lg z-40"
            :class="{ 'navbar-scrolled': isScrolled }">
            <div class="px-4 py-6 space-y-3">
                <a href="{{ route('home') }}"
                    class="flex items-center px-4 py-3 rounded-xl text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'nav-active-mobile' : '' }}">
                    <i class="fas fa-home mr-3 w-5"></i>Beranda
                </a>
                <a href="{{ route('products.index') }}"
                    class="flex items-center px-4 py-3 rounded-xl text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium transition-all duration-200 {{ request()->routeIs('products.*') ? 'nav-active-mobile' : '' }}">
                    <i class="fas fa-shopping-basket mr-3 w-5"></i>Produk
                </a>
                <a href="/education"
                    class="flex items-center px-4 py-3 rounded-xl text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium transition-all duration-200 {{ request()->is('education*') ? 'nav-active-mobile' : '' }}">
                    <i class="fas fa-graduation-cap mr-3 w-5"></i>Edukasi
                </a>
                <a href="/about"
                    class="flex items-center px-4 py-3 rounded-xl text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium transition-all duration-200 {{ request()->is('about*') ? 'nav-active-mobile' : '' }}">
                    <i class="fas fa-info-circle mr-3 w-5"></i>Tentang Kami
                </a>

                @auth
                    <div class="border-t border-slate-200 my-4"></div>
                    <a href="{{ route('cart.index') }}"
                        class="flex items-center justify-between px-4 py-3 rounded-xl text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium transition-all duration-200 {{ request()->routeIs('cart.*') ? 'nav-active-mobile' : '' }}">
                        <span><i class="fas fa-shopping-cart mr-3 w-5"></i>Keranjang</span>
                        @if ($cartCount > 0)
                            <span id="mobile-cart-badge"
                                class="bg-orange-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-semibold">
                                {{ $cartCount > 99 ? '99+' : $cartCount }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('orders.index') }}"
                        class="flex items-center px-4 py-3 rounded-xl text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium transition-all duration-200 {{ request()->routeIs('orders.*') ? 'nav-active-mobile' : '' }}">
                        <i class="fas fa-shopping-bag mr-3 w-5"></i>Pesanan Saya
                    </a>
                    <a href="{{ route('profile.show') }}"
                        class="flex items-center px-4 py-3 rounded-xl text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium transition-all duration-200 {{ request()->routeIs('profile.*') ? 'nav-active-mobile' : '' }}">
                        <i class="fas fa-user-circle mr-3 w-5"></i>Profil Saya
                    </a>

                    @if (in_array(Auth::user()->user_type, ['admin', 'produsen', 'kurir']))
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center px-4 py-3 rounded-xl text-slate-700 hover:text-blue-600 hover:bg-blue-50 font-medium transition-all duration-200 {{ request()->routeIs('admin.*') ? 'bg-blue-50 text-blue-600' : '' }}">
                            @if (Auth::user()->user_type === 'admin')
                                <i class="fas fa-tachometer-alt mr-3 w-5"></i>Dashboard Admin
                            @elseif(Auth::user()->user_type === 'produsen')
                                <i class="fas fa-store mr-3 w-5"></i>Dashboard Produsen
                            @elseif(Auth::user()->user_type === 'kurir')
                                <i class="fas fa-truck mr-3 w-5"></i>Dashboard Kurir
                            @endif
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 font-medium transition-all duration-200">
                            <i class="fas fa-sign-out-alt mr-3 w-5"></i>Keluar
                        </button>
                    </form>
                @else
                    <!-- Move "Masuk" button into mobile menu and add separator -->
                    <div class="border-t border-slate-200 my-4"></div>
                    <a href="{{ route('login') }}"
                        class="flex items-center px-4 py-3 rounded-xl text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 font-medium transition-all duration-200 {{ request()->routeIs('login') ? 'nav-active-mobile' : '' }}">
                        <i class="fas fa-sign-in-alt mr-3 w-5"></i>Masuk
                    </a>
                    <!-- Remove "Daftar" from mobile menu since it's now in main header -->
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="fixed top-24 right-4 z-50 max-w-md w-full" x-data="{ show: true }" x-show="show"
            x-init="setTimeout(() => show = false, 5000)">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl shadow-lg">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                    <span class="font-medium flex-1">{{ session('success') }}</span>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800 ml-2">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-24 right-4 z-50 max-w-md w-full" x-data="{ show: true }" x-show="show"
            x-init="setTimeout(() => show = false, 5000)">
            <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-2xl shadow-lg">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                    </div>
                    <span class="font-medium flex-1">{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-600 hover:text-red-800 ml-2">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content with top padding for fixed header -->
    <main class="pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 lg:px-8 py-16">
            <!-- Changed from grid-cols-1 to grid-cols-2 for mobile, and adjusted brand section span -->
            <!-- Updated grid from lg:grid-cols-4 to lg:grid-cols-3 after removing navigation section -->
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <!-- Brand Section -->
                <!-- Changed from lg:col-span-2 to col-span-2 lg:col-span-2 to span 2 columns on mobile -->
                <div class="col-span-2 lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <div
                            class="w-12 h-12 agriconnect-primary rounded-2xl flex items-center justify-center shadow-lg">
                            <span class="text-white text-lg font-bold">🌿</span>
                        </div>
                        <div>
                            <span class="text-2xl font-bold text-white">AgriConnect</span>
                            <p class="text-sm text-slate-400">Fresh & Organic</p>
                        </div>
                    </div>
                    <p class="text-slate-300 text-lg leading-relaxed mb-6">
                        Platform e-commerce terdepan untuk bahan makanan segar berkualitas premium,
                        menghubungkan petani lokal dengan konsumen cerdas di seluruh Indonesia.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#"
                            class="w-12 h-12 bg-slate-800 hover:bg-emerald-600 rounded-xl flex items-center justify-center transition-colors hover-lift">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#"
                            class="w-12 h-12 bg-slate-800 hover:bg-emerald-600 rounded-xl flex items-center justify-center transition-colors hover-lift">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#"
                            class="w-12 h-12 bg-slate-800 hover:bg-emerald-600 rounded-xl flex items-center justify-center transition-colors hover-lift">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <a href="#"
                            class="w-12 h-12 bg-slate-800 hover:bg-emerald-600 rounded-xl flex items-center justify-center transition-colors hover-lift">
                            <i class="fab fa-whatsapp text-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-bold mb-6 text-white">Hubungi Kami</h4>
                    <ul class="space-y-4">
                        <li class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-phone text-sm"></i>
                            </div>
                            <span class="text-slate-300">+62 822-9706-7565</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-envelope text-sm"></i>
                            </div>
                            <span class="text-slate-300">info@agriconnect.click</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-sm"></i>
                            </div>
                            <span class="text-slate-300">Aceh, Indonesia</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="border-t border-slate-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-slate-400 text-sm">&copy; 2024 AgriConnect. Semua hak dilindungi undang-undang.</p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#"
                            class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Kebijakan
                            Privasi</a>
                        <a href="#"
                            class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Syarat &
                            Ketentuan</a>
                        <a href="#"
                            class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">FAQ</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Function to update cart counter via AJAX
        function updateCartCounter() {
            fetch('/cart/count', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const cartBadge = document.getElementById('cart-badge');
                    const mobileCartBadge = document.getElementById('mobile-cart-badge');

                    if (data.count > 0) {
                        const displayCount = data.count > 99 ? '99+' : data.count;
                        if (cartBadge) cartBadge.textContent = displayCount;
                        if (mobileCartBadge) mobileCartBadge.textContent = displayCount;

                        // Show badges if hidden
                        if (cartBadge) cartBadge.style.display = 'flex';
                        if (mobileCartBadge) mobileCartBadge.style.display = 'flex';
                    } else {
                        // Hide badges if count is 0
                        if (cartBadge) cartBadge.style.display = 'none';
                        if (mobileCartBadge) mobileCartBadge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error updating cart counter:', error));
        }

        // Auto-refresh cart counter
        function refreshCartCounter() {
            updateCartCounter();
        }

        // Event listeners
        window.addEventListener('focus', refreshCartCounter);
        document.addEventListener('DOMContentLoaded', refreshCartCounter);
        setInterval(refreshCartCounter, 60000); // Refresh every minute

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>
