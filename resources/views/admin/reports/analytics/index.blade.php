@extends('layouts.admin')

@section('title', 'Laporan Analytics')
@section('page-title')
    @if (auth()->user()->user_type === 'admin')
        Analytics Platform
    @else
        Analisis Produk
    @endif
@endsection
@section('page-description')
    @if (auth()->user()->user_type === 'admin')
        Analisis perilaku pengguna dan performa platform
    @else
        Analisis performa dan insights produk Anda
    @endif
@endsection

@section('page-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.reports.analytics.customer-behavior') }}"
            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
            <i class="fas fa-users mr-2"></i>
            Customer Behavior
        </a>

        <form method="POST" action="{{ route('admin.reports.analytics.export') }}" class="inline">
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
        <form method="GET" action="{{ route('admin.reports.analytics.index') }}"
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
                <a href="{{ route('admin.reports.analytics.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Analytics Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Customers</p>
                    <p class="text-2xl font-bold text-blue-900">{{ number_format($overview['total_customers']) }}</p>
                    <p class="text-sm text-blue-600 mt-1">
                        <i class="fas fa-user-plus mr-1"></i>Pelanggan terdaftar
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Customers</p>
                    <p class="text-2xl font-bold text-green-900">{{ number_format($overview['active_customers']) }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-chart-line mr-1"></i>Berbelanja aktif
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Repeat Customers</p>
                    <p class="text-2xl font-bold text-purple-900">{{ number_format($overview['repeat_customers']) }}</p>
                    <p class="text-sm text-purple-600 mt-1">
                        <i class="fas fa-redo mr-1"></i>{{ $overview['customer_retention_rate'] }}% retention
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-heart text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Avg Order Value</p>
                    <p class="text-2xl font-bold text-orange-900">Rp {{ number_format($overview['average_order_value'], 0, ',', '.') }}</p>
                    <p class="text-sm text-orange-600 mt-1">
                        <i class="fas fa-shopping-cart mr-1"></i>{{ $overview['orders_per_customer'] }} orders/customer
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversion Metrics -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Conversion Metrics</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto flex items-center justify-center mb-3">
                    <i class="fas fa-eye text-blue-600 text-xl"></i>
                </div>
                <p class="text-sm text-gray-600">Total Visitors</p>
                <p class="text-2xl font-bold text-blue-900">{{ number_format($conversionMetrics['total_customers']) }}</p>
                <p class="text-sm text-blue-600">Registered users</p>
            </div>

            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="w-16 h-16 bg-green-100 rounded-full mx-auto flex items-center justify-center mb-3">
                    <i class="fas fa-shopping-bag text-green-600 text-xl"></i>
                </div>
                <p class="text-sm text-gray-600">Customers with Orders</p>
                <p class="text-2xl font-bold text-green-900">{{ number_format($conversionMetrics['customers_with_orders']) }}</p>
                <p class="text-sm text-green-600">Made purchases</p>
            </div>

            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="w-16 h-16 bg-purple-100 rounded-full mx-auto flex items-center justify-center mb-3">
                    <i class="fas fa-percentage text-purple-600 text-xl"></i>
                </div>
                <p class="text-sm text-gray-600">Conversion Rate</p>
                <p class="text-2xl font-bold text-purple-900">{{ $conversionMetrics['conversion_rate'] }}%</p>
                <p class="text-sm text-purple-600">Customer to buyer</p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Customer Segments -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Segments</h3>
            <div class="space-y-4">
                @forelse($customerSegments as $segment)
                    @php
                        $colors = [
                            'VIP Customer' => 'green',
                            'Regular Customer' => 'blue',
                            'One-time Buyer' => 'yellow',
                            'No Orders' => 'gray'
                        ];
                        $color = $colors[$segment->segment] ?? 'gray';
                        $totalCustomers = $customerSegments->sum('customer_count');
                        $percentage = $totalCustomers > 0 ? round(($segment->customer_count / $totalCustomers) * 100, 1) : 0;
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-{{ $color }}-50 rounded-lg border border-{{ $color }}-200">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-{{ $color }}-500 rounded-full"></div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $segment->segment }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ number_format($segment->customer_count) }} customers
                                    @if($segment->avg_spent > 0)
                                        • Avg: Rp {{ number_format($segment->avg_spent, 0, ',', '.') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-{{ $color }}-600">{{ $percentage }}%</p>
                            <div class="w-20 h-2 bg-gray-200 rounded-full mt-1">
                                <div class="h-2 bg-{{ $color }}-500 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-users text-4xl mb-2"></i>
                        <p>Tidak ada data customer segments</p>
                        <p class="text-sm mt-1">Belum ada data customer untuk segmentasi</p>
                    </div>
                @endforelse
            </div>

            @if($customerSegments->count() > 0)
                <div class="mt-4 p-3 bg-purple-50 rounded-lg border border-purple-200">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-chart-pie text-purple-600"></i>
                        <h4 class="font-medium text-purple-800">Segmentation Insights</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        @php
                            $vipCustomers = $customerSegments->where('segment', 'VIP Customer')->first();
                            $noOrderCustomers = $customerSegments->where('segment', 'No Orders')->first();
                            $totalCustomers = $customerSegments->sum('customer_count');
                        @endphp
                        <div>
                            <span class="text-gray-600">VIP Customers:</span>
                            <span class="font-medium text-green-600 ml-1">
                                {{ $vipCustomers ? round(($vipCustomers->customer_count / $totalCustomers) * 100, 1) : 0 }}%
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Conversion Opportunity:</span>
                            <span class="font-medium text-orange-600 ml-1">
                                {{ $noOrderCustomers ? number_format($noOrderCustomers->customer_count) : 0 }} users
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Customer Growth Trend -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Growth Trend</h3>
            <div class="space-y-3">
                @forelse($customerGrowth as $growth)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $growth->period)->format('M Y') }}
                            </p>
                            <p class="text-sm text-gray-500">{{ number_format($growth->new_customers) }} new customers</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">{{ number_format($growth->total_customers) }}</p>
                            <div class="w-20 h-2 bg-gray-200 rounded-full mt-1">
                                @php
                                    $maxCustomers = $customerGrowth->max('total_customers');
                                    $widthPercentage = $maxCustomers > 0 ? ($growth->total_customers / $maxCustomers) * 100 : 0;
                                @endphp
                                <div class="h-2 bg-blue-500 rounded-full transition-all duration-300" style="width: {{ $widthPercentage }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-chart-line text-4xl mb-2"></i>
                        <p>Tidak ada data customer growth</p>
                        <p class="text-sm mt-1">Belum ada registrasi customer di periode ini</p>
                    </div>
                @endforelse
            </div>

            @if($customerGrowth->count() > 0)
                <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        <h4 class="font-medium text-blue-800">Growth Summary</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Total New Customers:</span>
                            <span class="font-medium text-blue-900 ml-1">{{ number_format($customerGrowth->sum('new_customers')) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Growth Rate:</span>
                            @php
                                $firstMonth = $customerGrowth->first();
                                $lastMonth = $customerGrowth->last();
                                $growthRate = $firstMonth && $firstMonth->total_customers > 0
                                    ? round((($lastMonth->total_customers - $firstMonth->total_customers) / $firstMonth->total_customers) * 100, 1)
                                    : 0;
                            @endphp
                            <span class="font-medium {{ $growthRate >= 0 ? 'text-green-600' : 'text-red-600' }} ml-1">
                                {{ $growthRate > 0 ? '+' : '' }}{{ $growthRate }}%
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if (auth()->user()->user_type === 'admin')
        <!-- Platform Analytics (Admin Only) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Top Performing Products -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Performing Products</h3>
                <div class="space-y-4">
                    @php
                        // Sample top products data
                        $topProducts = [
                            ['name' => 'Beras Pandan Wangi', 'sales' => 245, 'revenue' => 12250000, 'customers' => 89],
                            ['name' => 'Sayuran Organik Mix', 'sales' => 180, 'revenue' => 9600000, 'customers' => 67],
                            ['name' => 'Buah Lokal Segar', 'sales' => 156, 'revenue' => 7800000, 'customers' => 52],
                            ['name' => 'Ikan Segar Harian', 'sales' => 89, 'revenue' => 5340000, 'customers' => 34]
                        ];
                    @endphp

                    @foreach($topProducts as $index => $product)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 {{ $index === 0 ? 'bg-yellow-100' : ($index === 1 ? 'bg-gray-100' : 'bg-orange-100') }} rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium {{ $index === 0 ? 'text-yellow-600' : ($index === 1 ? 'text-gray-600' : 'text-orange-600') }}">{{ $index + 1 }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $product['name'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $product['sales'] }} sales • {{ $product['customers'] }} customers</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">Rp {{ number_format($product['revenue'], 0, ',', '.') }}</p>
                                <div class="flex items-center gap-1 mt-1">
                                    @for($i = 0; $i < min(5, ceil($product['sales'] / 50)); $i++)
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Producer Performance -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Producers</h3>
                <div class="space-y-4">
                    @php
                        $topProducers = [
                            ['name' => 'Petani Aceh Utara', 'products' => 25, 'revenue' => 45000000, 'customers' => 156],
                            ['name' => 'Kebun Organik Medan', 'products' => 18, 'revenue' => 32000000, 'customers' => 134],
                            ['name' => 'Tani Fresh Banda', 'products' => 22, 'revenue' => 28000000, 'customers' => 98],
                            ['name' => 'Sayur Hijau Lhokseumawe', 'products' => 15, 'revenue' => 19000000, 'customers' => 67]
                        ];
                    @endphp

                    @foreach($topProducers as $index => $producer)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $producer['name'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $producer['products'] }} products • {{ $producer['customers'] }} customers</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">Rp {{ number_format($producer['revenue'], 0, ',', '.') }}</p>
                                <div class="flex items-center gap-1 mt-1">
                                    @for($i = 0; $i < min(5, ceil($producer['products'] / 5)); $i++)
                                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Analytics Insights -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 Analytics Insights</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @php
                $insights = [
                    [
                        'type' => 'success',
                        'icon' => 'fa-arrow-up',
                        'title' => 'Customer Retention Improvement',
                        'description' => 'Customer retention rate ' . $overview['customer_retention_rate'] . '% menunjukkan loyalitas pelanggan yang baik'
                    ],
                    [
                        'type' => 'info',
                        'icon' => 'fa-lightbulb',
                        'title' => 'Average Order Value',
                        'description' => 'AOV Rp ' . number_format($overview['average_order_value'], 0, ',', '.') . ' per transaksi. Pertimbangkan strategi upselling untuk meningkatkan nilai'
                    ],
                    [
                        'type' => 'warning',
                        'icon' => 'fa-exclamation-triangle',
                        'title' => 'Conversion Optimization',
                        'description' => 'Conversion rate ' . $conversionMetrics['conversion_rate'] . '%. Masih ada peluang untuk meningkatkan konversi pelanggan'
                    ],
                    [
                        'type' => 'info',
                        'icon' => 'fa-users',
                        'title' => 'Customer Acquisition',
                        'description' => number_format($overview['active_customers']) . ' dari ' . number_format($overview['total_customers']) . ' pelanggan aktif berbelanja'
                    ]
                ];
            @endphp

            @foreach($insights as $insight)
                <div class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg">
                    <div class="w-8 h-8
                        {{ $insight['type'] === 'success' ? 'bg-green-100' :
                           ($insight['type'] === 'warning' ? 'bg-yellow-100' : 'bg-blue-100') }}
                        rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas {{ $insight['icon'] }} text-sm
                            {{ $insight['type'] === 'success' ? 'text-green-600' :
                               ($insight['type'] === 'warning' ? 'text-yellow-600' : 'text-blue-600') }}"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-1">{{ $insight['title'] }}</h4>
                        <p class="text-sm text-gray-600">{{ $insight['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
