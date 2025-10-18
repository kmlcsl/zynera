@extends('layouts.admin')

@section('title', 'Laporan Performa')
@section('page-title')
    @if (auth()->user()->user_type === 'admin')
        Laporan Performa Platform
    @else
        Laporan Performa Produk
    @endif
@endsection
@section('page-description')
    @if (auth()->user()->user_type === 'admin')
        Analisis performa keseluruhan platform Zynera
    @else
        Analisis performa penjualan dan stok produk Anda
    @endif
@endsection

@section('page-actions')
    <div class="flex items-center gap-3">
        <button onclick="generateInsights()"
            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
            <i class="fas fa-lightbulb mr-2"></i>
            Generate Insights
        </button>

        <form method="POST" action="{{ route('admin.reports.performance.export') }}" class="inline">
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
    </div>
@endsection

@section('content')
    <!-- Period Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6 mb-6">
        <form method="GET" action="{{ route('admin.reports.performance.index') }}"
            class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.reports.performance.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Performance Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Performa Produk</p>
                    <p class="text-2xl font-bold text-green-900">{{ $metrics['product_performance'] }}%</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>Produk aktif
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
                    <p class="text-sm text-gray-600">Revenue per Produk</p>
                    <p class="text-2xl font-bold text-blue-900">Rp {{ number_format($metrics['revenue_per_product'], 0, ',', '.') }}</p>
                    <p class="text-sm text-blue-600 mt-1">
                        <i class="fas fa-money-bill-wave mr-1"></i>Rata-rata
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pesanan per Hari</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $metrics['orders_per_day'] }}</p>
                    <p class="text-sm text-purple-600 mt-1">
                        <i class="fas fa-calendar-day mr-1"></i>Rata-rata harian
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-day text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Stock Turnover</p>
                    <p class="text-2xl font-bold text-orange-900">{{ $metrics['stock_turnover'] }}x</p>
                    <p class="text-sm text-orange-600 mt-1">
                        <i class="fas fa-sync-alt mr-1"></i>Perputaran stok
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-sync-alt text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Insights Alert -->
    <div id="insights-container" class="hidden mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Performance Insights</h3>
            <div id="insights-content" class="space-y-3">
                <!-- Insights will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Performance Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Sales Performance -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Performa Penjualan</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                    <div>
                        <p class="text-sm text-gray-600">Total Revenue</p>
                        <p class="text-xl font-bold text-green-900">Rp {{ number_format($salesOverview['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Completion Rate</p>
                        <p class="text-xl font-bold text-green-900">{{ $salesOverview['completion_rate'] }}%</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-gray-50 rounded-lg text-center">
                        <p class="text-sm text-gray-600">Total Orders</p>
                        <p class="text-lg font-bold text-gray-900">{{ $salesOverview['total_orders'] }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg text-center">
                        <p class="text-sm text-gray-600">Avg Order Value</p>
                        <p class="text-lg font-bold text-gray-900">Rp {{ number_format($salesOverview['average_order_value'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Performance -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Performa Inventori</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div>
                        <p class="text-sm text-gray-600">Total Products</p>
                        <p class="text-xl font-bold text-blue-900">{{ $inventoryOverview['total_products'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Active Products</p>
                        <p class="text-xl font-bold text-blue-900">{{ $inventoryOverview['active_products'] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <div class="p-3 bg-green-50 rounded-lg text-center">
                        <p class="text-xs text-gray-600">In Stock</p>
                        <p class="text-sm font-bold text-green-900">{{ $inventoryOverview['in_stock'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-lg text-center">
                        <p class="text-xs text-gray-600">Low Stock</p>
                        <p class="text-sm font-bold text-yellow-900">{{ $inventoryOverview['low_stock'] }}</p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-lg text-center">
                        <p class="text-xs text-gray-600">Out of Stock</p>
                        <p class="text-sm font-bold text-red-900">{{ $inventoryOverview['out_of_stock'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Performance Trend -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Performa Bulanan</h3>
        <div class="space-y-3">
            @forelse($salesByPeriod as $period)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">{{ $period->period }}</p>
                        <p class="text-sm text-gray-500">{{ $period->orders }} pesanan</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($period->revenue, 0, ',', '.') }}</p>
                        <div class="w-20 h-2 bg-gray-200 rounded-full mt-1">
                            @php
                                $maxRevenue = $salesByPeriod->max('revenue');
                                $percentage = $maxRevenue > 0 ? ($period->revenue / $maxRevenue) * 100 : 0;
                            @endphp
                            <div class="h-2 bg-blue-500 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-chart-line text-4xl mb-2"></i>
                    <p>Tidak ada data performa</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Top Performing Products -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Produk Terbaik</h3>
        <div class="space-y-4">
            @forelse($topProducts as $index => $product)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 {{ $index === 0 ? 'bg-yellow-100' : ($index === 1 ? 'bg-gray-100' : 'bg-orange-100') }} rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium {{ $index === 0 ? 'text-yellow-600' : ($index === 1 ? 'text-gray-600' : 'text-orange-600') }}">{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $product->product->name ?? 'Produk Tidak Ditemukan' }}</p>
                            <p class="text-sm text-gray-500">{{ $product->total_quantity }} terjual</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                        <div class="flex items-center gap-1 mt-1">
                            @for($i = 0; $i < min(5, ceil($product->total_quantity / 10)); $i++)
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            @endfor
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-trophy text-4xl mb-2"></i>
                    <p>Tidak ada data produk terbaik</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function generateInsights() {
            const insightsContainer = document.getElementById('insights-container');
            const insightsContent = document.getElementById('insights-content');

            // Show loading
            insightsContainer.classList.remove('hidden');
            insightsContent.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-gray-400"></i> <span class="ml-2">Generating insights...</span></div>';

            // Make AJAX request
            fetch('{{ route("admin.reports.performance.insights") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    start_date: '{{ $startDate }}',
                    end_date: '{{ $endDate }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let html = '';
                    if (data.insights && data.insights.length > 0) {
                        data.insights.forEach(insight => {
                            const iconClass = insight.type === 'warning' ? 'fa-exclamation-triangle text-yellow-500' :
                                            insight.type === 'danger' ? 'fa-exclamation-circle text-red-500' :
                                            'fa-info-circle text-blue-500';

                            html += `
                                <div class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg">
                                    <i class="fas ${iconClass} mt-1"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">${insight.title}</h4>
                                        <p class="text-sm text-gray-600">${insight.message}</p>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html = '<div class="text-center text-gray-500 py-4"><i class="fas fa-check-circle text-green-500 text-2xl"></i><p class="mt-2">Semua performa dalam kondisi baik!</p></div>';
                    }

                    insightsContent.innerHTML = html;
                } else {
                    insightsContent.innerHTML = '<div class="text-center text-red-500 py-4"><i class="fas fa-exclamation-triangle"></i> <span class="ml-2">Error: ' + (data.message || 'Unknown error') + '</span></div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                insightsContent.innerHTML = '<div class="text-center text-red-500 py-4"><i class="fas fa-exclamation-triangle"></i> <span class="ml-2">Error generating insights</span></div>';
            });
        }
    </script>
@endsection

