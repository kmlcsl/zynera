@extends('layouts.admin')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan #' . $order->order_number)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">Informasi Pesanan</h3>
                    <span
                        class="px-3 py-1 bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800 rounded-full text-sm font-medium">
                        {{ $order->status_label }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Customer</p>
                        <p class="font-medium">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total</p>
                        <p class="font-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Assign Courier Section (UNTUK PRODUSEN) -->
            @if (auth()->user()->user_type === 'produsen' && in_array($order->status, ['paid', 'processing']))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-blue-900 mb-4">
                        <i class="fas fa-truck mr-2"></i>
                        Pilih Kurir untuk Pengiriman
                    </h3>

                    @if (!$order->delivery || !$order->delivery->courier_id)
                        <form id="assignCourierForm">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kurir</label>
                                <select name="courier_id" required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Pilih Kurir --</option>
                                    @foreach (App\Models\User::where('user_type', 'kurir')->get() as $courier)
                                        <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                <i class="fas fa-user-plus mr-2"></i>
                                Tugaskan Kurir
                            </button>
                        </form>
                    @else
                        <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                                <div>
                                    <p class="font-medium text-green-900">Kurir sudah ditugaskan</p>
                                    <p class="text-sm text-green-700">
                                        {{ $order->delivery->courier->name ?? 'Unknown Courier' }}</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">
                                {{ $order->delivery->status_label }}
                            </span>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Produk Pesanan</h3>
                </div>
                <div class="divide-y">
                    @foreach ($order->orderItems as $item)
                        <div class="p-6 flex items-center gap-4">
                            <div class="flex-1">
                                <h4 class="font-medium">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $item->quantity }} x Rp
                                    {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-bold text-green-600">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Tracking -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold mb-4">Status Tracking</h3>

                @if ($order->delivery)
                    <div class="space-y-3">
                        <div class="flex items-center text-green-600">
                            <div class="w-3 h-3 rounded-full bg-green-500 mr-3"></div>
                            <div>
                                <p class="font-medium">Kurir Ditugaskan</p>
                                <p class="text-xs text-gray-500">{{ $order->delivery->courier->name ?? 'Unknown' }}</p>
                            </div>
                        </div>

                        @if ($order->delivery->status === 'picked_up')
                            <div class="flex items-center text-blue-600">
                                <div class="w-3 h-3 rounded-full bg-blue-500 mr-3"></div>
                                <div>
                                    <p class="font-medium">Barang Diambil</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $order->delivery->picked_up_at?->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($order->delivery->status === 'in_transit')
                            <div class="flex items-center text-purple-600">
                                <div class="w-3 h-3 rounded-full bg-purple-500 mr-3"></div>
                                <div>
                                    <p class="font-medium">Dalam Pengiriman</p>
                                </div>
                            </div>
                        @endif

                        @if ($order->delivery->status === 'delivered')
                            <div class="flex items-center text-green-600">
                                <div class="w-3 h-3 rounded-full bg-green-500 mr-3"></div>
                                <div>
                                    <p class="font-medium">Terkirim</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $order->delivery->delivered_at?->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Belum ada tracking pengiriman</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.getElementById('assignCourierForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const courierId = formData.get('courier_id');

            if (!courierId) {
                alert('Pilih kurir terlebih dahulu');
                return;
            }

            fetch(`/admin/orders/{{ $order->id }}/assign-courier`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json'
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
                        alert(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menugaskan kurir');
                });
        });
    </script>
@endsection
