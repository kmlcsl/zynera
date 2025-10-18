<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Zynera Admin Panel - Minyak Jelantah Marketplace')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Mobile Overlay -->
        <div id="mobile-overlay" class="mobile-overlay" onclick="closeSidebar()"></div>

        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <!-- Logo -->
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <div class="sidebar-logo-text">
                        <h1>Zynera</h1>
                        <p>Minyak Jelantah</p>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            <div class="sidebar-profile">
                <div class="profile-info">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-details">
                        <h3 id="userName">{{ Auth::user()->name ?? 'User' }}</h3>
                        <p id="userRole">{{ ucfirst(Auth::user()->user_type ?? 'admin') }}</p>
                        <div class="profile-status">
                            <div class="status-dot"></div>
                            <span style="font-size: 12px;">Online</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="sidebar-nav">
                <!-- Dashboard - Accessible by ALL admin panel users -->
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Produk - ADMIN & PRODUSEN -->
                @if (in_array(auth()->user()->user_type, ['admin', 'produsen']))
                    <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" data-tooltip="Minyak Jelantah">
                        <i class="fas fa-oil-can"></i>
                        <span>Minyak Jelantah</span>
                    </a>
                @endif

                <!-- Payment - ADMIN & PRODUSEN -->
                @if (in_array(auth()->user()->user_type, ['admin', 'produsen']))
                    <a href="{{ route('admin.payments.index') }}" class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" data-tooltip="Pembayaran">
                        <i class="fas fa-receipt"></i>
                        <span>Pembayaran</span>
                    </a>
                @endif

                <!-- Pesanan - ADMIN, PRODUSEN, KURIR -->
                @if (in_array(auth()->user()->user_type, ['admin', 'produsen', 'kurir']))
                    <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" data-tooltip="Pesanan">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Pesanan</span>
                    </a>
                @endif

                <!-- Pengiriman - ADMIN & KURIR -->
                @if (in_array(auth()->user()->user_type, ['admin', 'kurir']))
                    <a href="{{ route('admin.deliveries.index') }}" class="nav-item {{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}" data-tooltip="Pengiriman">
                        <i class="fas fa-truck"></i>
                        <span>Pengiriman</span>
                    </a>
                @endif

                <!-- Kategori - ONLY ADMIN -->
                @if (auth()->user()->user_type === 'admin')
                    <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" data-tooltip="Kategori">
                        <i class="fas fa-tags"></i>
                        <span>Kategori</span>
                    </a>
                @endif

                <!-- Manajemen User - ONLY ADMIN -->
                @if (auth()->user()->user_type === 'admin')
                    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" data-tooltip="Manajemen User">
                        <i class="fas fa-users-cog"></i>
                        <span>Manajemen User</span>
                    </a>
                @endif

                <!-- Analytics - ADMIN -->
                @if (auth()->user()->user_type === 'admin')
                    <a href="#" class="nav-item" data-tooltip="Analytics">
                        <i class="fas fa-chart-bar"></i>
                        <span>Analytics</span>
                    </a>
                @endif

                <!-- Settings - ALL -->
                <a href="#" class="nav-item" data-tooltip="Settings">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </div>

            <!-- Logout -->
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-content">
                    <div class="header-left">
                        <button class="mobile-menu-btn" onclick="toggleSidebar()">
                            <i class="fas fa-bars"></i>
                        </button>

                        <button class="desktop-toggle-btn" onclick="toggleSidebarCollapse()">
                            <i id="sidebarToggleIcon" class="fas fa-bars"></i>
                        </button>

                        <div class="page-title">
                            <h2 id="pageTitle">@yield('page-title', 'Dashboard') - {{ ucfirst(Auth::user()->user_type ?? 'admin') }}</h2>
                            <p class="page-subtitle">@yield('page-description', 'Selamat datang di Zynera Minyak Jelantah Marketplace')</p>
                        </div>
                    </div>

                    <div class="header-actions">
                        <!-- Notifications -->
                        <div class="notification-dropdown">
                            <button class="notification-btn" onclick="toggleNotifications()">
                                <i class="fas fa-bell"></i>
                                @if(($notifications ?? 0) > 0)
                                    <span class="notification-badge">{{ $notifications ?? 0 }}</span>
                                @endif
                            </button>

                            <!-- Notification Dropdown -->
                            <div id="notificationDropdown" class="notification-dropdown-content">
                                <div class="notification-header">
                                    <h3>Notifications</h3>
                                </div>
                                <div class="notification-list">
                                    <div class="notification-item">
                                        <p>Pesanan baru diterima</p>
                                        <span>2 menit yang lalu</span>
                                    </div>
                                    <div class="notification-item">
                                        <p>Stok minyak jelantah rendah</p>
                                        <span>1 jam yang lalu</span>
                                    </div>
                                    <div class="notification-item">
                                        <p>Pembayaran terverifikasi</p>
                                        <span>3 jam yang lalu</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="user-menu-dropdown">
                            <button class="user-menu-btn" onclick="toggleUserMenu()">
                                <div class="user-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <i class="fas fa-chevron-down" style="font-size: 12px; color: #6b7280;"></i>
                            </button>

                            <!-- User Dropdown -->
                            <div id="userDropdown" class="user-dropdown-content">
                                <a href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-circle"></i>
                                    Profile
                                </a>
                                <a href="#">
                                    <i class="fas fa-cog"></i>
                                    Settings
                                </a>
                                <hr>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-logout">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Actions (if any) -->
            @hasSection('page-actions')
                <div class="bg-white border-b border-gray-200">
                    <div class="content-wrapper">
                        <div class="flex items-center justify-end">
                            @yield('page-actions')
                        </div>
                    </div>
                </div>
            @endif

            <!-- Content -->
            <div class="dashboard-content min-h-screen bg-gray-50">
                @if (session('success'))
                    <div class="alert alert-success">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            {{ session('status') }}
                        </div>
                    </div>
                @endif

                <div class="content-wrapper">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
