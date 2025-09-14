@extends('layouts.admin')

@section('title', 'Laporan Inventori')
@section('page-title', 'Laporan Inventori')
@section('page-description')
    @if (auth()->user()->user_type === 'admin')
        Analisis stok dan inventori keseluruhan platform
    @else
        Analisis stok produk Anda
    @endif
@endsection

@section('page-actions')
    <div class="flex items-center gap-3">
        <form method="POST" action="{{ route('admin.reports.inventory.export') }}" class="inline">
            @csrf
            <input type="hidden" name="type" value="{{ $stockType }}">
            <input type="hidden" name="format" value="csv">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700">
                <i class="fas fa-file-csv mr-2"></i>
                Export CSV
            </button>
        </form>

        <button onclick="location.reload()"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700">
            <i class="fas fa-sync-alt mr-2"></i>
            Refresh
        </button>
    </div>
@endsection

@section('content')
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6 mb-6">
        <form method="GET" action="{{ route('admin.reports.inventory.index') }}"
            class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="stock_type" class="block text-sm font-medium text-gray-700 mb-1">Filter Stok</label>
                <select name="stock_type" id="stock_type"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="all" {{ $stockType == 'all' ? 'selected' : '' }}>Semua Produk</option>
                    <option value="in_stock" {{ $stockType == 'in_stock' ? 'selected' : '' }}>Stok Tersedia</option>
                    <option value="low_stock" {{ $stockType == 'low_stock' ? 'selected' : '' }}>Stok Menipis</option>
                    <option value="out_of_stock" {{ $stockType == 'out_of_stock' ? 'selected' : '' }}>Stok Habis</option>
                </select>
            </div>

            <div>
                <label for="limit" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Data</label>
                <select name="limit" id="limit"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="20" {{ $limit == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ $limit == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $limit == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.reports.inventory.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $overview['total_products'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Produk Aktif</p>
                    <p class="text-2xl font-bold text-green-900">{{ $overview['active_products'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Stok Tersedia</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $overview['in_stock'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-warehouse text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Stok Menipis</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $overview['low_stock'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Stok Habis</p>
                    <p class="text-2xl font-bold text-red-900">{{ $overview['out_of_stock'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Inventory by Category -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Inventori per Kategori</h3>
            <div class="space-y-4">
                @forelse($categoryInventory as $category)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $category->category->name ?? 'Kategori Tidak Ditemukan' }}</p>
                            <p class="text-sm text-gray-500">{{ $category->total_products }} produk</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">{{ $category->total_stock }} stok</p>
                            @if ($category->out_of_stock > 0)
                                <p class="text-xs text-red-600">{{ $category->out_of_stock }} habis</p>
                            @endif
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

        <!-- Expired Products Alert -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Produk Mendekati Kadaluarsa</h3>
            <div class="space-y-4">
                @forelse($expiredProducts->take(5) as $product)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                        <div>
                            <p class="font-medium text-gray-900">{{ $product->name }}</p>
                            <p class="text-sm text-gray-500">{{ $product->category->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-red-600">
                                {{ $product->expired_date ? \Carbon\Carbon::parse($product->expired_date)->format('d M Y') : 'N/A' }}
                            </p>
                            <p class="text-xs text-gray-500">Stok: {{ $product->stock }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-calendar-check text-4xl mb-2"></i>
                        <p>Tidak ada produk mendekati kadaluarsa</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                Daftar Produk
                @if ($stockType !== 'all')
                    - {{ ucfirst(str_replace('_', ' ', $stockType)) }}
                @endif
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left py-3 px-6 font-medium text-gray-600">Produk</th>
                        <th class="text-left py-3 px-6 font-medium text-gray-600">Kategori</th>
                        @if (auth()->user()->user_type === 'admin')
                            <th class="text-left py-3 px-6 font-medium text-gray-600">Produsen</th>
                        @endif
                        <th class="text-left py-3 px-6 font-medium text-gray-600">Stok</th>
                        <th class="text-left py-3 px-6 font-medium text-gray-600">Harga</th>
                        <th class="text-left py-3 px-6 font-medium text-gray-600">Kadaluarsa</th>
                        <th class="text-left py-3 px-6 font-medium text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-box text-gray-500"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $product->unit ?? 'pcs' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    {{ $product->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            @if (auth()->user()->user_type === 'admin')
                                <td class="py-3 px-6">
                                    <p class="text-sm text-gray-900">{{ $product->user->name ?? 'N/A' }}</p>
                                </td>
                            @endif
                            <td class="py-3 px-6">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-lg font-bold
                                        {{ $product->stock == 0 ? 'text-red-600' : ($product->stock <= 10 ? 'text-yellow-600' : 'text-green-600') }}">
                                        {{ $product->stock }}
                                    </span>
                                    @if ($product->stock == 0)
                                        <span class="px-2 py-1 bg-red-100 text-red-600 text-xs rounded-full">Habis</span>
                                    @elseif($product->stock <= 10)
                                        <span
                                            class="px-2 py-1 bg-yellow-100 text-yellow-600 text-xs rounded-full">Menipis</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <p class="text-sm font-medium text-gray-900">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-3 px-6">
                                @if ($product->expired_date)
                                    @php
                                        $expiredDate = \Carbon\Carbon::parse($product->expired_date);
                                        $daysLeft = $expiredDate->diffInDays(now(), false);
                                    @endphp
                                    <div class="text-sm">
                                        <p class="text-gray-900">{{ $expiredDate->format('d M Y') }}</p>
                                        @if ($daysLeft > 0)
                                            <p class="text-xs text-red-600">Kadaluarsa</p>
                                        @elseif($daysLeft > -30)
                                            <p class="text-xs text-yellow-600">{{ abs($daysLeft) }} hari lagi</p>
                                        @else
                                            <p class="text-xs text-green-600">{{ abs($daysLeft) }} hari lagi</p>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-6">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->user_type === 'admin' ? '7' : '6' }}"
                                class="py-12 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-4"></i>
                                <p>Tidak ada data produk</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
