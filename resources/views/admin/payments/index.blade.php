{{-- resources/views/admin/payments/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Pembayaran')
@section('page-title', 'Kelola Pembayaran')
@section('page-description', 'Kelola semua pembayaran di platform Zynera')

@section('page-actions')
    <div class="btn-group">
        <button type="button" onclick="exportPayments()" class="btn btn-info">
            <i class="fas fa-download"></i>
            Export Data
        </button>
        <button type="button" onclick="refreshData()" class="btn btn-secondary">
            <i class="fas fa-sync-alt"></i>
            Refresh
        </button>
    </div>
@endsection

@section('content')
    <!-- Filters -->
    <div class="content-card">
        <div class="card-header">
            <h3>Filter Pembayaran</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payments.index') }}" class="filter-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="search">Cari Pembayaran</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="No. order, referensi..." class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="method">Metode Pembayaran</label>
                        <select name="method" id="method" class="form-control">
                            <option value="">Semua Metode</option>
                            <option value="cod" {{ request('method') == 'cod' ? 'selected' : '' }}>COD</option>
                            <option value="transfer" {{ request('method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="qris" {{ request('method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status Pembayaran</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Pembayaran Gagal</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_range">Periode</label>
                        <select name="date_range" id="date_range" class="form-control">
                            <option value="">Semua Periode</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>7 Hari Terakhir</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>30 Hari Terakhir</option>
                            <option value="quarter" {{ request('date_range') == 'quarter' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="content-card">
        <div class="card-header">
            <h3>Daftar Pembayaran</h3>
            <!-- Bulk Actions -->
            <div class="bulk-actions" style="display: none;" id="bulk-actions">
                <div class="bulk-info">
                    <span><span id="selected-count">0</span> pembayaran dipilih</span>
                </div>
                <div class="bulk-controls">
                    @if (auth()->user()->user_type === 'admin')
                        <button type="button" onclick="bulkConfirmPayments()" class="btn btn-success btn-sm">
                            <i class="fas fa-check"></i> Konfirmasi
                        </button>
                        <button type="button" onclick="bulkRejectPayments()" class="btn btn-danger btn-sm">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    @endif
                    <button type="button" onclick="exportSelected()" class="btn btn-info btn-sm">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="select-all" class="form-check">
                        </th>
                        <th>No. Order</th>
                        <th>Pelanggan</th>
                        <th>Metode</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments ?? [] as $payment)
                        <tr>
                            <td>
                                <input type="checkbox" class="payment-checkbox form-check" value="{{ $payment->id }}">
                            </td>
                            <td>
                                <div class="order-number">#{{ $payment->order->order_number ?? 'N/A' }}</div>
                                <div class="order-id">ID: {{ $payment->id }}</div>
                            </td>
                            <td>
                                <div class="customer-name">{{ $payment->order->user->name ?? 'N/A' }}</div>
                                <div class="customer-email">{{ $payment->order->user->email ?? 'N/A' }}</div>
                            </td>
                            <td>
                                @php
                                    $methodClass = match($payment->method ?? '') {
                                        'cod' => 'badge-cod',
                                        'transfer' => 'badge-transfer', 
                                        'qris' => 'badge-qris',
                                        default => 'badge-info'
                                    };
                                    $methodIcon = match($payment->method ?? '') {
                                        'cod' => 'fa-money-bill-wave',
                                        'transfer' => 'fa-university',
                                        'qris' => 'fa-qrcode', 
                                        default => 'fa-credit-card'
                                    };
                                @endphp
                                <span class="badge {{ $methodClass }}">
                                    <i class="fas {{ $methodIcon }}"></i> {{ $payment->method_label ?? $payment->method }}
                                </span>
                            </td>
                            <td>
                                <div class="payment-amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                @if ($payment->reference_number)
                                    <div class="payment-reference">Ref: {{ $payment->reference_number }}</div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = match($payment->status ?? '') {
                                        'paid' => 'badge-paid',
                                        'pending' => 'badge-pending',
                                        'failed' => 'badge-failed',
                                        'refunded' => 'badge-refunded',
                                        default => 'badge-info'
                                    };
                                    $statusIcon = match($payment->status ?? '') {
                                        'paid' => 'fa-check-circle',
                                        'pending' => 'fa-clock',
                                        'failed' => 'fa-times-circle',
                                        'refunded' => 'fa-undo',
                                        default => 'fa-info-circle'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    <i class="fas {{ $statusIcon }}"></i> {{ $payment->status_label ?? ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="payment-date">{{ $payment->created_at->format('d/m/Y') }}</div>
                                <div class="payment-time">{{ $payment->created_at->format('H:i') }}</div>
                                @if ($payment->paid_at)
                                    <div class="payment-paid-at">
                                        <i class="fas fa-check"></i> {{ $payment->paid_at->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if ($payment->status === 'pending' && auth()->user()->user_type === 'admin')
                                        <button type="button" onclick="confirmPayment({{ $payment->id }})" 
                                                class="btn btn-sm btn-success" title="Konfirmasi Pembayaran">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" onclick="rejectPayment({{ $payment->id }})" 
                                                class="btn btn-sm btn-danger" title="Tolak Pembayaran">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    @if ($payment->proof_image)
                                        <button type="button" onclick="viewProof('{{ asset('storage/' . $payment->proof_image) }}')" 
                                                class="btn btn-sm btn-secondary" title="Lihat Bukti Pembayaran">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="empty-title">Belum ada data pembayaran</div>
                                <div class="empty-subtitle">Data pembayaran akan muncul setelah ada transaksi.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if (isset($payments) && $payments->hasPages())
            <div class="card-footer">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-card-1">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>Total Pembayaran</h3>
                    <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card stat-card-2">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>Sudah Dibayar</h3>
                    <div class="stat-value">{{ $stats['paid'] ?? 0 }}</div>
                    <div class="stat-change">Rp {{ number_format($stats['paid_amount'] ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card stat-card-3">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>Menunggu Pembayaran</h3>
                    <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="stat-change">Rp {{ number_format($stats['pending_amount'] ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card stat-card-4">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>Pembayaran Gagal</h3>
                    <div class="stat-value">{{ $stats['failed'] ?? 0 }}</div>
                    <div class="stat-change">Rp {{ number_format($stats['failed_amount'] ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
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

