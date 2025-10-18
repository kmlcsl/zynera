{{-- resources/views/admin/payments/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Pembayaran #' . $payment->id)
@section('page-title', 'Detail Pembayaran #' . $payment->id)
@section('page-description', 'Informasi lengkap pembayaran dan transaksi terkait')

@section('page-actions')
    <div class="btn-group">
        @if ($payment->status === 'pending' && auth()->user()->user_type === 'admin')
            <button type="button" onclick="confirmPayment({{ $payment->id }})" class="btn btn-success">
                <i class="fas fa-check"></i>
                Konfirmasi Pembayaran
            </button>
            <button type="button" onclick="rejectPayment({{ $payment->id }})" class="btn btn-danger">
                <i class="fas fa-times"></i>
                Tolak Pembayaran
            </button>
        @endif
        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Payment Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pembayaran</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Pembayaran</label>
                        <p class="text-gray-900 font-mono">#{{ $payment->id }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Order</label>
                        <a href="{{ route('admin.orders.show', $payment->order) }}"
                            class="text-blue-600 hover:text-blue-800 font-mono">
                            #{{ $payment->order->order_number }}
                        </a>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $payment->method === 'cod'
                                ? 'bg-orange-100 text-orange-800'
                                : ($payment->method === 'transfer'
                                    ? 'bg-blue-100 text-blue-800'
                                    : 'bg-purple-100 text-purple-800') }}">
                            <i
                                class="fas {{ $payment->method === 'cod'
                                    ? 'fa-money-bill-wave'
                                    : ($payment->method === 'transfer'
                                        ? 'fa-university'
                                        : 'fa-qrcode') }} mr-2"></i>
                            {{ $payment->method_label }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $payment->status === 'paid'
                                ? 'bg-green-100 text-green-800'
                                : ($payment->status === 'pending'
                                    ? 'bg-yellow-100 text-yellow-800'
                                    : ($payment->status === 'failed'
                                        ? 'bg-red-100 text-red-800'
                                        : 'bg-blue-100 text-blue-800')) }}">
                            <i
                                class="fas {{ $payment->status === 'paid'
                                    ? 'fa-check-circle'
                                    : ($payment->status === 'pending'
                                        ? 'fa-clock'
                                        : ($payment->status === 'failed'
                                            ? 'fa-times-circle'
                                            : 'fa-undo')) }} mr-2"></i>
                            {{ $payment->status_label }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pembayaran</label>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </p>
                    </div>

                    @if ($payment->reference_number)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Referensi</label>
                            <p class="text-gray-900 font-mono">{{ $payment->reference_number }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dibuat</label>
                        <p class="text-gray-900">{{ $payment->created_at->format('d F Y, H:i') }} WIB</p>
                    </div>

                    @if ($payment->paid_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dibayar</label>
                            <p class="text-green-600 font-medium">{{ $payment->paid_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pelanggan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <p class="text-gray-900">{{ $payment->order->user->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-gray-900">{{ $payment->order->user->email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <p class="text-gray-900">{{ $payment->order->user->phone ?? 'Tidak tersedia' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman</label>
                        <p class="text-gray-900">{{ $payment->order->delivery_address ?? 'Tidak tersedia' }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Item Pesanan</h3>

                <div class="space-y-4">
                    @foreach ($payment->order->orderItems as $item)
                        <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg">
                            <img src="{{ $item->product->images ? asset('storage/' . $item->product->images[0]) : 'https://via.placeholder.com/80x80' }}"
                                alt="{{ $item->product->name }}" class="w-16 h-16 rounded-lg object-cover">

                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $item->product->user->name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $item->quantity }} {{ $item->product->unit }} ×
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="font-medium text-gray-900">
                                    Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Total -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">Total Pesanan</span>
                        <span class="text-xl font-bold text-gray-900">
                            Rp {{ number_format($payment->order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payment Proof -->
            @if ($payment->proof_image)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bukti Pembayaran</h3>

                    <div class="text-center">
                        <img src="{{ asset('storage/' . $payment->proof_image) }}" alt="Bukti Pembayaran"
                            class="w-full h-auto rounded-lg border border-gray-200 cursor-pointer"
                            onclick="viewProof('{{ asset('storage/' . $payment->proof_image) }}')">

                        <button type="button" onclick="viewProof('{{ asset('storage/' . $payment->proof_image) }}')"
                            class="mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-expand mr-1"></i>Lihat Ukuran Penuh
                        </button>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.orders.show', $payment->order) }}"
                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Lihat Detail Order
                    </a>

                    @if ($payment->order->delivery)
                        <a href="{{ route('admin.deliveries.show', $payment->order->delivery) }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-truck mr-2"></i>
                            Lihat Status Pengiriman
                        </a>
                    @endif

                    @if (auth()->user()->user_type === 'admin')
                        <a href="{{ route('admin.payments.edit', $payment) }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-yellow-300 rounded-lg text-sm font-medium text-yellow-700 bg-yellow-50 hover:bg-yellow-100">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Pembayaran
                        </a>
                    @endif
                </div>
            </div>

            <!-- Payment Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline Pembayaran</h3>

                <div class="space-y-4">
                    <!-- Created -->
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Pembayaran Dibuat</p>
                            <p class="text-xs text-gray-500">{{ $payment->created_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    @if ($payment->proof_image)
                        <!-- Proof Uploaded -->
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Bukti Pembayaran Diupload</p>
                                <p class="text-xs text-gray-500">{{ $payment->updated_at->format('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>
                    @endif

                    @if ($payment->paid_at)
                        <!-- Paid -->
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Pembayaran Dikonfirmasi</p>
                                <p class="text-xs text-gray-500">{{ $payment->paid_at->format('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>
                    @endif

                    @if ($payment->status === 'failed')
                        <!-- Failed -->
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Pembayaran Gagal/Ditolak</p>
                                <p class="text-xs text-gray-500">{{ $payment->updated_at->format('d F Y, H:i') }} WIB</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Statistics -->
            @if (auth()->user()->user_type === 'admin')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Pelanggan</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Pesanan</span>
                            <span
                                class="text-sm font-medium text-gray-900">{{ $payment->order->user->orders()->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Pembelian</span>
                            <span class="text-sm font-medium text-gray-900">
                                Rp {{ number_format($payment->order->user->orders()->sum('total_amount'), 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Pembayaran Sukses</span>
                            <span class="text-sm font-medium text-green-600">
                                {{ $payment->order->user->orders()->whereHas('payment', function ($q) {$q->where('status', 'paid');})->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Payment Proof Modal -->
    <div id="proofModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Bukti Pembayaran</h3>
                        <button type="button" onclick="closeProofModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="text-center">
                        <img id="proofImage" src="" alt="Bukti Pembayaran" class="max-w-full h-auto rounded-lg">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Payment actions
        function confirmPayment(paymentId) {
            if (confirm('Yakin ingin mengkonfirmasi pembayaran ini?')) {
                fetch(`/admin/payments/${paymentId}/confirm`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal mengkonfirmasi pembayaran: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengkonfirmasi pembayaran');
                    });
            }
        }

        function rejectPayment(paymentId) {
            const reason = prompt('Alasan penolakan pembayaran:');
            if (reason) {
                fetch(`/admin/payments/${paymentId}/reject`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            reason: reason
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal menolak pembayaran: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menolak pembayaran');
                    });
            }
        }

        // Proof modal
        function viewProof(imageUrl) {
            document.getElementById('proofImage').src = imageUrl;
            document.getElementById('proofModal').classList.remove('hidden');
        }

        function closeProofModal() {
            document.getElementById('proofModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('proofModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProofModal();
            }
        });
    </script>
@endpush
