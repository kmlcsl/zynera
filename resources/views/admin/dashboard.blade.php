@extends('layouts.admin')

@section('title', 'Dashboard - Zynera Admin Panel')

@section('page-title', 'Dashboard')

@section('page-subtitle', 'Selamat datang di Zynera - Platform Minyak Jelantah')

@section('content')
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-card-1">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>Total Penjualan</h3>
                    <div class="stat-value">Rp {{ number_format($stats['total_sales'] ?? 24567000, 0, ',', '.') }}</div>
                    <div class="stat-change">
                        <i class="fas fa-arrow-up"></i>
                        <span>+{{ $stats['sales_change'] ?? '12.5' }}%</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-2">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ Auth::user()->user_type == 'admin' ? 'Total Pesanan' : 'Pesanan Saya' }}</h3>
                    <div class="stat-value">{{ number_format($stats['total_orders'] ?? 1423) }}</div>
                    <div class="stat-change">
                        <i class="fas fa-arrow-up"></i>
                        <span>+{{ $stats['orders_change'] ?? '8.2' }}%</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-3">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ Auth::user()->user_type == 'admin' ? 'Total Pengguna' : 'Pelanggan' }}</h3>
                    <div class="stat-value">{{ number_format($stats['total_users'] ?? 8967) }}</div>
                    <div class="stat-change">
                        <i class="fas fa-arrow-up"></i>
                        <span>+{{ $stats['users_change'] ?? '15.3' }}%</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-4">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>{{ Auth::user()->user_type == 'admin' ? 'Produk Minyak' : 'Stok Minyak' }}</h3>
                    <div class="stat-value">{{ number_format($stats['total_products'] ?? 234) }} L</div>
                    <div class="stat-change">
                        <i class="fas fa-arrow-{{ ($stats['product_change'] ?? -2.1) >= 0 ? 'up' : 'down' }}" style="color: {{ ($stats['product_change'] ?? -2.1) >= 0 ? '#34d399' : '#fca5a5' }};"></i>
                        <span>{{ ($stats['product_change'] ?? -2.1) >= 0 ? '+' : '' }}{{ $stats['product_change'] ?? '-2.1' }}%</span>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-oil-can"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Chart -->
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">{{ Auth::user()->user_type == 'admin' ? 'Ringkasan Penjualan Minyak Jelantah' : 'Performa Penjualan' }}</h3>
                <div class="chart-filters">
                    <button class="chart-filter-btn active" onclick="setChartFilter('week', this)">Minggu</button>
                    <button class="chart-filter-btn" onclick="setChartFilter('month', this)">Bulan</button>
                    <button class="chart-filter-btn" onclick="setChartFilter('year', this)">Tahun</button>
                </div>
            </div>
            <div class="chart-placeholder" id="chartContainer">
                <i class="fas fa-chart-area"></i>
                <p style="color: #6b7280; margin-top: 8px;">Grafik Analisis Penjualan</p>
                <p style="color: #9ca3af; font-size: 12px; margin-top: 4px;">Data akan ditampilkan di sini</p>
            </div>
        </div>

        <!-- Activity Feed -->
        <div class="content-card">
            <div class="card-header">
                <h3 class="card-title">Aktivitas Terbaru</h3>
                <button class="refresh-btn" onclick="refreshActivity()">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <div class="activity-list" id="activityList">
                @forelse($activities ?? [] as $activity)
                    <div class="activity-item">
                        <div class="activity-icon {{ $activity['type'] }}">
                            <i class="fas {{ $activity['icon'] }}"></i>
                        </div>
                        <div class="activity-content">
                            <h4>{{ $activity['title'] }}</h4>
                            <p>{{ $activity['description'] }}</p>
                            <span class="activity-time">{{ $activity['time'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Pesanan minyak jelantah baru</h4>
                            <p>Pesanan #ZYN-{{ rand(1000,9999) }} - Rp {{ number_format(rand(50000,500000), 0, ',', '.') }}</p>
                            <span class="activity-time">2 menit yang lalu</span>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon info">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <h4>{{ Auth::user()->user_type == 'admin' ? 'Pengguna baru terdaftar' : 'Pelanggan baru bergabung' }}</h4>
                            <p>{{ Auth::user()->user_type == 'admin' ? 'supplier.minyak@example.com' : 'Pelanggan #987' }}</p>
                            <span class="activity-time">15 menit yang lalu</span>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="activity-content">
                            <h4>{{ Auth::user()->user_type == 'admin' ? 'Stok minyak menipis' : 'Peringatan inventori' }}</h4>
                            <p>Minyak Jelantah Grade A - Tersisa 5 liter</p>
                            <span class="activity-time">1 jam yang lalu</span>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon purple">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Pengiriman selesai</h4>
                            <p>{{ Auth::user()->user_type == 'admin' ? 'Pengiriman minyak jelantah ke Jakarta' : 'Pesanan Anda telah diterima' }}</p>
                            <span class="activity-time">3 jam yang lalu</span>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3 class="card-title">Aksi Cepat</h3>
        
        <!-- Admin Actions -->
        @if(Auth::user()->user_type == 'admin')
        <div id="adminActions" class="actions-grid">
            <button class="action-btn action-blue" onclick="openModal('addUser')">
                <i class="fas fa-user-plus"></i>
                <span>Tambah Pengguna</span>
            </button>
            <button class="action-btn action-green" onclick="openModal('addProduct')">
                <i class="fas fa-oil-can"></i>
                <span>Tambah Produk Minyak</span>
            </button>
            <a href="{{ route('admin.reports.analytics.index') }}" class="action-btn action-purple">
                <i class="fas fa-chart-bar"></i>
                <span>Lihat Laporan</span>
            </a>
            <a href="#" class="action-btn action-gray">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
            <button class="action-btn action-red" onclick="toggleNotifications()">
                <i class="fas fa-bell"></i>
                <span>Notifikasi</span>
            </button>
            <button class="action-btn action-indigo" onclick="openHelp()">
                <i class="fas fa-question-circle"></i>
                <span>Bantuan</span>
            </button>
        </div>
        @endif

        <!-- Producer Actions -->
        @if(Auth::user()->user_type == 'produsen')
        <div id="producerActions" class="actions-grid">
            <button class="action-btn action-orange" onclick="openModal('updateStock')">
                <i class="fas fa-warehouse"></i>
                <span>Update Stok Minyak</span>
            </button>
            <button class="action-btn action-teal" onclick="openModal('addCustomer')">
                <i class="fas fa-handshake"></i>
                <span>Tambah Pelanggan</span>
            </button>
            <a href="#" class="action-btn action-pink">
                <i class="fas fa-chart-line"></i>
                <span>Laporan Penjualan</span>
            </a>
            <a href="#" class="action-btn action-gray">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
            <button class="action-btn action-red" onclick="toggleNotifications()">
                <i class="fas fa-bell"></i>
                <span>Notifikasi</span>
            </button>
            <button class="action-btn action-indigo" onclick="openHelp()">
                <i class="fas fa-question-circle"></i>
                <span>Bantuan</span>
            </button>
        </div>
        @endif

        <!-- Kurir Actions -->
        @if(Auth::user()->user_type == 'kurir')
        <div id="kurirActions" class="actions-grid">
            <button class="action-btn action-blue" onclick="openModal('checkDelivery')">
                <i class="fas fa-clipboard-check"></i>
                <span>Cek Pengiriman</span>
            </button>
            <button class="action-btn action-green" onclick="openModal('updateLocation')">
                <i class="fas fa-map-marker-alt"></i>
                <span>Update Lokasi</span>
            </button>
            <a href="#" class="action-btn action-orange">
                <i class="fas fa-route"></i>
                <span>Rute Pengiriman</span>
            </a>
            <a href="#" class="action-btn action-purple">
                <i class="fas fa-history"></i>
                <span>Riwayat Pengiriman</span>
            </a>
        </div>
        @endif
    </div>

    <!-- Quick Stats (Additional) -->
    @if(Auth::user()->user_type == 'admin')
    <div class="additional-stats">
        <div class="stat-row">
            <div class="stat-item">
                <h4>Pesanan Hari Ini</h4>
                <span class="stat-number">{{ $stats['today_orders'] ?? 45 }}</span>
            </div>
            <div class="stat-item">
                <h4>Perlu Review</h4>
                <span class="stat-number">{{ $stats['pending_reviews'] ?? 12 }}</span>
            </div>
            <div class="stat-item">
                <h4>Pengguna Aktif</h4>
                <span class="stat-number">{{ $stats['active_users'] ?? 234 }}</span>
            </div>
            <div class="stat-item">
                <h4>Stok Tersedia</h4>
                <span class="stat-number">{{ $stats['available_stock'] ?? 1267 }}L</span>
            </div>
        </div>
    </div>
    @else
    <div class="additional-stats">
        <div class="stat-row">
            <div class="stat-item">
                <h4>Penjualan Hari Ini</h4>
                <span class="stat-number">{{ $stats['today_sales'] ?? 23 }}L</span>
            </div>
            <div class="stat-item">
                <h4>Stok Menipis</h4>
                <span class="stat-number">{{ $stats['low_stock'] ?? 8 }}</span>
            </div>
            <div class="stat-item">
                <h4>Pelanggan Baru</h4>
                <span class="stat-number">{{ $stats['new_customers'] ?? 15 }}</span>
            </div>
            <div class="stat-item">
                <h4>Tingkat Penyelesaian</h4>
                <span class="stat-number">{{ $stats['completion_rate'] ?? 92 }}%</span>
            </div>
        </div>
    </div>
    @endif

@endsection

@push('scripts')
<script>
    // Additional dashboard-specific JavaScript
    function setChartFilter(period, element) {
        // Remove active class from all buttons
        document.querySelectorAll('.chart-filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Add active class to clicked button
        element.classList.add('active');
        
        // Show loading state
        const chartContainer = document.getElementById('chartContainer');
        chartContainer.innerHTML = `
            <i class="fas fa-spinner fa-spin"></i>
            <p style="color: #6b7280; margin-top: 8px;">Memuat data ${period}...</p>
        `;
        
        // Simulate loading
        setTimeout(() => {
            chartContainer.innerHTML = `
                <i class="fas fa-chart-area"></i>
                <p style="color: #6b7280; margin-top: 8px;">Grafik periode ${period}</p>
                <p style="color: #9ca3af; font-size: 12px; margin-top: 4px;">Data berhasil dimuat</p>
            `;
        }, 1000);
    }
    
    function refreshActivity() {
        const refreshBtn = document.querySelector('.refresh-btn i');
        refreshBtn.classList.add('fa-spin');
        
        // Simulate refresh
        setTimeout(() => {
            refreshBtn.classList.remove('fa-spin');
        }, 1000);
    }
    
    function openModal(type) {
        alert(`Membuka ${type} modal...`);
    }
    
    function openHelp() {
        window.open('#', '_blank');
    }
    
    // Auto-refresh activities every 5 minutes
    setInterval(refreshActivity, 5 * 60 * 1000);
</script>
@endpush