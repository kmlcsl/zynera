<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AgriConnect') }} - Admin @yield('title', 'Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Modern color scheme and design system */
        .agriconnect-primary {
            background: linear-gradient(135deg, #1e3a2e 0%, #2D5016 100%);
        }

        .agriconnect-secondary {
            background: linear-gradient(135deg, #a8d5a8 0%, #8FBC8F 100%);
        }

        .agriconnect-accent {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        }

        .text-agriconnect-primary {
            color: #1e3a2e;
        }

        .text-agriconnect-secondary {
            color: #6b8e6b;
        }

        .text-agriconnect-accent {
            color: #d4af37;
        }

        .border-agriconnect-primary {
            border-color: #2D5016;
        }

        /* Modern sidebar with glassmorphism effect */
        .modern-sidebar {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        /* Smooth transitions */
        .sidebar-transition {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Modern overlay */
        .sidebar-overlay {
            backdrop-filter: blur(8px);
            background: rgba(15, 23, 42, 0.5);
        }

        /* Modern navigation items */
        .nav-item {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            margin: 4px 0;
        }

        .nav-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(45, 80, 22, 0.15);
        }

        .nav-item.active {
            background: linear-gradient(135deg, #2D5016 0%, #1e3a2e 100%);
            box-shadow: 0 4px 20px rgba(45, 80, 22, 0.3);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: linear-gradient(180deg, #FFD700 0%, #d4af37 100%);
            border-radius: 2px;
        }

        /* Modern submenu styling */
        .submenu-item {
            position: relative;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .submenu-item:hover {
            background: rgba(45, 80, 22, 0.05);
            transform: translateX(2px);
        }

        .submenu-item.active {
            background: linear-gradient(135deg, rgba(45, 80, 22, 0.1) 0%, rgba(45, 80, 22, 0.05) 100%);
            border-left: 3px solid #2D5016;
        }

        /* Modern cards */
        .modern-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        /* Modern buttons */
        .modern-btn {
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .modern-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        /* Modern search */
        .modern-search {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .modern-search:focus-within {
            border-color: #2D5016;
            box-shadow: 0 0 0 3px rgba(45, 80, 22, 0.1);
        }

        /* Modern notifications */
        .notification-badge {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        /* Modern dropdown */
        .modern-dropdown {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(241, 245, 249, 0.5);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #2D5016 0%, #1e3a2e 100%);
            border-radius: 3px;
        }

        /* Modern logo area */
        .logo-area {
            background: linear-gradient(135deg, rgba(45, 80, 22, 0.05) 0%, rgba(143, 188, 143, 0.05) 100%);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        }

        /* Body background */
        body {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            font-family: 'Inter', sans-serif;
        }

        /* Modern user profile - Enhanced z-index for proper positioning */
        .profile-dropdown {
            z-index: 9999 !important;
            position: absolute !important;
        }

        /* Ensure profile stays on top */
        .top-bar {
            position: relative;
            z-index: 10;
        }
    </style>
</head>

<body class="text-gray-900 select-none" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen max-w-full mx-auto">
        <!-- Modern mobile sidebar overlay -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
            class="fixed inset-0 z-40 sidebar-overlay lg:hidden">
        </div>

        <!-- Modern sidebar with glassmorphism -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-72 modern-sidebar flex flex-col justify-between sidebar-transition lg:translate-x-0 lg:static lg:inset-0"
            :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }" x-cloak>
            <div class="flex flex-col h-full">
                <!-- Modern logo area -->
                <div class="logo-area flex items-center gap-4 px-8 py-8">
                    <div class="relative">
                        <img src="{{ asset('images/logo.jpg') }}" alt="AgriConnect Logo"
                            class="w-14 h-14 object-contain rounded-xl shadow-lg">
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div>
                        <h1 class="text-agriconnect-primary font-bold text-xl leading-none tracking-tight">
                            AGRI
                        </h1>
                        <p class="text-sm text-agriconnect-secondary font-semibold tracking-wide">
                            CONNECT ADMIN
                        </p>
                        <div class="w-12 h-1 bg-gradient-to-r from-agriconnect-primary to-agriconnect-secondary rounded-full mt-1"></div>
                    </div>
                </div>

                <!-- Modern navigation with enhanced styling -->
                <nav class="flex-1 px-6 py-4 space-y-2 custom-scrollbar overflow-y-auto">
                    <!-- Dashboard - Accessible by ALL admin panel users -->
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-item flex items-center gap-3 {{ request()->routeIs('admin.dashboard') ? 'active text-white' : 'text-gray-600 hover:bg-gray-50' }} rounded-md px-4 py-3 font-semibold text-sm transition-colors">
                        <i class="fas fa-th-large text-sm"></i>
                        Dashboard
                    </a>

                    <!-- Produk - ADMIN & PRODUSEN -->
                    @if (in_array(auth()->user()->user_type, ['admin', 'produsen']))
                        <a href="{{ route('admin.products.index') }}"
                            class="nav-item flex items-center gap-3 {{ request()->routeIs('admin.products.*') ? 'active text-white' : 'text-gray-600 hover:bg-gray-50' }} rounded-md px-4 py-3 font-semibold text-sm transition-colors">
                            <i class="fas fa-shopping-basket text-sm"></i>
                            Produk
                            @if (auth()->user()->user_type === 'produsen')
                                <span
                                    class="ml-auto bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Own</span>
                            @endif
                        </a>
                    @endif

                    <!-- Payment - ADMIN & PRODUSEN -->
                    @if (in_array(auth()->user()->user_type, ['admin', 'produsen']))
                        <a href="{{ route('admin.payments.index') }}"
                            class="nav-item flex items-center gap-3 {{ request()->routeIs('admin.payments.*') ? 'active text-white' : 'text-gray-600 hover:bg-gray-50' }} rounded-md px-4 py-3 font-semibold text-sm transition-colors">
                            <i class="fas fa-credit-card text-sm"></i>
                            Pembayaran
                            @if (auth()->user()->user_type === 'produsen')
                                <span
                                    class="ml-auto bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Own</span>
                            @endif
                        </a>
                    @endif

                    <!-- Pesanan - ADMIN, PRODUSEN, KURIR -->
                    @if (in_array(auth()->user()->user_type, ['admin', 'produsen', 'kurir']))
                        <a href="{{ route('admin.orders.index') }}"
                            class="nav-item flex items-center gap-3 {{ request()->routeIs('admin.orders.*') ? 'active text-white' : 'text-gray-600 hover:bg-gray-50' }} rounded-md px-4 py-3 font-semibold text-sm transition-colors">
                            <i class="fas fa-shopping-cart text-sm"></i>
                            Pesanan
                            @if (auth()->user()->user_type === 'produsen')
                                <span
                                    class="ml-auto bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Related</span>
                            @elseif(auth()->user()->user_type === 'kurir')
                                <span
                                    class="ml-auto bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">Delivery</span>
                            @endif
                        </a>
                    @endif


                    <!-- Pengiriman - ADMIN & KURIR -->
                    @if (in_array(auth()->user()->user_type, ['admin', 'kurir']))
                        <a href="{{ route('admin.deliveries.index') }}"
                            class="nav-item flex items-center gap-3 {{ request()->routeIs('admin.deliveries.*') ? 'active text-white' : 'text-gray-600 hover:bg-gray-50' }} rounded-md px-4 py-3 font-semibold text-sm transition-colors">
                            <i class="fas fa-truck text-sm"></i>
                            Pengiriman
                            @if (auth()->user()->user_type === 'kurir')
                                <span
                                    class="ml-auto bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Assigned</span>
                            @endif
                        </a>
                    @endif

                    <!-- Kategori - ONLY ADMIN -->
                    @if (auth()->user()->user_type === 'admin')
                        <a href="{{ route('admin.categories.index') }}"
                            class="nav-item flex items-center gap-3 {{ request()->routeIs('admin.categories.*') ? 'active text-white' : 'text-gray-600 hover:bg-gray-50' }} rounded-md px-4 py-3 font-semibold text-sm transition-colors">
                            <i class="fas fa-tags text-sm"></i>
                            Kategori
                        </a>
                    @endif

                    <!-- Manajemen User Dropdown - ONLY for ADMIN -->
                    @if (auth()->user()->user_type === 'admin')
                        <div class="relative" x-data="{ open: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="nav-item flex items-center gap-3 w-full {{ request()->routeIs('admin.users.*') ? 'active text-white' : 'text-gray-600 hover:bg-gray-50' }} rounded-md px-4 py-3 font-semibold text-sm transition-colors focus:outline-none">
                                <i class="fas fa-users-cog text-sm"></i>
                                <span class="flex-1 text-left">Manajemen User</span>
                                <svg class="w-3 h-3 text-current transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>

                            <!-- Submenu -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-2"
                                class="ml-4 mt-2 space-y-1 border-l-2 border-gray-100">

                                <!-- Setting Akun Users -->
                                <a href="{{ route('admin.users.index') }}"
                                    class="submenu-item flex items-center gap-3 pl-6 pr-4 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('admin.users.index', 'admin.users.show', 'admin.users.create', 'admin.users.edit')
                                        ? 'active bg-green-50 text-green-700 font-medium'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i
                                        class="fas fa-users text-xs {{ request()->routeIs('admin.users.index', 'admin.users.show', 'admin.users.create', 'admin.users.edit')
                                            ? 'text-green-600'
                                            : 'text-gray-400' }}"></i>
                                    <span class="flex-1">Setting Akun Users</span>
                                    @if (request()->routeIs('admin.users.index', 'admin.users.show', 'admin.users.create', 'admin.users.edit'))
                                        <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                                    @endif
                                </a>

                                <!-- Permission Menu Users -->
                                <a href="{{ route('admin.users.permissions.index') }}"
                                    class="submenu-item flex items-center gap-3 pl-6 pr-4 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('admin.users.permissions.*')
                                        ? 'active bg-green-50 text-green-700 font-medium'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i
                                        class="fas fa-key text-xs {{ request()->routeIs('admin.users.permissions.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                                    <span class="flex-1">Permission Menu Users</span>
                                    @if (request()->routeIs('admin.users.permissions.*'))
                                        <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                                    @endif
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- <!-- Laporan - ADMIN & PRODUSEN -->
                    @if (in_array(auth()->user()->user_type, ['admin', 'produsen']))
                        <div class="relative" x-data="{ open: {{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="nav-item flex items-center gap-3 w-full {{ request()->routeIs('admin.reports.*') ? 'active text-white' : 'text-gray-600 hover:bg-gray-50' }} rounded-md px-4 py-3 font-semibold text-sm transition-colors focus:outline-none">
                                <i class="fas fa-chart-line text-sm"></i>
                                <span class="flex-1 text-left">Laporan</span>
                                @if (auth()->user()->user_type === 'produsen')
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-2">Own</span>
                                @endif
                                <svg class="w-3 h-3 text-current transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>

                            <!-- Submenu -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-2"
                                class="ml-4 mt-2 space-y-1 border-l-2 border-gray-100">

                                <!-- Penjualan - ADMIN & PRODUSEN -->
                                <a href="{{ route('admin.reports.sales.index') }}"
                                    class="submenu-item flex items-center gap-3 pl-6 pr-4 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('admin.reports.sales.*')
                                        ? 'active bg-green-50 text-green-700 font-medium'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i
                                        class="fas fa-chart-bar text-xs {{ request()->routeIs('admin.reports.sales.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                                    <span class="flex-1">
                                        @if (auth()->user()->user_type === 'admin')
                                            Penjualan
                                        @else
                                            Penjualan Saya
                                        @endif
                                    </span>
                                    @if (request()->routeIs('admin.reports.sales.*'))
                                        <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                                    @endif
                                </a>

                                <!-- Inventori - ADMIN & PRODUSEN -->
                                <a href="{{ route('admin.reports.inventory.index') }}"
                                    class="submenu-item flex items-center gap-3 pl-6 pr-4 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('admin.reports.inventory.*')
                                        ? 'active bg-green-50 text-green-700 font-medium'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i
                                        class="fas fa-box text-xs {{ request()->routeIs('admin.reports.inventory.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                                    <span class="flex-1">
                                        @if (auth()->user()->user_type === 'admin')
                                            Inventori
                                        @else
                                            Stok Produk
                                        @endif
                                    </span>
                                    @if (request()->routeIs('admin.reports.inventory.*'))
                                        <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                                    @endif
                                </a>

                                <!-- Performance - ADMIN & PRODUSEN -->
                                <a href="{{ route('admin.reports.performance.index') }}"
                                    class="submenu-item flex items-center gap-3 pl-6 pr-4 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('admin.reports.performance.*')
                                        ? 'active bg-green-50 text-green-700 font-medium'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i
                                        class="fas fa-chart-pie text-xs {{ request()->routeIs('admin.reports.performance.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                                    <span class="flex-1">
                                        @if (auth()->user()->user_type === 'admin')
                                            Performa Platform
                                        @else
                                            Performa Produk
                                        @endif
                                    </span>
                                    @if (request()->routeIs('admin.reports.performance.*'))
                                        <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                                    @endif
                                </a>

                                <!-- Financial - ADMIN & PRODUSEN -->
                                <a href="{{ route('admin.reports.financial.index') }}"
                                    class="submenu-item flex items-center gap-3 pl-6 pr-4 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('admin.reports.financial.*')
                                        ? 'active bg-green-50 text-green-700 font-medium'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i
                                        class="fas fa-coins text-xs {{ request()->routeIs('admin.reports.financial.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                                    <span class="flex-1">
                                        @if (auth()->user()->user_type === 'admin')
                                            Keuangan
                                        @else
                                            Pendapatan
                                        @endif
                                    </span>
                                    @if (request()->routeIs('admin.reports.financial.*'))
                                        <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                                    @endif
                                </a>

                                <!-- Analytics - ADMIN & PRODUSEN -->
                                <a href="{{ route('admin.reports.analytics.index') }}"
                                    class="submenu-item flex items-center gap-3 pl-6 pr-4 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('admin.reports.analytics.*')
                                        ? 'active bg-green-50 text-green-700 font-medium'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i
                                        class="fas fa-chart-area text-xs {{ request()->routeIs('admin.reports.analytics.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                                    <span class="flex-1">
                                        @if (auth()->user()->user_type === 'admin')
                                            Analytics
                                        @else
                                            Analisis Produk
                                        @endif
                                    </span>
                                    @if (request()->routeIs('admin.reports.analytics.*'))
                                        <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                                    @endif
                                </a>

                                <!-- Users Report - ONLY ADMIN -->
                                @if (auth()->user()->user_type === 'admin')
                                    <a href="{{ route('admin.reports.users.index') }}"
                                        class="submenu-item flex items-center gap-3 pl-6 pr-4 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('admin.reports.users.*')
                                            ? 'active bg-green-50 text-green-700 font-medium'
                                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <i
                                            class="fas fa-users text-xs {{ request()->routeIs('admin.reports.users.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                                        <span class="flex-1">Pengguna</span>
                                        @if (request()->routeIs('admin.reports.users.*'))
                                            <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                                        @endif
                                    </a>

                                    <a href="{{ route('admin.reports.system.index') }}"
                                        class="submenu-item flex items-center gap-3 pl-6 pr-4 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('admin.reports.system.*')
                                            ? 'active bg-green-50 text-green-700 font-medium'
                                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <i
                                            class="fas fa-server text-xs {{ request()->routeIs('admin.reports.system.*') ? 'text-green-600' : 'text-gray-400' }}"></i>
                                        <span class="flex-1">Sistem</span>
                                        @if (request()->routeIs('admin.reports.system.*'))
                                            <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                                        @endif
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Pengaturan - ALL (but different access levels) -->
                    <a href="#"
                        class="nav-item flex items-center gap-3 text-gray-600 hover:bg-gray-50 rounded-md px-4 py-3 font-semibold text-sm transition-colors">
                        <i class="fas fa-cog text-sm"></i>
                        Pengaturan
                        @if (auth()->user()->user_type !== 'admin')
                            <span
                                class="ml-auto bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">Limited</span>
                        @endif
                    </a> --}}
                </nav>
            </div>

            <!-- Modern logout section -->
            <div class="px-6 py-6 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-3 text-gray-500 hover:text-red-600 text-sm font-semibold w-full transition-all duration-300 p-3 rounded-xl hover:bg-red-50">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                        <span>Keluar</span>
                        <i class="fas fa-arrow-right ml-auto opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Modern main content area -->
        <main class="flex-1 lg:ml-0 p-6 overflow-y-auto custom-scrollbar">
            <div class="max-w-full space-y-6">
                <!-- Modern top bar -->
                <div class="modern-card p-6 top-bar">
                    <div class="flex items-center justify-between">
                        <!-- Mobile Menu Button -->
                        <button @click="sidebarOpen = true"
                            class="lg:hidden modern-btn p-3 text-gray-600 hover:text-agriconnect-primary hover:bg-green-50">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <!-- Modern search -->
                        <form class="flex items-center w-full max-w-lg mx-4 lg:mx-0">
                            <div class="relative w-full modern-search">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input
                                    class="w-full bg-transparent py-3 pl-12 pr-4 text-sm placeholder:text-gray-400 focus:outline-none"
                                    placeholder="Cari apapun..." type="search">
                            </div>
                        </form>

                        <!-- Modern right side controls -->
                        <div class="flex items-center gap-4">
                            <!-- Modern Notifications -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="relative p-3 text-gray-600 hover:text-agriconnect-primary transition-colors modern-btn hover:bg-green-50">
                                    <i class="fas fa-bell text-xl"></i>
                                    <span class="absolute -top-1 -right-1 notification-badge text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                        3
                                    </span>
                                </button>

                                <!-- Modern notification dropdown -->
                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute right-0 mt-4 w-80 modern-dropdown z-50">
                                    <div class="p-5 border-b border-gray-100">
                                        <h3 class="font-semibold text-lg">Notifikasi</h3>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto custom-scrollbar">
                                        <div class="p-4 hover:bg-gray-50">
                                            <div class="flex items-start gap-3">
                                                <div class="w-2 h-2 bg-red-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium">Pesanan Baru</p>
                                                    <p class="text-xs text-gray-500 truncate">Pesanan #GF20250708001 dari
                                                        Petani Aceh</p>
                                                    <p class="text-xs text-gray-400 mt-1">5 menit yang lalu</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-4 hover:bg-gray-50">
                                            <div class="flex items-start gap-3">
                                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium">Produk Stok Rendah</p>
                                                    <p class="text-xs text-gray-500 truncate">Beras Pandan Wangi tersisa 5
                                                        kg</p>
                                                    <p class="text-xs text-gray-400 mt-1">1 jam yang lalu</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4 border-t border-gray-100">
                                        <a href="#" class="text-sm text-agriconnect-primary hover:underline">Lihat semua
                                            notifikasi</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Modern user profile -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 transition-all duration-300 min-w-0">
                                    <div class="relative">
                                        <img class="w-12 h-12 rounded-xl object-cover border-2 border-agriconnect-secondary shadow-lg"
                                            src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=8FBC8F&color=fff"
                                            alt="{{ auth()->user()->name }}">
                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                                    </div>
                                    <div class="text-left hidden sm:block min-w-0 flex-1">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-agriconnect-secondary font-medium">Administrator</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 hidden sm:block transition-transform duration-200"
                                         :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>

                                <!-- Modern profile dropdown -->
                                <div x-show="open" @click.away="open = false" x-transition
                                    class="profile-dropdown right-0 mt-4 w-72 modern-dropdown">
                                    <div class="p-5 border-b border-gray-100">
                                        <p class="font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-sm text-gray-500 break-words">{{ auth()->user()->email }}</p>
                                    </div>
                                    <div class="py-2">
                                        <a href="{{ route('profile.edit') }}"
                                            class="block px-5 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-user mr-3 w-4"></i>Profil
                                        </a>
                                        <a href="{{ route('home') }}" target="_blank"
                                            class="block px-5 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-external-link-alt mr-3 w-4"></i>Lihat Website
                                        </a>
                                        <hr class="my-1 border-gray-100">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="block w-full text-left px-5 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                <i class="fas fa-sign-out-alt mr-3 w-4"></i>Keluar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modern page header -->
                <div class="modern-card p-8">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-gray-600 text-lg">@yield('page-description', 'Kelola platform AgriConnect dengan mudah')</p>
                        </div>
                        <div class="flex items-center gap-3 flex-wrap">
                            @yield('page-actions')
                        </div>
                    </div>
                </div>

                <!-- Modern alerts -->
                @if (session('success'))
                    <div class="modern-card border-l-4 border-green-500 bg-gradient-to-r from-green-50 to-transparent p-6"
                         x-data="{ show: true }" x-show="show">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-green-800 font-semibold">Berhasil!</p>
                                    <p class="text-green-700">{{ session('success') }}</p>
                                </div>
                            </div>
                            <button @click="show = false" class="text-green-500 hover:text-green-700 modern-btn p-2">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="modern-card border-l-4 border-red-500 bg-gradient-to-r from-red-50 to-transparent p-6"
                         x-data="{ show: true }" x-show="show">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-exclamation-triangle text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-red-800 font-semibold">Terjadi Kesalahan!</p>
                                    <p class="text-red-700">{{ session('error') }}</p>
                                </div>
                            </div>
                            <button @click="show = false" class="text-red-500 hover:text-red-700 modern-btn p-2">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Main Content -->
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Close sidebar when clicking outside (mobile) -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: false,
                toggle() {
                    this.open = !this.open;
                },
                close() {
                    this.open = false;
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
