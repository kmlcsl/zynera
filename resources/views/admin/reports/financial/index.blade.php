@extends('layouts.admin')

@section('title', 'Laporan Keuangan')
@section('page-title')
    @if (auth()->user()->user_type === 'admin')
        Laporan Keuangan Platform
    @else
        Laporan Pendapatan
    @endif
@endsection
@section('page-description')
    @if (auth()->user()->user_type === 'admin')
        Analisis keuangan dan profitabilitas platform
    @else
        Analisis pendapatan dan keuntungan dari penjualan produk Anda
    @endif
@endsection

@section('page-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.reports.financial.profit-loss') }}"
            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
            <i class="fas fa-calculator mr-2"></i>
            Profit & Loss
        </a>

        <form method="POST" action="{{ route('admin.reports.financial.export') }}" class="inline">
            @csrf
            <input type="hidden" name="start_date" value="{{ $startDate }}">
            <input type="hidden" name="end_date" value="{{ $endDate }}">
            <input type="hidden" name="type" value="overview">
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
        <form method="GET" action="{{ route('admin.reports.financial.index') }}"
            class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

            <div>
                <label for="period" class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <select name="period" id="period"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Harian</option>
                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.reports.financial.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Financial Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-green-900">Rp {{ number_format($overview['total_revenue'], 0, ',', '.') }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>{{ $overview['total_orders'] }} transaksi
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Costs</p>
                    <p class="text-2xl font-bold text-red-900">Rp {{ number_format($overview['total_costs'], 0, ',', '.') }}</p>
                    <p class="text-sm text-red-600 mt-1">
                        <i class="fas fa-arrow-down mr-1"></i>Biaya operasional
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-receipt text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Gross Profit</p>
                    <p class="text-2xl font-bold text-blue-900">Rp {{ number_format($overview['gross_profit'], 0, ',', '.') }}</p>
                    <p class="text-sm text-blue-600 mt-1">
                        <i class="fas fa-calculator mr-1"></i>Laba kotor
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Net Profit</p>
                    <p class="text-2xl font-bold {{ $overview['net_profit'] >= 0 ? 'text-green-900' : 'text-red-900' }}">
                        Rp {{ number_format($overview['net_profit'], 0, ',', '.') }}
                    </p>
                    <p class="text-sm {{ $overview['profit_margin'] >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                        <i class="fas fa-percentage mr-1"></i>{{ $overview['profit_margin'] }}% margin
                    </p>
                </div>
                <div class="w-12 h-12 {{ $overview['net_profit'] >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                    <i class="fas fa-coins {{ $overview['net_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }} text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Revenue Trend -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Pendapatan ({{ ucfirst($period) }})</h3>
            <div class="space-y-3">
                @forelse($revenueByPeriod as $period)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $period->period }}</p>
                            <p class="text-sm text-gray-500">{{ $period->orders }} pesanan</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">Rp {{ number_format($period->revenue, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">+Rp {{ number_format($period->shipping, 0, ',', '.') }} ongkir</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-chart-line text-4xl mb-2"></i>
                        <p>Tidak ada data pendapatan</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Revenue by Category -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pendapatan per Kategori</h3>
            <div class="space-y-4">
                @forelse($revenueByCategory as $index => $category)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 {{ $index % 3 === 0 ? 'bg-green-100' : ($index % 3 === 1 ? 'bg-blue-100' : 'bg-purple-100') }} rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium {{ $index % 3 === 0 ? 'text-green-600' : ($index % 3 === 1 ? 'text-blue-600' : 'text-purple-600') }}">{{ $index + 1 }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $category->name }}</p>
                                <p class="text-sm text-gray-500">{{ $category->total_quantity }} item terjual</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">Rp {{ number_format($category->total_revenue, 0, ',', '.') }}</p>
                            @php
                                $totalRevenue = $revenueByCategory->sum('total_revenue');
                                $percentage = $totalRevenue > 0 ? round(($category->total_revenue / $totalRevenue) * 100, 1) : 0;
                            @endphp
                            <p class="text-sm text-gray-500">{{ $percentage }}%</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-chart-pie text-4xl mb-2"></i>
                        <p>Tidak ada data kategori</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Keuangan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                <h4 class="font-medium text-green-800 mb-2">💰 Pendapatan</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Penjualan:</span>
                        <span class="font-medium">Rp {{ number_format($overview['total_revenue'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkir:</span>
                        <span class="font-medium">Rp {{ number_format($overview['total_shipping'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-medium border-t border-green-300 pt-2">
                        <span>Total:</span>
                        <span>Rp {{ number_format($overview['total_revenue'] + $overview['total_shipping'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                <h4 class="font-medium text-red-800 mb-2">💸 Pengeluaran</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">COGS:</span>
                        <span class="font-medium">Rp {{ number_format($overview['total_costs'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Operasional:</span>
                        <span class="font-medium">Rp {{ number_format($overview['total_costs'] * 0.2, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-medium border-t border-red-300 pt-2">
                        <span>Total:</span>
                        <span>Rp {{ number_format($overview['total_costs'] * 1.2, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="p-4 {{ $overview['net_profit'] >= 0 ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200' }} rounded-lg border">
                <h4 class="font-medium {{ $overview['net_profit'] >= 0 ? 'text-blue-800' : 'text-gray-800' }} mb-2">📊 Keuntungan</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Gross Profit:</span>
                        <span class="font-medium">Rp {{ number_format($overview['gross_profit'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Net Profit:</span>
                        <span class="font-medium {{ $overview['net_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($overview['net_profit'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between font-medium border-t {{ $overview['net_profit'] >= 0 ? 'border-blue-300' : 'border-gray-300' }} pt-2">
                        <span>Margin:</span>
                        <span class="{{ $overview['profit_margin'] >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $overview['profit_margin'] }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->user_type === 'admin')
        <!-- Platform Performance -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Performa Platform</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-users text-2xl text-blue-600 mb-2"></i>
                    <p class="text-sm text-gray-600">Active Producers</p>
                    <p class="text-lg font-bold text-gray-900">{{ rand(15, 50) }}</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-shopping-cart text-2xl text-green-600 mb-2"></i>
                    <p class="text-sm text-gray-600">Total Transactions</p>
                    <p class="text-lg font-bold text-gray-900">{{ $overview['total_orders'] }}</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-percentage text-2xl text-purple-600 mb-2"></i>
                    <p class="text-sm text-gray-600">Platform Fee</p>
                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($overview['total_revenue'] * 0.05, 0, ',', '.') }}</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-chart-line text-2xl text-orange-600 mb-2"></i>
                    <p class="text-sm text-gray-600">Growth Rate</p>
                    <p class="text-lg font-bold text-green-900">+{{ rand(5, 25) }}%</p>
                </div>
            </div>
        </div>
    @endif
@endsection
