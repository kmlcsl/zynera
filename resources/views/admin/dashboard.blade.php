@extends('layouts.admin')

@section('title', $dashboardData['page_title'] . ' - Zynera Admin Panel')

@section('page-title', $dashboardData['page_title'])

@section('page-subtitle', $dashboardData['page_description'])

@section('content')
    <!-- Stats Cards -->
    <div class="stats-grid">
        @if(Auth::user()->user_type == 'admin')
            <!-- Admin Stats -->
            <div class="stat-card stat-card-1">
                <div class="stat-content">
                    <div class="stat-info">
                        <h3>Total Revenue</h3>
                        <div class="stat-value">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</div>
                        <div class="stat-change">
                            <i class="fas fa-arrow-up"></i>
                            <span>Dari pesanan selesai</span>
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
                        <h3>Pesanan Hari Ini</h3>
                        <div class="stat-value">{{ number_format($stats['total_orders'] ?? 0) }}</div>
                        <div class="stat-change">
                            <i class="fas fa-calendar-day"></i>
                            <span>Hari ini</span>
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
                        <h3>Total Pengguna</h3>
                        <div class="stat-value">{{ number_format($stats['total_users'] ?? 0) }}</div>
                        <div class="stat-change">
                            <i class="fas fa-users"></i>
                            <span>Semua pengguna</span>
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
                        <h3>Total Produk</h3>
                        <div class="stat-value">{{ number_format($stats['total_products'] ?? 0) }}</div>
                        <div class="stat-change">
                            <i class="fas fa-box"></i>
                            <span>Produk aktif: {{ number_format($stats['active_products'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-oil-can"></i>
                    </div>
                </div>
            </div>
        @elseif(Auth::user()->user_type == 'produsen')
            <!-- Producer Stats -->
            <div class="stat-card stat-card-1">
                <div class="stat-content">
                    <div class="stat-info">
                        <h3>Pendapatan Saya</h3>
                        <div class="stat-value">Rp {{ number_format($stats['my_revenue'] ?? 0, 0, ',', '.') }}</div>
                        <div class="stat-change">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Dari pesanan selesai</span>
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
                        <h3>Pesanan Masuk Hari Ini</h3>
                        <div class="stat-value">{{ number_format($stats['incoming_orders'] ?? 0) }}</div>
                        <div class="stat-change">
                            <i class="fas fa-calendar-day"></i>
                            <span>Pending: {{ number_format($stats['pending_orders'] ?? 0) }}</span>
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
                        <h3>Produk Saya</h3>
                        <div class="stat-value">{{ number_format($stats['my_products'] ?? 0) }}</div>
                        <div class="stat-change">
                            <i class="fas fa-check-circle"></i>
                            <span>Aktif: {{ number_format($stats['active_products'] ?? 0) }}</span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card stat-card-4">
                <div class="stat-content">
                    <div class="stat-info">
                        <h3>Stok Rendah</h3>
                        <div class="stat-value">{{ number_format($stats['low_stock_products'] ?? 0) }}</div>
                        <div class="stat-change">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>≤10 unit</span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-warehouse"></i>
                    </div>
                </div>
            </div>
        @elseif(Auth::user()->user_type == 'kurir')
            <!-- Courier Stats -->
            <div class="stat-card stat-card-1">
                <div class="stat-content">
                    <div class="stat-info">
                        <h3>Pengiriman Hari Ini</h3>
                        <div class="stat-value">{{ number_format($stats['today_deliveries'] ?? 0) }}</div>
                        <div class="stat-change">
                            <i class="fas fa-calendar-day"></i>
                            <span>Hari ini</span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card stat-card-2">
                <div class="stat-content">
                    <div class="stat-info">
                        <h3>Menunggu Pickup</h3>
                        <div class="stat-value">{{ number_format($stats['pending_deliveries'] ?? 0) }}</div>
                        <div class="stat-change">
                            <i class="fas fa-clock"></i>
                            <span>Dalam proses</span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card stat-card-3">
                <div class="stat-content">
                    <div class="stat-info">
                        <h3>Selesai</h3>
                        <div class="stat-value">{{ number_format($stats['completed_deliveries'] ?? 0) }}</div>
                        <div class="stat-change">
                            <i class="fas fa-check-circle"></i>
                            <span>Berhasil diantar</span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card stat-card-4">
                <div class="stat-content">
                    <div class="stat-info">
                        <h3>Rating Saya</h3>
                        <div class="stat-value">{{ number_format($stats['delivery_rating'] ?? 0, 1) }}</div>
                        <div class="stat-change">
                            <i class="fas fa-star"></i>
                            <span>Rating pelanggan</span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        @endif
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
                @if(Auth::user()->user_type == 'admin')
                    <!-- Recent Orders -->
                    @forelse($recentData['recent_orders'] ?? [] as $order)
                        <div class="activity-item">
                            <div class="activity-icon success">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="activity-content">
                                <h4>Pesanan Baru</h4>
                                <p>{{ $order->user->name ?? 'Unknown' }} - Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                <span class="activity-time">{{ $order->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                    @endforelse
                    
                    <!-- Recent Users -->
                    @forelse($recentData['recent_users'] ?? [] as $user)
                        <div class="activity-item">
                            <div class="activity-icon info">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="activity-content">
                                <h4>Pengguna Baru Terdaftar</h4>
                                <p>{{ $user->name }} ({{ ucfirst($user->user_type) }})</p>
                                <span class="activity-time">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                    @endforelse
                @elseif(Auth::user()->user_type == 'produsen')
                    <!-- Producer Recent Orders -->
                    @forelse($recentData['recent_orders'] ?? [] as $order)
                        <div class="activity-item">
                            <div class="activity-icon success">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="activity-content">
                                <h4>Pesanan untuk Produk Anda</h4>
                                <p>{{ $order->user->name ?? 'Unknown' }} - Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                <span class="activity-time">{{ $order->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                    @endforelse
                    
                    <!-- Low Stock Alert -->
                    @if(isset($additionalData['low_stock_products']))
                        @forelse($additionalData['low_stock_products'] as $product)
                            <div class="activity-item">
                                <div class="activity-icon warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Stok Rendah</h4>
                                    <p>{{ $product->name }} - Tersisa {{ $product->stock }} unit</p>
                                    <span class="activity-time">Peringatan stok</span>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    @endif
                @elseif(Auth::user()->user_type == 'kurir')
                    <!-- Courier Assigned Orders -->
                    @forelse($recentData['assigned_orders'] ?? [] as $delivery)
                        <div class="activity-item">
                            <div class="activity-icon info">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="activity-content">
                                <h4>Pengiriman Ditugaskan</h4>
                                <p>Pesanan {{ $delivery->order->order_number ?? 'N/A' }} - {{ $delivery->order->user->name ?? 'Unknown' }}</p>
                                <span class="activity-time">{{ $delivery->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                    @endforelse
                    
                    <!-- Delivery History -->
                    @forelse($recentData['delivery_history'] ?? [] as $delivery)
                        <div class="activity-item">
                            <div class="activity-icon success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="activity-content">
                                <h4>Pengiriman Selesai</h4>
                                <p>Pesanan {{ $delivery->order->order_number ?? 'N/A' }} berhasil diantar</p>
                                <span class="activity-time">{{ $delivery->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                    @endforelse
                @endif
                
                @if(empty($recentData) || (empty($recentData['recent_orders']) && empty($recentData['recent_users']) && empty($recentData['assigned_orders']) && empty($recentData['delivery_history'])))
                    <div class="activity-item">
                        <div class="activity-icon info">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Belum ada aktivitas</h4>
                            <p>Aktivitas terbaru akan muncul di sini</p>
                            <span class="activity-time">-</span>
                        </div>
                    </div>
                @endif
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
                <h4>Total Konsumen</h4>
                <span class="stat-number">{{ $stats['total_customers'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <h4>Total Produsen</h4>
                <span class="stat-number">{{ $stats['total_producers'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <h4>Total Kurir</h4>
                <span class="stat-number">{{ $stats['total_couriers'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <h4>Pesanan Pending</h4>
                <span class="stat-number">{{ $stats['pending_orders'] ?? 0 }}</span>
            </div>
        </div>
    </div>
    @elseif(Auth::user()->user_type == 'produsen')
    <div class="additional-stats">
        <div class="stat-row">
            <div class="stat-item">
                <h4>Total Pesanan</h4>
                <span class="stat-number">{{ $stats['my_orders'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <h4>Pesanan Pending</h4>
                <span class="stat-number">{{ $stats['pending_orders'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <h4>Produk Aktif</h4>
                <span class="stat-number">{{ $stats['active_products'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <h4>Stok Menipis</h4>
                <span class="stat-number">{{ $stats['low_stock_products'] ?? 0 }}</span>
            </div>
        </div>
    </div>
    @elseif(Auth::user()->user_type == 'kurir')
    <div class="additional-stats">
        <div class="stat-row">
            <div class="stat-item">
                <h4>Total Pengiriman</h4>
                <span class="stat-number">{{ $stats['assigned_deliveries'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <h4>Gagal Kirim</h4>
                <span class="stat-number">{{ $stats['failed_deliveries'] ?? 0 }}</span>
            </div>
            <div class="stat-item">
                <h4>On-Time Rate</h4>
                <span class="stat-number">{{ $additionalData['performance_metrics']['on_time_delivery'] ?? 0 }}%</span>
            </div>
            <div class="stat-item">
                <h4>Waktu Rata-rata</h4>
                <span class="stat-number">{{ $additionalData['performance_metrics']['avg_delivery_time'] ?? 0 }}h</span>
            </div>
        </div>
    </div>
    @endif

@endsection

@push('styles')
<style>
/* Dashboard CSS Fix untuk memastikan styling teraplikasi */
.stats-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)) !important;
    gap: 24px !important;
    margin-bottom: 32px !important;
}

.stat-info h3 {
    font-size: 14px !important;
    color: rgba(255, 255, 255, 0.9) !important;
    font-weight: 500 !important;
    margin-bottom: 8px !important;
    text-transform: none !important;
}

.stat-value {
    font-size: 36px !important;
    font-weight: 700 !important;
    color: white !important;
    margin-bottom: 8px !important;
    line-height: 1 !important;
}

.stat-change {
    display: flex !important;
    align-items: center !important;
    font-size: 12px !important;
    color: rgba(255, 255, 255, 0.8) !important;
}

.stat-change i {
    margin-right: 4px !important;
    font-size: 12px !important;
}

.stat-change span {
    font-size: 12px !important;
}

.activity-content h4 {
    font-size: 14px !important;
    font-weight: 500 !important;
    color: #1f2937 !important;
    margin-bottom: 4px !important;
}

.activity-content p {
    font-size: 12px !important;
    color: #6b7280 !important;
    margin-bottom: 4px !important;
    line-height: 1.4 !important;
}

.activity-time {
    font-size: 11px !important;
    color: #9ca3af !important;
    display: block !important;
}

.stat-item h4 {
    font-size: 14px !important;
    color: #6b7280 !important;
    font-weight: 500 !important;
    margin-bottom: 8px !important;
}

.stat-number {
    font-size: 24px !important;
    font-weight: 700 !important;
    color: #1f2937 !important;
    line-height: 1.2 !important;
}

.card-title {
    font-size: 18px !important;
    font-weight: 600 !important;
    color: #1f2937 !important;
    margin: 0 !important;
}

.chart-placeholder p {
    color: #6b7280 !important;
    margin-top: 8px !important;
    font-size: 14px !important;
}
</style>
@endpush

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