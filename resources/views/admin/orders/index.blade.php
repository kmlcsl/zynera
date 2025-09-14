@extends('layouts.admin')

@section('title', 'Kelola Pesanan')
@section('page-title', 'Kelola Pesanan')
@section('page-description')
@if (auth()->user()->user_type === 'admin')
Kelola semua pesanan di platform AgriConnect
@elseif(auth()->user()->user_type === 'produsen')
Kelola pesanan produk Anda dan assign kurir
@elseif(auth()->user()->user_type === 'kurir')
Kelola pesanan untuk pengiriman
@endif
@endsection

@section('page-actions')
<!-- Made action buttons responsive with proper mobile layout -->
<div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
    @if (auth()->user()->user_type === 'admin')
    <button onclick="exportOrders()"
        class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-emerald-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
        <i class="fas fa-file-excel mr-2"></i>
        <span class="hidden sm:inline">Export Excel</span>
        <span class="sm:hidden">Export</span>
    </button>
    @endif

    @if (in_array(auth()->user()->user_type, ['admin', 'produsen']))
    <button onclick="openBulkAssignModal()"
        class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-violet-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-violet-700 transition-colors">
        <i class="fas fa-truck mr-2"></i>
        <span class="hidden sm:inline">Bulk Assign Kurir</span>
        <span class="sm:hidden">Bulk Assign</span>
    </button>
    @endif

    <button onclick="refreshOrders()"
        class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
        <i class="fas fa-sync-alt mr-2"></i>
        <span class="hidden sm:inline">Refresh</span>
    </button>

    @if (auth()->user()->user_type === 'admin')
    <a href="{{ route('admin.orders.create') }}"
        class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-orange-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-orange-700 transition-colors">
        <i class="fas fa-plus mr-2"></i>
        <span class="hidden sm:inline">Buat Pesanan</span>
        <span class="sm:hidden">Buat</span>
    </a>
    @endif
</div>
@endsection

@section('content')
<!-- Enhanced statistics cards with better mobile layout -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-blue-700 font-medium">Total Pesanan</p>
                <p class="text-lg sm:text-xl font-bold text-blue-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-200 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-cart text-blue-700 text-sm sm:text-base"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-orange-700 font-medium">Perlu Assign</p>
                <p class="text-lg sm:text-xl font-bold text-orange-900">{{ $stats['pending'] + $stats['paid'] ?? 0 }}
                </p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-200 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-plus text-orange-700 text-sm sm:text-base"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 border border-indigo-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-indigo-700 font-medium">Sedang Dikirim</p>
                <p class="text-lg sm:text-xl font-bold text-indigo-900">
                    {{ $stats['processing'] + $stats['shipped'] ?? 0 }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-200 rounded-lg flex items-center justify-center">
                <i class="fas fa-truck text-indigo-700 text-sm sm:text-base"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 border border-emerald-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-emerald-700 font-medium">Terkirim</p>
                <p class="text-lg sm:text-xl font-bold text-emerald-900">{{ $stats['delivered'] ?? 0 }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-200 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-emerald-700 text-sm sm:text-base"></i>
            </div>
        </div>
    </div>
</div>

<!-- Improved responsive filter form with better mobile layout -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6 mb-6">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
            <div class="sm:col-span-2 lg:col-span-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Pesanan</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="No. pesanan, nama pelanggan..."
                    class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status"
                    class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status')=='paid' ? 'selected' : '' }}>Paid (Perlu Assign)
                    </option>
                    <option value="processing" {{ request('status')=='processing' ? 'selected' : '' }}>Processing
                    </option>
                    <option value="shipped" {{ request('status')=='shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status')=='delivered' ? 'selected' : '' }}>Delivered
                    </option>
                    <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled
                    </option>
                </select>
            </div>

            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
            </div>

            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.orders.index') }}"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-undo text-sm"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Mobile-first responsive table with card layout for mobile -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Enhanced bulk actions with better mobile buttons -->
    <div class="border-b border-gray-200 p-4 bg-gradient-to-r from-gray-50 to-gray-100" style="display: none;"
        id="bulkActions">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600 font-medium">
                    <span id="selectedCount">0</span> pesanan dipilih
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                @if (in_array(auth()->user()->user_type, ['admin', 'produsen']))
                <button onclick="openBulkAssignModal()"
                    class="flex-1 sm:flex-none px-3 py-2 bg-violet-600 text-white text-sm rounded-lg hover:bg-violet-700 transition-colors">
                    <i class="fas fa-truck mr-1"></i>Assign Kurir
                </button>
                @endif

                @if (auth()->user()->user_type === 'admin')
                <button onclick="bulkUpdateStatus('processing')"
                    class="flex-1 sm:flex-none px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-cogs mr-1"></i>Processing
                </button>
                <button onclick="bulkUpdateStatus('shipped')"
                    class="flex-1 sm:flex-none px-3 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-shipping-fast mr-1"></i>Shipped
                </button>
                <button onclick="bulkUpdateStatus('delivered')"
                    class="flex-1 sm:flex-none px-3 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition-colors">
                    <i class="fas fa-check mr-1"></i>Delivered
                </button>
                @elseif(auth()->user()->user_type === 'kurir')
                <button onclick="bulkUpdateStatus('shipped')"
                    class="flex-1 sm:flex-none px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-truck mr-1"></i>Pickup
                </button>
                <button onclick="bulkUpdateStatus('delivered')"
                    class="flex-1 sm:flex-none px-3 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition-colors">
                    <i class="fas fa-check mr-1"></i>Delivered
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile card layout for better responsiveness -->
    <div class="block lg:hidden">
        @forelse($orders as $order)
        <div class="border-b border-gray-200 p-4 hover:bg-gray-50">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="order_ids[]" value="{{ $order->id }}"
                        class="order-checkbox rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    <div>
                        <div class="font-semibold text-gray-900">#{{ $order->order_number }}</div>
                        <div class="text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                    {{ $order->status_label }}
                </span>
            </div>

            <div class="space-y-2 mb-3">
                <div class="flex items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'User') }}&background=8FBC8F&color=fff"
                        alt="{{ $order->user->name ?? 'User' }}" class="w-6 h-6 rounded-full">
                    <span class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Unknown User' }}</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ $order->total_items }} item(s)</span>
                    <span class="font-semibold text-gray-900">Rp
                        {{ number_format($order->final_total, 0, ',', '.') }}</span>
                </div>

                @if (in_array(auth()->user()->user_type, ['admin', 'produsen']) && $order->delivery &&
                $order->delivery->courier)
                <div class="flex items-center gap-2 text-sm">
                    <i class="fas fa-truck text-gray-400"></i>
                    <span class="text-gray-600">{{ $order->delivery->courier->name }}</span>
                </div>
                @endif
            </div>

            <!-- Mobile action buttons with better touch targets -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.orders.show', $order) }}"
                    class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 text-xs rounded-lg hover:bg-blue-200 transition-colors">
                    <i class="fas fa-eye mr-1"></i>Detail
                </a>

                @if (in_array(auth()->user()->user_type, ['admin', 'produsen']) &&
                in_array($order->status, ['paid', 'processing']) &&
                !$order->delivery)
                <button onclick="openAssignModal({{ $order->id }}, '{{ $order->order_number }}')"
                    class="inline-flex items-center px-3 py-1.5 bg-violet-100 text-violet-700 text-xs rounded-lg hover:bg-violet-200 transition-colors">
                    <i class="fas fa-plus mr-1"></i>Assign Kurir
                </button>
                @endif

                @if (auth()->user()->user_type === 'admin' && in_array($order->status, ['pending', 'paid',
                'processing']))
                <a href="{{ route('admin.orders.edit', $order) }}"
                    class="inline-flex items-center px-3 py-1.5 bg-emerald-100 text-emerald-700 text-xs rounded-lg hover:bg-emerald-200 transition-colors">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
                @endif

                @if (in_array(auth()->user()->user_type, ['admin', 'produsen']) &&
                $order->delivery && $order->delivery->courier && $order->delivery->courier->phone)
                <button
                    onclick="followUpWhatsApp({{ $order->id }}, '{{ $order->delivery->courier->phone }}', '{{ $order->order_number }}')"
                    class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs rounded-lg hover:bg-green-200 transition-colors">
                    <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="py-12 text-center text-gray-500">
            <i class="fas fa-shopping-cart text-4xl mb-4 text-gray-300"></i>
            <div class="text-lg font-medium mb-2">Tidak ada pesanan ditemukan</div>
            <div class="text-sm">
                @if (auth()->user()->user_type === 'admin')
                Belum ada pesanan masuk atau coba ubah filter pencarian.
                @elseif(auth()->user()->user_type === 'produsen')
                Belum ada pesanan untuk produk Anda.
                @elseif(auth()->user()->user_type === 'kurir')
                Belum ada pesanan yang perlu dikirim.
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Desktop table with improved responsive design -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-4">
                        <input type="checkbox" id="selectAll"
                            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    </th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">No. Pesanan</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Pelanggan</th>
                    @if (auth()->user()->user_type === 'admin')
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Produsen</th>
                    @endif
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Items</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Total</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Status</th>
                    @if (in_array(auth()->user()->user_type, ['admin', 'produsen']))
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Kurir</th>
                    @endif
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Tanggal</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-4">
                        <input type="checkbox" name="order_ids[]" value="{{ $order->id }}"
                            class="order-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500">
                    </td>
                    <td class="py-4 px-4">
                        <div class="font-medium text-gray-900">#{{ $order->order_number }}</div>
                        <div class="text-xs text-gray-500">ID: {{ $order->id }}</div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'User') }}&background=8FBC8F&color=fff"
                                alt="{{ $order->user->name ?? 'User' }}" class="w-8 h-8 rounded-full object-cover">
                            <div>
                                <div class="font-medium text-gray-900">{{ $order->user->name ?? 'Unknown User' }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $order->user->email ?? 'No email' }}</div>
                            </div>
                        </div>
                    </td>
                    @if (auth()->user()->user_type === 'admin')
                    <td class="py-4 px-4">
                        @php
                        $producers = $order->orderItems->pluck('product.user.name')->unique()->filter();
                        @endphp
                        <div class="text-sm text-gray-900">
                            @if ($producers->count() > 0)
                            {{ $producers->implode(', ') }}
                            @else
                            <span class="text-gray-400">No producer</span>
                            @endif
                        </div>
                    </td>
                    @endif
                    <td class="py-4 px-4">
                        <div class="text-sm text-gray-900">{{ $order->total_items }} item(s)</div>
                        <div class="text-xs text-gray-500">
                            {{ $order->orderItems->sum('quantity') }} qty total
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="font-medium text-gray-900">Rp
                            {{ number_format($order->final_total, 0, ',', '.') }}</div>
                        @if ($order->shipping_cost > 0)
                        <div class="text-xs text-gray-500">
                            + Rp {{ number_format($order->shipping_cost, 0, ',', '.') }} shipping
                        </div>
                        @endif
                    </td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                            @if ($order->status === 'pending')
                            <i class="fas fa-clock mr-1"></i>{{ $order->status_label }}
                            @elseif($order->status === 'paid')
                            <i class="fas fa-credit-card mr-1"></i>{{ $order->status_label }}
                            @if (in_array(auth()->user()->user_type, ['admin', 'produsen']) && !$order->delivery)
                            <i class="fas fa-exclamation-triangle ml-1 text-orange-600" title="Perlu assign kurir"></i>
                            @endif
                            @elseif($order->status === 'processing')
                            <i class="fas fa-cogs mr-1"></i>{{ $order->status_label }}
                            @elseif($order->status === 'shipped')
                            <i class="fas fa-shipping-fast mr-1"></i>{{ $order->status_label }}
                            @elseif($order->status === 'delivered')
                            <i class="fas fa-check-circle mr-1"></i>{{ $order->status_label }}
                            @elseif($order->status === 'cancelled')
                            <i class="fas fa-times-circle mr-1"></i>{{ $order->status_label }}
                            @else
                            {{ $order->status_label }}
                            @endif
                        </span>
                    </td>

                    @if (in_array(auth()->user()->user_type, ['admin', 'produsen']))
                    <td class="py-4 px-4">
                        @if ($order->delivery && $order->delivery->courier)
                        <div class="flex items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order->delivery->courier->name) }}&background=4F46E5&color=fff"
                                alt="{{ $order->delivery->courier->name }}" class="w-6 h-6 rounded-full object-cover">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $order->delivery->courier->name }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $order->delivery->status_label ?? 'Assigned' }}</div>
                            </div>
                            @if (in_array($order->status, ['paid', 'processing']) && $order->delivery->status ===
                            'assigned')
                            <button onclick="removeCourierAssignment({{ $order->id }})"
                                class="text-red-500 hover:text-red-700 p-1 rounded ml-1" title="Remove Assignment">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                            @endif
                            @if ($order->delivery && $order->delivery->courier && $order->delivery->courier->phone)
                            <button
                                onclick="followUpWhatsApp({{ $order->id }}, '{{ $order->delivery->courier->phone }}', '{{ $order->order_number }}')"
                                class="text-green-600 hover:text-green-800 p-1 rounded ml-1"
                                title="Follow Up via WhatsApp">
                                <i class="fab fa-whatsapp text-sm"></i>
                            </button>
                            @endif
                        </div>
                        @else
                        @if (in_array($order->status, ['paid', 'processing']))
                        <button onclick="openAssignModal({{ $order->id }}, '{{ $order->order_number }}')"
                            class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full hover:bg-purple-200">
                            <i class="fas fa-plus mr-1"></i>Assign Kurir
                        </button>
                        @else
                        <span class="text-gray-400 text-xs">-</span>
                        @endif
                        @endif
                    </td>
                    @endif

                    <td class="py-4 px-4">
                        <div class="text-sm text-gray-900">{{ $order->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.orders.show', $order) }}"
                                class="text-blue-600 hover:text-blue-800 p-1 rounded" title="View Details">
                                <i class="fas fa-eye text-sm"></i>
                            </a>

                            @if (in_array(auth()->user()->user_type, ['admin']) && in_array($order->status, ['pending',
                            'paid', 'processing']))
                            <a href="{{ route('admin.orders.edit', $order) }}"
                                class="text-green-600 hover:text-green-800 p-1 rounded" title="Edit Order">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            @endif

                            @if (in_array(auth()->user()->user_type, ['admin', 'produsen']) &&
                            $order->delivery && $order->delivery->courier && $order->delivery->courier->phone)
                            <button
                                onclick="followUpWhatsApp({{ $order->id }}, '{{ $order->delivery->courier->phone }}', '{{ $order->order_number }}')"
                                class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs rounded-lg hover:bg-green-200 transition-colors">
                                <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                            </button>
                            @endif

                            {{-- ASSIGN KURIR UNTUK PRODUSEN --}}
                            @if (auth()->user()->user_type === 'produsen' && in_array($order->status, ['paid',
                            'processing']))
                            @php
                            $hasProducerProducts =
                            $order->orderItems->where('product.user_id', auth()->id())->count() > 0;
                            $delivery = $order->delivery;
                            @endphp

                            @if ($hasProducerProducts)
                            @if (!$delivery)
                            {{-- Belum ada kurir yang di-assign --}}
                            <button onclick="showAssignCourierModal({{ $order->id }})"
                                class="text-purple-600 hover:text-purple-800 p-1 rounded" title="Assign Kurir">
                                <i class="fas fa-user-plus text-sm"></i>
                            </button>
                            @else
                            {{-- Sudah ada kurir yang di-assign --}}
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-green-600 hover:text-green-800 p-1 rounded"
                                    title="Kurir: {{ $delivery->courier->name ?? 'Unknown' }}">
                                    <i class="fas fa-user-check text-sm"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <div class="text-sm font-medium text-gray-900">Kurir Assigned
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            {{ $delivery->courier->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-500">Status:
                                            {{ $delivery->status_label ?? 'Unknown' }}</div>
                                    </div>
                                    @if ($delivery->status === 'assigned')
                                    <button onclick="changeAssignedCourier({{ $order->id }})"
                                        class="block w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-blue-50">
                                        <i class="fas fa-exchange-alt mr-2"></i>Ganti Kurir
                                    </button>
                                    <button onclick="removeCourierAssignment({{ $order->id }})"
                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-user-times mr-2"></i>Hapus Assignment
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @endif
                            @endif

                            {{-- Quick Status Update for Admin/Kurir --}}
                            @if (auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'kurir')
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-purple-600 hover:text-purple-800 p-1 rounded"
                                    title="Update Status">
                                    <i class="fas fa-exchange-alt text-sm"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                    @if ($order->status === 'pending')
                                    <button onclick="updateOrderStatus({{ $order->id }}, 'paid')"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-credit-card mr-2 text-blue-600"></i>Mark as Paid
                                    </button>
                                    @endif
                                    @if (in_array($order->status, ['pending', 'paid']))
                                    <button onclick="updateOrderStatus({{ $order->id }}, 'processing')"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-cogs mr-2 text-purple-600"></i>Set Processing
                                    </button>
                                    @endif
                                    @if (in_array($order->status, ['paid', 'processing']))
                                    <button onclick="updateOrderStatus({{ $order->id }}, 'shipped')"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-shipping-fast mr-2 text-indigo-600"></i>Set
                                        Shipped
                                    </button>
                                    @endif
                                    @if ($order->status === 'shipped')
                                    <button onclick="updateOrderStatus({{ $order->id }}, 'delivered')"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-check-circle mr-2 text-green-600"></i>Mark
                                        Delivered
                                    </button>
                                    @endif
                                    @if (!in_array($order->status, ['delivered', 'cancelled']))
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <button onclick="updateOrderStatus({{ $order->id }}, 'cancelled')"
                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-times-circle mr-2"></i>Cancel Order
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if (auth()->user()->user_type === 'admin' && in_array($order->status, ['cancelled']))
                            <button onclick="deleteOrder({{ $order->id }})"
                                class="text-red-600 hover:text-red-800 p-1 rounded" title="Delete Order">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                            @endif
                        </div>
                    </td>

                    {{-- MODAL ASSIGN KURIR --}}
                    {{-- <div id="assignCourierModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50"
                        onclick="closeAssignCourierModal()">
                        <div class="flex items-center justify-center min-h-screen p-4">
                            <div class="bg-white rounded-lg max-w-md w-full p-6" onclick="event.stopPropagation()">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Assign Kurir</h3>
                                    <button onclick="closeAssignCourierModal()"
                                        class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-2">Pilih kurir untuk mengirim pesanan:</p>
                                    <div class="font-medium text-gray-900" id="orderInfo">Order #</div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kurir
                                        Tersedia</label>
                                    <select id="courierSelect"
                                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                        <option value="">Pilih kurir...</option>
                                    </select>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <button onclick="closeAssignCourierModal()"
                                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                        Batal
                                    </button>
                                    <button onclick="confirmAssignCourier()"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                        Assign Kurir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->user_type === 'admin' ? '10' : (in_array(auth()->user()->user_type, ['produsen']) ? '9' : '8') }}"
                        class="py-12 text-center text-gray-500">
                        <i class="fas fa-shopping-cart text-4xl mb-4 text-gray-300"></i>
                        <div class="text-lg font-medium mb-2">Tidak ada pesanan ditemukan</div>
                        <div class="text-sm">
                            @if (auth()->user()->user_type === 'admin')
                            Belum ada pesanan masuk atau coba ubah filter pencarian.
                            @elseif(auth()->user()->user_type === 'produsen')
                            Belum ada pesanan untuk produk Anda.
                            @elseif(auth()->user()->user_type === 'kurir')
                            Belum ada pesanan yang perlu dikirim.
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($orders->hasPages())
    <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $orders->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Modal Assign Kurir Single -->
<div id="assignModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Assign Kurir</h3>
                <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="assignForm" onsubmit="assignCourier(event)">
                <input type="hidden" id="assignOrderId" name="order_id">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pesanan</label>
                    <div id="assignOrderInfo" class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900"></div>
                </div>

                <div class="mb-4">
                    <label for="assignCourierId" class="block text-sm font-medium text-gray-700 mb-2">Pilih
                        Kurir</label>
                    <select id="assignCourierId" name="courier_id" required
                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        <option value="">-- Pilih Kurir --</option>
                        @foreach ($availableCouriers as $courier)
                        <option value="{{ $courier->id }}">
                            {{ $courier->name }}
                            @if ($courier->phone)
                            ({{ $courier->phone }})
                            @endif
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label for="assignNotes" class="block text-sm font-medium text-gray-700 mb-2">Catatan
                        Pengiriman</label>
                    <textarea id="assignNotes" name="delivery_notes" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                        placeholder="Catatan khusus untuk kurir..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeAssignModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        <i class="fas fa-truck mr-2"></i>Assign Kurir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Bulk Assign Kurir -->
<div id="bulkAssignModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Bulk Assign Kurir</h3>
                <button onclick="closeBulkAssignModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="bulkAssignForm" onsubmit="bulkAssignCourier(event)">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pesanan Dipilih</label>
                    <div id="bulkAssignOrderInfo" class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">
                        <span id="bulkSelectedCount">0</span> pesanan dipilih
                    </div>
                </div>

                <div class="mb-4">
                    <label for="bulkAssignCourierId" class="block text-sm font-medium text-gray-700 mb-2">Pilih
                        Kurir</label>
                    <select id="bulkAssignCourierId" name="courier_id" required
                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        <option value="">-- Pilih Kurir --</option>
                        @foreach ($availableCouriers as $courier)
                        <option value="{{ $courier->id }}">
                            {{ $courier->name }}
                            @if ($courier->phone)
                            ({{ $courier->phone }})
                            @endif
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label for="bulkAssignNotes" class="block text-sm font-medium text-gray-700 mb-2">Catatan
                        Pengiriman</label>
                    <textarea id="bulkAssignNotes" name="delivery_notes" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                        placeholder="Catatan khusus untuk kurir..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeBulkAssignModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        <i class="fas fa-truck mr-2"></i>Assign Kurir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentOrderId = null;
        let availableCouriers = [];

        // Load available couriers when page loads
        document.addEventListener('DOMContentLoaded', function() {
            @if (auth()->user()->user_type === 'produsen')
                loadAvailableCouriers();
            @endif
        });

        // Load available couriers
        function loadAvailableCouriers() {
            fetch('/admin/orders/available-couriers', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        availableCouriers = data.couriers;
                    }
                })
                .catch(error => {
                    console.error('Error loading couriers:', error);
                });
        }

        // Show assign courier modal
        function showAssignCourierModal(orderId) {
            currentOrderId = orderId;

            // Update order info
            const orderRow = document.querySelector(`input[value="${orderId}"]`).closest('tr');
            const orderNumber = orderRow.querySelector('td:nth-child(2) .font-medium').textContent;
            document.getElementById('orderInfo').textContent = orderNumber;

            // Populate courier select
            const courierSelect = document.getElementById('courierSelect');
            courierSelect.innerHTML = '<option value="">Pilih kurir...</option>';

            availableCouriers.forEach(courier => {
                const option = document.createElement('option');
                option.value = courier.id;
                option.textContent = `${courier.name} (${courier.email})`;
                courierSelect.appendChild(option);
            });

            // Show modal
            document.getElementById('assignCourierModal').classList.remove('hidden');
        }

        // Close assign courier modal
        function closeAssignCourierModal() {
            document.getElementById('assignCourierModal').classList.add('hidden');
            currentOrderId = null;
            document.getElementById('courierSelect').value = '';
        }

        // Confirm assign courier
        function confirmAssignCourier() {
            const courierId = document.getElementById('courierSelect').value;

            if (!courierId) {
                alert('Silakan pilih kurir terlebih dahulu');
                return;
            }

            if (!currentOrderId) {
                alert('Order ID tidak valid');
                return;
            }

            fetch(`/admin/orders/${currentOrderId}/assign-courier`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        courier_id: courierId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert(data.message || 'Gagal assign kurir');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat assign kurir');
                })
                .finally(() => {
                    closeAssignCourierModal();
                });
        }

        // Change assigned courier
        function changeAssignedCourier(orderId) {
            showAssignCourierModal(orderId);
        }

        // Remove courier assignment
        function removeCourierAssignment(orderId) {
            if (confirm('Yakin ingin membatalkan assignment kurir untuk pesanan ini?')) {
                fetch(`/admin/orders/${orderId}/remove-courier`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.message || 'Gagal remove assignment');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    });
            }
        }

        // Select All Functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.order-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');

            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });

            updateBulkActionsVisibility();
        });

        // Individual checkbox change
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('order-checkbox')) {
                updateBulkActionsVisibility();
            }
        });

        function updateBulkActionsVisibility() {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            const bulkSelectedCount = document.getElementById('bulkSelectedCount');

            if (checkedBoxes.length > 0) {
                bulkActions.style.display = 'block';
                selectedCount.textContent = checkedBoxes.length;
                if (bulkSelectedCount) {
                    bulkSelectedCount.textContent = checkedBoxes.length;
                }
            } else {
                bulkActions.style.display = 'none';
            }
        }

        // Single Assign Modal Functions
        function openAssignModal(orderId, orderNumber) {
            document.getElementById('assignOrderId').value = orderId;
            document.getElementById('assignOrderInfo').textContent = `Pesanan #${orderNumber}`;
            document.getElementById('assignModal').classList.remove('hidden');
        }

        function closeAssignModal() {
            document.getElementById('assignModal').classList.add('hidden');
            document.getElementById('assignForm').reset();
        }

        function assignCourier(event) {
            event.preventDefault();

            const formData = new FormData(event.target);
            const orderId = formData.get('order_id');

            console.log('Assigning courier:', {
                orderId: orderId,
                courierId: formData.get('courier_id'),
                notes: formData.get('delivery_notes')
            });

            fetch(`/admin/orders/${orderId}/assign-courier`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        courier_id: formData.get('courier_id'),
                        delivery_notes: formData.get('delivery_notes')
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        alert(data.message);
                        closeAssignModal();
                        location.reload();
                    } else {
                        alert(data.message || 'Gagal assign kurir');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                });
        }

        // Bulk Assign Modal Functions
        function openBulkAssignModal() {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Pilih pesanan terlebih dahulu');
                return;
            }

            document.getElementById('bulkSelectedCount').textContent = checkedBoxes.length;
            document.getElementById('bulkAssignModal').classList.remove('hidden');
        }

        function closeBulkAssignModal() {
            document.getElementById('bulkAssignModal').classList.add('hidden');
            document.getElementById('bulkAssignForm').reset();
        }

        function bulkAssignCourier(event) {
            event.preventDefault();

            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            const orderIds = Array.from(checkedBoxes).map(cb => cb.value);
            const formData = new FormData(event.target);

            fetch('/admin/orders/bulk-assign-courier', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        order_ids: orderIds,
                        courier_id: formData.get('courier_id'),
                        delivery_notes: formData.get('delivery_notes')
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeBulkAssignModal();
                        location.reload();
                    } else {
                        alert(data.message || 'Gagal bulk assign kurir');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        }

        // Remove courier assignment
        function removeCourierAssignment(orderId) {
            if (confirm('Yakin ingin membatalkan assignment kurir untuk pesanan ini?')) {
                fetch(`/admin/orders/${orderId}/remove-courier`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Gagal remove assignment');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    });
            }
        }

        // Update single order status
        function updateOrderStatus(orderId, status) {
            if (confirm(`Ubah status pesanan menjadi ${status}?`)) {
                fetch(`/admin/orders/${orderId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal mengubah status pesanan');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    });
            }
        }

        // Bulk update status
        function bulkUpdateStatus(status) {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            const orderIds = Array.from(checkedBoxes).map(cb => cb.value);

            if (orderIds.length === 0) {
                alert('Pilih pesanan terlebih dahulu');
                return;
            }

            if (confirm(`Ubah status ${orderIds.length} pesanan menjadi ${status}?`)) {
                fetch('/admin/orders/bulk-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            order_ids: orderIds,
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal mengubah status pesanan');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    });
            }
        }

        // Delete order
        function deleteOrder(orderId) {
            if (confirm('Yakin ingin menghapus pesanan ini? Tindakan ini tidak dapat dibatalkan.')) {
                fetch(`/admin/orders/${orderId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal menghapus pesanan');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan');
                    });
            }
        }

        // Export orders
        function exportOrders() {
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'excel');
            window.location.href = `/admin/orders?${params.toString()}`;
        }

        // Refresh orders
        function refreshOrders() {
            location.reload();
        }

        // Follow up via WhatsApp - menggunakan backend
        function followUpWhatsApp(orderId, courierPhone, orderNumber) {
            fetch(`/admin/orders/${orderId}/whatsapp-followup`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Buka WhatsApp dengan URL yang sudah disiapkan backend
                    window.open(data.whatsapp_url, '_blank');
                } else {
                    alert(data.message || 'Gagal membuat pesan WhatsApp');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat membuat pesan WhatsApp');
            });
        }

        // Auto refresh every 30 seconds for courier view
        @if (auth()->user()->user_type === 'kurir')
            setInterval(function() {
                if (!document.hidden) {
                    location.reload();
                }
            }, 30000);
        @endif
</script>
@endsection
