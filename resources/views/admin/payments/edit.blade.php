{{-- resources/views/admin/payments/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Pembayaran #' . $payment->id)
@section('page-title', 'Edit Pembayaran #' . $payment->id)
@section('page-description', 'Edit informasi pembayaran dan status transaksi')

@section('page-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.payments.show', $payment) }}"
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-700">
            <i class="fas fa-eye mr-2"></i>
            Lihat Detail
        </a>
        <a href="{{ route('admin.payments.index') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Edit Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Edit Informasi Pembayaran</h3>

                <form action="{{ route('admin.payments.update', $payment) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Payment Method -->
                        <div class="md:col-span-2">
                            <label for="method" class="block text-sm font-medium text-gray-700 mb-2">
                                Metode Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <select name="method" id="method" required
                                class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('method') border-red-300 @enderror">
                                <option value="cod" {{ old('method', $payment->method) == 'cod' ? 'selected' : '' }}>
                                    Bayar di Tempat (COD)
                                </option>
                                <option value="transfer"
                                    {{ old('method', $payment->method) == 'transfer' ? 'selected' : '' }}>
                                    Transfer Bank
                                </option>
                                <option value="qris" {{ old('method', $payment->method) == 'qris' ? 'selected' : '' }}>
                                    QRIS
                                </option>
                            </select>
                            @error('method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" name="amount" id="amount"
                                    value="{{ old('amount', $payment->amount) }}" min="0" step="0.01" required
                                    class="w-full pl-12 rounded-lg focus:border-green-500 focus:ring-green-500 @error('amount') border-red-300 @enderror">
                            </div>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('status') border-red-300 @enderror">
                                <option value="pending"
                                    {{ old('status', $payment->status) == 'pending' ? 'selected' : '' }}>
                                    Menunggu Pembayaran
                                </option>
                                <option value="paid" {{ old('status', $payment->status) == 'paid' ? 'selected' : '' }}>
                                    Sudah Dibayar
                                </option>
                                <option value="failed" {{ old('status', $payment->status) == 'failed' ? 'selected' : '' }}>
                                    Pembayaran Gagal
                                </option>
                                <option value="refunded"
                                    {{ old('status', $payment->status) == 'refunded' ? 'selected' : '' }}>
                                    Dikembalikan
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reference Number -->
                        <div class="md:col-span-2">
                            <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Referensi
                            </label>
                            <input type="text" name="reference_number" id="reference_number"
                                value="{{ old('reference_number', $payment->reference_number) }}"
                                placeholder="Masukkan nomor referensi pembayaran..."
                                class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('reference_number') border-red-300 @enderror">
                            @error('reference_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Nomor referensi dari bank atau sistem pembayaran (opsional)
                            </p>
                        </div>
                    </div>

                    <!-- Warning untuk perubahan status -->
                    <div id="status-warning" class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-yellow-400 mr-3 mt-0.5"></i>
                            <div>
                                <h4 class="text-sm font-medium text-yellow-800">Peringatan Perubahan Status</h4>
                                <p class="mt-1 text-sm text-yellow-700" id="warning-text"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-8 flex items-center justify-end gap-4">
                        <a href="{{ route('admin.payments.show', $payment) }}"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Current Payment Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Saat Ini</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Order Terkait</label>
                        <a href="{{ route('admin.orders.show', $payment->order) }}"
                            class="text-blue-600 hover:text-blue-800 font-mono">
                            #{{ $payment->order->order_number }}
                        </a>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pelanggan</label>
                        <p class="text-gray-900">{{ $payment->order->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $payment->order->user->email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Order</label>
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $payment->order->status }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dibuat</label>
                        <p class="text-gray-900">{{ $payment->created_at->format('d F Y, H:i') }} WIB</p>
                    </div>

                    @if ($payment->paid_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dibayar</label>
                            <p class="text-green-600">{{ $payment->paid_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment History/Activity -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Perubahan</h3>

                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Pembayaran Dibuat</p>
                            <p class="text-xs text-gray-500">{{ $payment->created_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>

                    @if ($payment->updated_at != $payment->created_at)
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Terakhir Diupdate</p>
                                <p class="text-xs text-gray-500">{{ $payment->updated_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($payment->paid_at)
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Pembayaran Dikonfirmasi</p>
                                <p class="text-xs text-gray-500">{{ $payment->paid_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>

                <div class="space-y-3">
                    @if ($payment->status === 'pending')
                        <button type="button" onclick="quickConfirm()"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700">
                            <i class="fas fa-check mr-2"></i>
                            Konfirmasi Pembayaran
                        </button>
                    @endif

                    <a href="{{ route('admin.orders.show', $payment->order) }}"
                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Lihat Order Detail
                    </a>

                    @if ($payment->proof_image)
                        <button type="button" onclick="viewProof('{{ asset('storage/' . $payment->proof_image) }}')"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-purple-300 rounded-lg text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100">
                            <i class="fas fa-image mr-2"></i>
                            Lihat Bukti Pembayaran
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Proof Modal -->
    <div id="proofModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Bukti Pembayaran</h3>
                        <button type="button" onclick="closeProofModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
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
        const originalStatus = '{{ $payment->status }}';

        // Monitor status changes
        document.getElementById('status').addEventListener('change', function() {
            const newStatus = this.value;
            const warningDiv = document.getElementById('status-warning');
            const warningText = document.getElementById('warning-text');

            if (newStatus !== originalStatus) {
                let warning = '';

                switch (newStatus) {
                    case 'paid':
                        if (originalStatus === 'pending') {
                            warning =
                                'Mengubah status ke "Sudah Dibayar" akan mengupdate tanggal pembayaran dan status order terkait.';
                        } else {
                            warning = 'Mengubah status ke "Sudah Dibayar" akan mengupdate informasi pembayaran.';
                        }
                        break;
                    case 'failed':
                        warning = 'Mengubah status ke "Pembayaran Gagal" akan mempengaruhi status order terkait.';
                        break;
                    case 'refunded':
                        warning =
                            'Mengubah status ke "Dikembalikan" akan menandai bahwa pembayaran telah dikembalikan ke pelanggan.';
                        break;
                    case 'pending':
                        warning =
                            'Mengubah status kembali ke "Menunggu Pembayaran" akan mereset informasi pembayaran.';
                        break;
                }

                if (warning) {
                    warningText.textContent = warning;
                    warningDiv.classList.remove('hidden');
                } else {
                    warningDiv.classList.add('hidden');
                }
            } else {
                warningDiv.classList.add('hidden');
            }
        });

        // Quick confirm function
        function quickConfirm() {
            if (confirm('Yakin ingin mengkonfirmasi pembayaran ini?')) {
                document.getElementById('status').value = 'paid';

                // Trigger the change event to show warning
                document.getElementById('status').dispatchEvent(new Event('change'));

                // Scroll to form
                document.querySelector('form').scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }

        // Proof modal functions
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

        // Format amount input
        document.getElementById('amount').addEventListener('input', function() {
            // Remove non-numeric characters except decimal point
            this.value = this.value.replace(/[^0-9.]/g, '');

            // Ensure only one decimal point
            const parts = this.value.split('.');
            if (parts.length > 2) {
                this.value = parts[0] + '.' + parts.slice(1).join('');
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const amount = parseFloat(document.getElementById('amount').value);

            if (amount <= 0) {
                e.preventDefault();
                alert('Jumlah pembayaran harus lebih dari 0');
                document.getElementById('amount').focus();
                return false;
            }

            if (originalStatus !== document.getElementById('status').value) {
                if (!confirm('Anda mengubah status pembayaran. Yakin ingin melanjutkan?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    </script>
@endpush
