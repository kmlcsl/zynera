{{-- resources/views/admin/payments/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Pembayaran')
@section('page-title', 'Kelola Pembayaran')
@section('page-description', 'Kelola semua pembayaran di platform AgriConnect')

@section('page-actions')
    <div class="flex items-center gap-3">
        <button type="button" onclick="exportPayments()"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="fas fa-download mr-2"></i>
            Export Data
        </button>
        <button type="button" onclick="refreshData()"
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
            <i class="fas fa-sync-alt mr-2"></i>
            Refresh
        </button>
    </div>
@endsection

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6 mb-6">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Pembayaran</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="No. order, referensi..."
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
            </div>

            <div>
                <label for="method" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                <select name="method" id="method"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="">Semua Metode</option>
                    <option value="cod" {{ request('method') == 'cod' ? 'selected' : '' }}>COD</option>
                    <option value="transfer" {{ request('method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="qris" {{ request('method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                <select name="status" id="status"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran
                    </option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Pembayaran Gagal</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>

            <div>
                <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <select name="date_range" id="date_range"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="">Semua Periode</option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>30 Hari Terakhir
                    </option>
                    <option value="quarter" {{ request('date_range') == 'quarter' ? 'selected' : '' }}>3 Bulan Terakhir
                    </option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.payments.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Bulk Actions -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 hidden" id="bulk-actions">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">
                    <span id="selected-count">0</span> pembayaran dipilih
                </span>
                <div class="flex items-center gap-2">
                    @if (auth()->user()->user_type === 'admin')
                        <button type="button" onclick="bulkConfirmPayments()"
                            class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                            <i class="fas fa-check mr-1"></i>Konfirmasi Pembayaran
                        </button>
                        <button type="button" onclick="bulkRejectPayments()"
                            class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                            <i class="fas fa-times mr-1"></i>Tolak Pembayaran
                        </button>
                    @endif
                    <button type="button" onclick="exportSelected()"
                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        <i class="fas fa-download mr-1"></i>Export Dipilih
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">
                            <input type="checkbox" id="select-all"
                                class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        </th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">No. Order</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Pelanggan</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Metode</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Jumlah</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Status</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Tanggal</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payments ?? [] as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6">
                                <input type="checkbox"
                                    class="payment-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500"
                                    value="{{ $payment->id }}">
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-medium text-gray-900">#{{ $payment->order->order_number ?? 'N/A' }}</div>
                                <div class="text-gray-500 text-xs">ID: {{ $payment->id }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-medium text-gray-900">{{ $payment->order->user->name ?? 'N/A' }}</div>
                                <div class="text-gray-500 text-xs">{{ $payment->order->user->email ?? 'N/A' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
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
                                                : 'fa-qrcode') }} mr-1"></i>
                                    {{ $payment->method_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-medium text-gray-900">Rp
                                    {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                @if ($payment->reference_number)
                                    <div class="text-gray-500 text-xs">Ref: {{ $payment->reference_number }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
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
                                                    : 'fa-undo')) }} mr-1"></i>
                                    {{ $payment->status_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-gray-900">{{ $payment->created_at->format('d/m/Y') }}</div>
                                <div class="text-gray-500 text-xs">{{ $payment->created_at->format('H:i') }}</div>
                                @if ($payment->paid_at)
                                    <div class="text-green-600 text-xs">
                                        <i class="fas fa-check mr-1"></i>{{ $payment->paid_at->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.payments.show', $payment) }}"
                                        class="text-blue-600 hover:text-blue-800 p-1" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if ($payment->status === 'pending' && auth()->user()->user_type === 'admin')
                                        <button type="button" onclick="confirmPayment({{ $payment->id }})"
                                            class="text-green-600 hover:text-green-800 p-1" title="Konfirmasi Pembayaran">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" onclick="rejectPayment({{ $payment->id }})"
                                            class="text-red-600 hover:text-red-800 p-1" title="Tolak Pembayaran">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif

                                    @if ($payment->proof_image)
                                        <button type="button"
                                            onclick="viewProof('{{ asset('storage/' . $payment->proof_image) }}')"
                                            class="text-purple-600 hover:text-purple-800 p-1"
                                            title="Lihat Bukti Pembayaran">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    @endif

                                    {{-- @if ($payment->status === 'paid' && $payment->method !== 'cod')
                                        <button type="button" onclick="printReceipt({{ $payment->id }})"
                                            class="text-gray-600 hover:text-gray-800 p-1" title="Cetak Kwitansi">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    @endif --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-gray-500">
                                <i class="fas fa-credit-card text-4xl mb-4 text-gray-300"></i>
                                <div class="text-lg font-medium mb-2">Belum ada data pembayaran</div>
                                <div class="text-sm">Data pembayaran akan muncul setelah ada transaksi.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (isset($payments) && $payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Pembayaran</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-credit-card text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Sudah Dibayar</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['paid'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Rp {{ number_format($stats['paid_amount'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Menunggu Pembayaran</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Rp {{ number_format($stats['pending_amount'] ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pembayaran Gagal</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['failed'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Rp {{ number_format($stats['failed_amount'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
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
        // Bulk selection functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.payment-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        document.querySelectorAll('.payment-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        function updateBulkActions() {
            const selected = document.querySelectorAll('.payment-checkbox:checked');
            const bulkActions = document.getElementById('bulk-actions');
            const selectedCount = document.getElementById('selected-count');

            if (selected.length > 0) {
                bulkActions.classList.remove('hidden');
                selectedCount.textContent = selected.length;
            } else {
                bulkActions.classList.add('hidden');
            }
        }

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

        // Utility functions
        function refreshData() {
            location.reload();
        }

        function exportPayments() {
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'excel');
            window.open('?' + params.toString(), '_blank');
        }

        function exportSelected() {
            const selected = Array.from(document.querySelectorAll('.payment-checkbox:checked')).map(cb => cb.value);
            if (selected.length === 0) {
                alert('Pilih pembayaran yang ingin di-export');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('admin.payments.export-selected') }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);

            selected.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'payment_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        function printReceipt(paymentId) {
            window.open(`/admin/payments/${paymentId}/receipt`, '_blank');
        }

        function bulkConfirmPayments() {
            const selected = Array.from(document.querySelectorAll('.payment-checkbox:checked')).map(cb => cb.value);
            if (selected.length === 0) {
                alert('Pilih pembayaran yang ingin dikonfirmasi');
                return;
            }

            if (confirm(`Yakin ingin mengkonfirmasi ${selected.length} pembayaran?`)) {
                fetch('/admin/payments/bulk-confirm', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            payment_ids: selected
                        })
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

        function bulkRejectPayments() {
            const selected = Array.from(document.querySelectorAll('.payment-checkbox:checked')).map(cb => cb.value);
            if (selected.length === 0) {
                alert('Pilih pembayaran yang ingin ditolak');
                return;
            }

            const reason = prompt('Alasan penolakan pembayaran:');
            if (reason && confirm(`Yakin ingin menolak ${selected.length} pembayaran?`)) {
                fetch('/admin/payments/bulk-reject', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            payment_ids: selected,
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

        // Close modal when clicking outside
        document.getElementById('proofModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProofModal();
            }
        });
    </script>
@endpush
