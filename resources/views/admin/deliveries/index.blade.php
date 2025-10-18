@extends('layouts.admin')

@section('title', 'Kelola Pengiriman')
@section('page-title', 'Kelola Pengiriman')
@section('page-description')
@if (auth()->user()->user_type === 'admin')
Kelola semua pengiriman di platform Zynera.ID
@elseif(auth()->user()->user_type === 'kurir')
Kelola tugas pengiriman Anda
@endif
@endsection

@section('page-actions')
<div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
    @if (auth()->user()->user_type === 'admin')
    <button onclick="assignBulkDeliveries()"
        class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 border border-transparent rounded-lg text-sm font-medium text-white hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-sm">
        <i class="fas fa-users mr-2"></i>
        <span class="hidden sm:inline">Assign Kurir</span>
        <span class="sm:hidden">Assign</span>
    </button>

    <a href="#"
        class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 border border-transparent rounded-lg text-sm font-medium text-white hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 shadow-sm">
        <i class="fas fa-plus mr-2"></i>
        <span class="hidden sm:inline">Buat Pengiriman</span>
        <span class="sm:hidden">Buat</span>
    </a>
    @endif

    <button onclick="refreshDeliveries()"
        class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-lg text-sm font-medium text-white hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-sm">
        <i class="fas fa-sync-alt mr-2"></i>
        <span class="hidden sm:inline">Refresh</span>
        <span class="sm:hidden">Refresh</span>
    </button>
</div>
@endsection

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4 mb-6">
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 shadow-sm border border-blue-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-blue-700 font-medium">Total</p>
                <p class="text-lg sm:text-xl font-bold text-blue-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-truck text-white text-sm"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 shadow-sm border border-indigo-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-indigo-700 font-medium">Ditugaskan</p>
                <p class="text-lg sm:text-xl font-bold text-indigo-900">{{ $stats['assigned'] ?? 0 }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-clock text-white text-sm"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 shadow-sm border border-purple-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-purple-700 font-medium">Perjalanan</p>
                <p class="text-lg sm:text-xl font-bold text-purple-900">{{ $stats['in_transit'] ?? 0 }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-shipping-fast text-white text-sm"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 shadow-sm border border-emerald-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-emerald-700 font-medium">Terkirim</p>
                <p class="text-lg sm:text-xl font-bold text-emerald-900">{{ $stats['delivered'] ?? 0 }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-white text-sm"></i>
            </div>
        </div>
    </div>

    <div
        class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 shadow-sm border border-red-200 col-span-2 lg:col-span-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-red-700 font-medium">Gagal</p>
                <p class="text-lg sm:text-xl font-bold text-red-900">{{ $stats['failed'] ?? 0 }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-times-circle text-white text-sm"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6 mb-6">
    <form method="GET" action="{{ route('admin.deliveries.index') }}"
        class="space-y-4 lg:space-y-0 lg:grid lg:grid-cols-5 lg:gap-4">
        <div class="lg:col-span-2">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Pengiriman</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}"
                placeholder="No. pesanan, alamat..."
                class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" id="status"
                class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                <option value="">Semua Status</option>
                <option value="assigned" {{ request('status')=='assigned' ? 'selected' : '' }}>Ditugaskan</option>
                <option value="picked_up" {{ request('status')=='picked_up' ? 'selected' : '' }}>Diambil</option>
                <option value="in_transit" {{ request('status')=='in_transit' ? 'selected' : '' }}>Dalam Perjalanan
                </option>
                <option value="delivered" {{ request('status')=='delivered' ? 'selected' : '' }}>Terkirim</option>
                <option value="failed" {{ request('status')=='failed' ? 'selected' : '' }}>Gagal</option>
            </select>
        </div>

        @if (auth()->user()->user_type === 'admin')
        <div>
            <label for="courier_id" class="block text-sm font-medium text-gray-700 mb-2">Kurir</label>
            <select name="courier_id" id="courier_id"
                class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                <option value="">Semua Kurir</option>
                @foreach ($couriers ?? [] as $courier)
                <option value="{{ $courier->id }}" {{ request('courier_id')==$courier->id ? 'selected' : '' }}>
                    {{ $courier->name }}
                </option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-3 lg:flex-col lg:gap-2">
            <div class="flex-1">
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm">
            </div>
            <div class="flex gap-2 sm:flex-col sm:justify-end lg:flex-row">
                <button type="submit"
                    class="flex-1 sm:flex-none bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-4 py-2 rounded-lg hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 text-sm font-medium shadow-sm">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.deliveries.index') }}"
                    class="flex-1 sm:flex-none px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm text-center">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    @if (auth()->user()->user_type === 'admin')
    <div class="border-b border-gray-200 p-4 bg-gradient-to-r from-gray-50 to-gray-100" style="display: none;"
        id="bulkActions">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600 font-medium">
                    <span id="selectedCount">0</span> pengiriman dipilih
                </span>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <select id="bulkCourierSelect"
                    class="text-sm border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Pilih Kurir</option>
                    @foreach ($couriers ?? [] as $courier)
                    <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                    @endforeach
                </select>
                <button onclick="bulkAssignCourier()"
                    class="px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200 font-medium shadow-sm">
                    <i class="fas fa-user mr-2"></i>Assign
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <tr>
                    @if (auth()->user()->user_type === 'admin')
                    <th class="text-left py-4 px-4">
                        <input type="checkbox" id="selectAll"
                            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    </th>
                    @endif
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">ID Pengiriman</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Pesanan</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Pelanggan</th>
                    @if (auth()->user()->user_type === 'admin')
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Kurir</th>
                    @endif
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Alamat Tujuan</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Status</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Waktu</th>
                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($deliveries as $delivery)
                <tr class="hover:bg-gray-50 transition-colors">
                    @if (auth()->user()->user_type === 'admin')
                    <td class="py-4 px-4">
                        <input type="checkbox" name="delivery_ids[]" value="{{ $delivery->id }}"
                            class="delivery-checkbox rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    </td>
                    @endif

                    <td class="py-4 px-4">
                        <div class="font-semibold text-gray-900">
                            #DEL{{ str_pad($delivery->id, 4, '0', STR_PAD_LEFT) }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $delivery->created_at->format('d/m/Y H:i') }}
                        </div>
                    </td>

                    <td class="py-4 px-4">
                        <div class="font-medium text-gray-900">
                            #{{ $delivery->order->order_number ?? 'N/A' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            Rp {{ number_format($delivery->order->total_amount ?? 0, 0, ',', '.') }}
                        </div>
                    </td>

                    <td class="py-4 px-4">
                        <div class="flex items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($delivery->order->user->name ?? 'User') }}&background=10B981&color=fff"
                                alt="{{ $delivery->order->user->name ?? 'User' }}"
                                class="w-8 h-8 rounded-full object-cover">
                            <div>
                                <div class="font-medium text-gray-900">
                                    {{ $delivery->order->user->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $delivery->order->recipient_phone ?? 'No phone' }}</div>
                            </div>
                        </div>
                    </td>

                    @if (auth()->user()->user_type === 'admin')
                    <td class="py-4 px-4">
                        @if ($delivery->courier)
                        <div class="flex items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($delivery->courier->name) }}&background=3B82F6&color=fff"
                                alt="{{ $delivery->courier->name }}" class="w-6 h-6 rounded-full object-cover">
                            <span class="text-sm text-gray-900">{{ $delivery->courier->name }}</span>
                        </div>
                        @else
                        <span class="text-xs text-gray-500 italic">Belum di-assign</span>
                        @endif
                    </td>
                    @endif

                    <td class="py-4 px-4">
                        <div class="text-sm text-gray-900 max-w-xs truncate">
                            {{ $delivery->order->shipping_address ?? 'Alamat tidak tersedia' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $delivery->order->recipient_name ?? 'No recipient' }}
                        </div>
                    </td>

                    <td class="py-4 px-4">
                        @php
                        $statusInfo = [
                        'assigned' => ['color' => 'blue', 'icon' => 'user-clock', 'text' => 'Ditugaskan'],
                        'picked_up' => ['color' => 'yellow', 'icon' => 'hand-holding', 'text' => 'Diambil'],
                        'in_transit' => ['color' => 'purple', 'icon' => 'shipping-fast', 'text' => 'Dalam Perjalanan'],
                        'delivered' => ['color' => 'green', 'icon' => 'check-circle', 'text' => 'Terkirim'],
                        'failed' => ['color' => 'red', 'icon' => 'times-circle', 'text' => 'Gagal'],
                        ];
                        $status = $statusInfo[$delivery->status] ?? ['color' => 'gray', 'icon' => 'question', 'text' =>
                        'Unknown'];
                        @endphp

                        <div class="flex flex-col gap-1">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-800">
                                <i class="fas fa-{{ $status['icon'] }} mr-1"></i>
                                {{ $status['text'] }}
                            </span>

                            <div class="text-xs text-gray-400">
                                @if ($delivery->delivered_at)
                                Delivered: {{ $delivery->delivered_at->format('H:i') }}
                                @elseif ($delivery->picked_up_at)
                                Picked: {{ $delivery->picked_up_at->format('H:i') }}
                                @elseif ($delivery->assigned_at)
                                Assigned: {{ $delivery->assigned_at->format('H:i') }}
                                @endif
                            </div>
                        </div>
                    </td>

                    <td class="py-4 px-4">
                        <div class="text-sm text-gray-900">
                            {{ $delivery->assigned_at ? $delivery->assigned_at->format('d M Y') : 'Not assigned' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $delivery->assigned_at ? $delivery->assigned_at->format('H:i') : '' }}
                        </div>
                    </td>

                    <td class="py-4 px-4">
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="{{ route('admin.deliveries.show', $delivery) }}"
                                class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium hover:bg-blue-200 transition-colors">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>

                            @if (auth()->user()->user_type === 'kurir')
                            @php
                            $isAssignedToMe = $delivery->courier_id && (int)$delivery->courier_id === (int)auth()->id();
                            $canUpdate = $isAssignedToMe && !in_array($delivery->status, ['delivered', 'failed']);
                            @endphp

                            @if ($isAssignedToMe && $canUpdate)
                            @if ($delivery->status === 'assigned')
                            <button onclick="updateDeliveryStatus({{ $delivery->id }}, 'picked_up')"
                                class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium hover:bg-yellow-200 transition-colors"
                                title="Klik untuk mengambil barang">
                                <i class="fas fa-hand-holding mr-1"></i>Ambil
                            </button>
                            @endif

                            @if ($delivery->status === 'picked_up')
                            <button onclick="updateDeliveryStatus({{ $delivery->id }}, 'in_transit')"
                                class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium hover:bg-purple-200 transition-colors"
                                title="Klik jika barang sedang dikirim">
                                <i class="fas fa-shipping-fast mr-1"></i>Kirim
                            </button>
                            @endif

                            @if (in_array($delivery->status, ['picked_up', 'in_transit']))
                            <button onclick="updateDeliveryStatus({{ $delivery->id }}, 'delivered')"
                                class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs font-medium hover:bg-emerald-200 transition-colors"
                                title="Klik jika barang sudah terkirim">
                                <i class="fas fa-check-circle mr-1"></i>Selesai
                            </button>
                            @endif

                            @if (!in_array($delivery->status, ['delivered', 'failed']))
                            <button onclick="markAsFailed({{ $delivery->id }})"
                                class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium hover:bg-red-200 transition-colors"
                                title="Klik jika pengiriman gagal">
                                <i class="fas fa-times-circle mr-1"></i>Gagal
                            </button>
                            @endif
                            @elseif (!$isAssignedToMe && $delivery->courier_id)
                            <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-500 rounded text-xs">
                                <i class="fas fa-user mr-1"></i>Assigned to {{ $delivery->courier->name ?? 'Other
                                Courier' }}
                            </span>
                            @elseif (!$delivery->courier_id)
                            <span
                                class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-600 rounded text-xs">
                                <i class="fas fa-clock mr-1"></i>Waiting Assignment
                            </span>
                            @endif
                            @elseif(auth()->user()->user_type === 'admin')
                            @if (!$delivery->courier_id)
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium hover:bg-purple-200 transition-colors">
                                    <i class="fas fa-user-plus mr-1"></i>Assign
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                    @foreach ($couriers ?? [] as $courier)
                                    <button
                                        onclick="assignCourier({{ $delivery->id }}, {{ $courier->id }}); document.querySelector('[x-data]').__x.$data.open = false"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        {{ $courier->name }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">
                                <i class="fas fa-user mr-1"></i>{{ $delivery->courier->name }}
                            </span>
                            @endif

                            <a href="{{ route('admin.deliveries.edit', $delivery) }}"
                                class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs font-medium hover:bg-emerald-200 transition-colors">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->user_type === 'admin' ? '9' : '7' }}"
                        class="py-12 text-center text-gray-500">
                        <i class="fas fa-truck text-4xl mb-4 text-gray-300"></i>
                        <div class="text-lg font-medium mb-2">Tidak ada pengiriman ditemukan</div>
                        <div class="text-sm">
                            @if (auth()->user()->user_type === 'admin')
                            Belum ada pengiriman dibuat atau coba ubah filter pencarian.
                            @elseif(auth()->user()->user_type === 'kurir')
                            Belum ada tugas pengiriman untuk Anda.
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="lg:hidden">
        @forelse($deliveries as $delivery)
        <div class="border-b border-gray-200 p-4 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    @if (auth()->user()->user_type === 'admin')
                    <input type="checkbox" name="delivery_ids[]" value="{{ $delivery->id }}"
                        class="delivery-checkbox rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 mt-1">
                    @endif
                    <div>
                        <div class="font-semibold text-gray-900 text-sm">
                            #DEL{{ str_pad($delivery->id, 4, '0', STR_PAD_LEFT) }}
                        </div>
                        <div class="text-xs text-gray-500">
                            Order: #{{ $delivery->order->order_number ?? 'N/A' }}
                        </div>
                    </div>
                </div>
                @php
                $statusInfo = [
                'assigned' => ['color' => 'blue', 'icon' => 'user-clock', 'text' => 'Ditugaskan'],
                'picked_up' => ['color' => 'yellow', 'icon' => 'hand-holding', 'text' => 'Diambil'],
                'in_transit' => ['color' => 'purple', 'icon' => 'shipping-fast', 'text' => 'Dalam Perjalanan'],
                'delivered' => ['color' => 'green', 'icon' => 'check-circle', 'text' => 'Terkirim'],
                'failed' => ['color' => 'red', 'icon' => 'times-circle', 'text' => 'Gagal'],
                ];
                $status = $statusInfo[$delivery->status] ?? ['color' => 'gray', 'icon' => 'question', 'text' =>
                'Unknown'];
                @endphp

                <span
                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-800">
                    <i class="fas fa-{{ $status['icon'] }} mr-1"></i>
                    {{ $status['text'] }}
                </span>
            </div>

            <div class="space-y-2 mb-4">
                <div class="flex items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($delivery->order->user->name ?? 'User') }}&background=10B981&color=fff"
                        alt="{{ $delivery->order->user->name ?? 'User' }}" class="w-6 h-6 rounded-full object-cover">
                    <div class="text-sm">
                        <span class="font-medium text-gray-900">{{ $delivery->order->user->name ?? 'Unknown' }}</span>
                        <span class="text-gray-500 ml-2">{{ $delivery->order->recipient_phone ?? 'No phone' }}</span>
                    </div>
                </div>

                @if (auth()->user()->user_type === 'admin' && $delivery->courier)
                <div class="flex items-center gap-2">
                    <i class="fas fa-user-tie text-gray-400 w-6 text-center"></i>
                    <span class="text-sm text-gray-700">{{ $delivery->courier->name }}</span>
                </div>
                @endif

                <div class="flex items-start gap-2">
                    <i class="fas fa-map-marker-alt text-gray-400 w-6 text-center mt-0.5"></i>
                    <div class="text-sm text-gray-700">
                        <div>{{ $delivery->order->shipping_address ?? 'Alamat tidak tersedia' }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $delivery->order->recipient_name ?? 'No recipient' }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fas fa-clock text-gray-400 w-6 text-center"></i>
                    <div class="text-sm text-gray-700">
                        {{ $delivery->assigned_at ? $delivery->assigned_at->format('d M Y H:i') : 'Not assigned' }}
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.deliveries.show', $delivery) }}"
                    class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-200 transition-colors">
                    <i class="fas fa-eye mr-1"></i>Detail
                </a>

                @if (auth()->user()->user_type === 'kurir')
                @php
                $isAssignedToMe = $delivery->courier_id && (int)$delivery->courier_id === (int)auth()->id();
                $canUpdate = $isAssignedToMe && !in_array($delivery->status, ['delivered', 'failed']);
                @endphp

                @if ($isAssignedToMe && $canUpdate)
                @if ($delivery->status === 'assigned')
                <button onclick="updateDeliveryStatus({{ $delivery->id }}, 'picked_up')"
                    class="inline-flex items-center px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-medium hover:bg-yellow-200 transition-colors">
                    <i class="fas fa-hand-holding mr-1"></i>Ambil
                </button>
                @endif

                @if ($delivery->status === 'picked_up')
                <button onclick="updateDeliveryStatus({{ $delivery->id }}, 'in_transit')"
                    class="inline-flex items-center px-3 py-2 bg-purple-100 text-purple-700 rounded-lg text-xs font-medium hover:bg-purple-200 transition-colors">
                    <i class="fas fa-shipping-fast mr-1"></i>Kirim
                </button>
                @endif

                @if (in_array($delivery->status, ['picked_up', 'in_transit']))
                <button onclick="updateDeliveryStatus({{ $delivery->id }}, 'delivered')"
                    class="inline-flex items-center px-3 py-2 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-medium hover:bg-emerald-200 transition-colors">
                    <i class="fas fa-check-circle mr-1"></i>Selesai
                </button>
                @endif

                @if (!in_array($delivery->status, ['delivered', 'failed']))
                <button onclick="markAsFailed({{ $delivery->id }})"
                    class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg text-xs font-medium hover:bg-red-200 transition-colors">
                    <i class="fas fa-times-circle mr-1"></i>Gagal
                </button>
                @endif
                @elseif (!$isAssignedToMe && $delivery->courier_id)
                <span class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-500 rounded-lg text-xs">
                    Assigned to {{ $delivery->courier->name ?? 'Other' }}
                </span>
                @elseif (!$delivery->courier_id)
                <span class="inline-flex items-center px-3 py-2 bg-orange-100 text-orange-600 rounded-lg text-xs">
                    Waiting Assignment
                </span>
                @endif
                @elseif(auth()->user()->user_type === 'admin')
                @if (!$delivery->courier_id)
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="inline-flex items-center px-3 py-2 bg-purple-100 text-purple-700 rounded-lg text-xs font-medium hover:bg-purple-200 transition-colors">
                        <i class="fas fa-user-plus mr-1"></i>Assign
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                        @foreach ($couriers ?? [] as $courier)
                        <button
                            onclick="assignCourier({{ $delivery->id }}, {{ $courier->id }}); document.querySelector('[x-data]').__x.$data.open = false"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            {{ $courier->name }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <a href="{{ route('admin.deliveries.edit', $delivery) }}"
                    class="inline-flex items-center px-3 py-2 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-medium hover:bg-emerald-200 transition-colors">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="py-12 text-center text-gray-500">
            <i class="fas fa-truck text-4xl mb-4 text-gray-300"></i>
            <div class="text-lg font-medium mb-2">Tidak ada pengiriman ditemukan</div>
            <div class="text-sm">
                @if (auth()->user()->user_type === 'admin')
                Belum ada pengiriman dibuat atau coba ubah filter pencarian.
                @elseif(auth()->user()->user_type === 'kurir')
                Belum ada tugas pengiriman untuk Anda.
                @endif
            </div>
        </div>
        @endforelse
    </div>

    @if (isset($deliveries) && $deliveries->hasPages())
    <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
        {{ $deliveries->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<div id="failedModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 p-4">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl">
            <h3 class="text-lg font-semibold mb-4 text-gray-900">Tandai Pengiriman Gagal</h3>
            <form id="failedForm">
                <div class="mb-6">
                    <label for="failedNotes" class="block text-sm font-medium text-gray-700 mb-2">Alasan
                        Kegagalan</label>
                    <textarea id="failedNotes" rows="4"
                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-sm"
                        placeholder="Jelaskan alasan pengiriman gagal..."></textarea>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
                    <button type="button" onclick="closeFailedModal()"
                        class="px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg border border-gray-300 transition-colors">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 font-medium shadow-sm">
                        Tandai Gagal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    console.log('Delivery scripts loading...');

    // Ensure CSRF token is available
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found. Please add <meta name="csrf-token" content="{{ csrf_token() }}"> to your layout.');
        return;
    }

    // =================================================================
    // GLOBAL FUNCTIONS - Available everywhere
    // =================================================================

    // Update Delivery Status Function
    window.updateDeliveryStatus = function(deliveryId, status) {
        console.log('updateDeliveryStatus called:', deliveryId, status);

        // Konfirmasi dengan pesan yang lebih spesifik
        let confirmMessage = '';
        switch(status) {
            case 'picked_up':
                confirmMessage = 'Apakah Anda yakin telah mengambil barang untuk pengiriman ini?';
                break;
            case 'in_transit':
                confirmMessage = 'Apakah Anda yakin barang sedang dalam perjalanan?';
                break;
            case 'delivered':
                confirmMessage = 'Apakah Anda yakin barang sudah terkirim ke tujuan?';
                break;
            default:
                confirmMessage = 'Apakah Anda yakin ingin mengubah status pengiriman ini?';
        }

        if (!confirm(confirmMessage)) {
            console.log('User cancelled status update');
            return;
        }

        // Disable semua tombol dengan delivery ID yang sama
        const buttons = document.querySelectorAll(`[onclick*="updateDeliveryStatus(${deliveryId}"]`);
        buttons.forEach(btn => {
            btn.disabled = true;
            btn.style.opacity = '0.6';
        });

        // Show loading pada tombol yang diklik
        const loadingBtn = document.querySelector(`[onclick*="updateDeliveryStatus(${deliveryId}, '${status}')"]`);
        const originalText = loadingBtn ? loadingBtn.innerHTML : '';
        if (loadingBtn) {
            loadingBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Processing...';
        }

        // Kirim request
        fetch(`/admin/deliveries/${deliveryId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: status
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert(data.message || 'Status berhasil diupdate');
                location.reload();
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat update status');
            }
        })
        .catch(error => {
            console.error('Error updating delivery status:', error);
            alert('Terjadi kesalahan: ' + error.message);

            // Re-enable buttons dan restore text
            buttons.forEach(btn => {
                btn.disabled = false;
                btn.style.opacity = '1';
            });

            if (loadingBtn) {
                // Restore original text based on status
                switch(status) {
                    case 'picked_up':
                        loadingBtn.innerHTML = '<i class="fas fa-hand-holding mr-1"></i>Ambil';
                        break;
                    case 'in_transit':
                        loadingBtn.innerHTML = '<i class="fas fa-shipping-fast mr-1"></i>Kirim';
                        break;
                    case 'delivered':
                        loadingBtn.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Selesai';
                        break;
                    default:
                        loadingBtn.innerHTML = originalText;
                }
            }
        });
    };

    // Assign Courier Function
    window.assignCourier = function(deliveryId, courierId) {
        console.log('assignCourier called:', deliveryId, courierId);

        fetch(`/admin/deliveries/${deliveryId}/assign`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                courier_id: courierId
            })
        })
        .then(response => {
            console.log('Assign response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Assign response data:', data);
            if (data.success) {
                alert(data.message || 'Kurir berhasil di-assign');
                location.reload();
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat assign kurir');
            }
        })
        .catch(error => {
            console.error('Error assigning courier:', error);
            alert('Terjadi kesalahan: ' + error.message);
        });
    };

    // Mark as Failed Function
    let currentDeliveryId = null;

    window.markAsFailed = function(deliveryId) {
        console.log('markAsFailed called:', deliveryId);
        currentDeliveryId = deliveryId;
        const modal = document.getElementById('failedModal');
        if (modal) {
            modal.classList.remove('hidden');
        } else {
            console.error('Failed modal not found');
        }
    };

    window.closeFailedModal = function() {
        const modal = document.getElementById('failedModal');
        if (modal) {
            modal.classList.add('hidden');
        }
        currentDeliveryId = null;
        const notesField = document.getElementById('failedNotes');
        if (notesField) {
            notesField.value = '';
        }
    };

    // Bulk Assign Courier Function
    window.bulkAssignCourier = function() {
        const checkboxes = document.querySelectorAll('.delivery-checkbox:checked');
        const courierSelect = document.getElementById('bulkCourierSelect');

        if (!courierSelect) {
            console.error('Bulk courier select not found');
            return;
        }

        const courierId = courierSelect.value;

        if (checkboxes.length === 0) {
            alert('Pilih minimal satu pengiriman');
            return;
        }

        if (!courierId) {
            alert('Pilih kurir terlebih dahulu');
            return;
        }

        const deliveryIds = Array.from(checkboxes).map(cb => cb.value);
        console.log('Bulk assign:', deliveryIds, 'to courier:', courierId);

        fetch('/admin/deliveries/bulk-assign', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                delivery_ids: deliveryIds,
                courier_id: courierId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message || 'Bulk assign berhasil');
                location.reload();
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat bulk assign');
            }
        })
        .catch(error => {
            console.error('Error bulk assign:', error);
            alert('Terjadi kesalahan: ' + error.message);
        });
    };

    // Utility Functions
    window.refreshDeliveries = function() {
        location.reload();
    };

    window.assignBulkDeliveries = function() {
        window.bulkAssignCourier();
    };

    // =================================================================
    // EVENT LISTENERS
    // =================================================================

    // Select All Checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.delivery-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }

    // Individual Checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('delivery-checkbox')) {
            updateBulkActions();
        }
    });

    // Failed Form Submit
    const failedForm = document.getElementById('failedForm');
    if (failedForm) {
        failedForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const notesField = document.getElementById('failedNotes');
            if (!notesField) {
                console.error('Failed notes field not found');
                return;
            }

            const notes = notesField.value.trim();
            if (!notes) {
                alert('Alasan kegagalan harus diisi');
                return;
            }

            if (!currentDeliveryId) {
                console.error('No delivery ID set for failed marking');
                return;
            }

            fetch(`/admin/deliveries/${currentDeliveryId}/failed`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    notes: notes
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Pengiriman berhasil ditandai gagal');
                    window.closeFailedModal();
                    location.reload();
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error marking as failed:', error);
                alert('Terjadi kesalahan: ' + error.message);
            });
        });
    }

    // =================================================================
    // HELPER FUNCTIONS
    // =================================================================

    function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.delivery-checkbox:checked');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');

        if (bulkActions && selectedCount) {
            if (checkboxes.length > 0) {
                bulkActions.style.display = 'block';
                selectedCount.textContent = checkboxes.length;
            } else {
                bulkActions.style.display = 'none';
            }
        }
    }

    // =================================================================
    // DEBUGGING HELPERS
    // =================================================================

    // Debug function untuk development
    window.debugDelivery = function(deliveryId) {
        console.log('Debugging delivery:', deliveryId);
        fetch(`/admin/deliveries/${deliveryId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Delivery data:', data);
        })
        .catch(error => {
            console.error('Debug error:', error);
        });
    };

    // Test function availability
    console.log('Functions loaded:', {
        updateDeliveryStatus: typeof window.updateDeliveryStatus,
        assignCourier: typeof window.assignCourier,
        markAsFailed: typeof window.markAsFailed,
        bulkAssignCourier: typeof window.bulkAssignCourier
    });

    console.log('Delivery scripts loaded successfully!');
});
</script>
@endpush

