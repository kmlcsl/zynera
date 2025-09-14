@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')
@section('page-description')
    @if (auth()->user()->user_type === 'admin')
        Analisis penjualan keseluruhan platform AgriConnect
    @else
        Analisis penjualan produk Anda
    @endif
@endsection

@section('page-actions')
    <div class="flex items-center gap-3">
        <form method="POST" action="{{ route('admin.reports.sales.export') }}" class="inline">
            @csrf
            <input type="hidden" name="start_date" value="{{ $startDate }}">
            <input type="hidden" name="end_date" value="{{ $endDate }}">
            <input type="hidden" name="format" value="csv">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700">
                <i class="fas fa-file-csv mr-2"></i>
                Export CSV
            </button>
        </form>

        <button onclick="refreshReport()"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700">
            <i class="fas fa-sync-alt mr-2"></i>
            Refresh
        </button>
    </div>
@endsection

@section('content')
    <!-- Period Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6 mb-6">
        <form method="GET" action="{{ route('admin.reports.sales.index') }}"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="period" class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <select name="period" id="period" onchange="toggleCustomDate()"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="yesterday" {{ request('period') == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                    <option value="this_week" {{ request('period') == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="last_week" {{ request('period') == 'last_week' ? 'selected' : '' }}>Minggu Lalu</option>
                    <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>Bulan Lalu</option>
                    <option value="this_year" {{ request('period') == 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                    <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>

            <div id="date_from_container" style="display: {{ request('period') == 'custom' ? 'block' : 'none' }};">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
            </div>

            <div id="date_to_container" style="display: {{ request('period') == 'custom' ? 'block' : 'none' }};">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.reports.sales.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Penjualan</p>
                    <p class="text-2xl font-bold text-gray-900">Rp
                        {{ number_format($overview['total_revenue'], 0, ',', '.') }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-chart-line mr-1"></i>{{ $overview['total_orders'] }} pesanan
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $overview['total_orders'] }}</p>
                    <p class="text-sm text-blue-600 mt-1">
                        <i class="fas fa-check-circle mr-1"></i>{{ $overview['completed_orders'] }} selesai
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Rata-rata Order</p>
                    <p class="text-2xl font-bold text-gray-900">Rp
                        {{ number_format($overview['average_order_value'], 0, ',', '.') }}</p>
                    <p class="text-sm text-purple-600 mt-1">
                        <i class="fas fa-calculator mr-1"></i>Per pesanan
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calculator text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tingkat Penyelesaian</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $overview['completion_rate'] }}%</p>
                    <p class="text-sm text-orange-600 mt-1">
                        <i class="fas fa-percentage mr-1"></i>Pesanan selesai
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-pie text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Sales Trend Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tren Penjualan ({{ ucfirst($period) }})</h3>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">{{ count($salesByPeriod) }} periode</span>
                </div>
            </div>
            <div class="space-y-3">
                @forelse($salesByPeriod as $sale)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $sale->period }}</p>
                            <p class="text-xs text-gray-500">{{ $sale->total_orders }} pesanan</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">Rp
                                {{ number_format($sale->total_revenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-chart-line text-4xl mb-2"></i>
                        <p>Tidak ada data penjualan</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Product Performance -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Produk Terlaris</h3>
                <span class="text-sm text-gray-500">Top {{ count($topProducts) }}</span>
            </div>
            <div class="space-y-4">
                @forelse($topProducts as $index => $product)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 {{ $index === 0 ? 'bg-green-100' : ($index === 1 ? 'bg-blue-100' : 'bg-purple-100') }} rounded-full flex items-center justify-center">
                                <span
                                    class="text-sm font-medium {{ $index === 0 ? 'text-green-600' : ($index === 1 ? 'text-blue-600' : 'text-purple-600') }}">{{ $index + 1 }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ $product->product->name ?? 'Produk Tidak Ditemukan' }}</p>
                                <p class="text-sm text-gray-500">{{ $product->total_quantity }} terjual</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">Rp
                                {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-box text-4xl mb-2"></i>
                        <p>Tidak ada data produk</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sales by Status -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Penjualan Berdasarkan Status</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($salesByStatus as $status)
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $status->status)) }}</p>
                            <p class="text-xl font-bold text-gray-900">{{ $status->count }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">Rp
                                {{ number_format($status->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center text-gray-500 py-8">
                    <p>Tidak ada data status penjualan</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function toggleCustomDate() {
            const period = document.getElementById('period').value;
            const dateFromContainer = document.getElementById('date_from_container');
            const dateToContainer = document.getElementById('date_to_container');

            if (period === 'custom') {
                dateFromContainer.style.display = 'block';
                dateToContainer.style.display = 'block';
            } else {
                dateFromContainer.style.display = 'none';
                dateToContainer.style.display = 'none';
            }
        }

        function refreshReport() {
            location.reload();
        }
    </script>
@endsection
