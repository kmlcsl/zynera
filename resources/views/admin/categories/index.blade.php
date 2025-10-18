@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('page-title', 'Kelola Kategori')
@section('page-description', 'Kelola kategori produk di platform Zynera')

@section('page-actions')
    <div class="btn-group">
        <button onclick="bulkAction()" id="bulkActionBtn" style="display: none;" class="btn btn-warning">
            <i class="fas fa-tasks"></i>
            Bulk Action
        </button>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Kategori
        </a>
        <button onclick="refreshCategories()" class="btn btn-info">
            <i class="fas fa-sync-alt"></i>
            Refresh
        </button>
    </div>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-card-1">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>Total Kategori</h3>
                    <div class="stat-value">{{ $categories->total() }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card stat-card-2">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>Kategori Aktif</h3>
                    <div class="stat-value">{{ $categories->where('is_active', true)->count() }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card stat-card-3">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>Kategori Nonaktif</h3>
                    <div class="stat-value">{{ $categories->where('is_active', false)->count() }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card stat-card-4">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>Total Produk</h3>
                    <div class="stat-value">{{ $categories->sum('total_products_count') }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-shopping-basket"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="content-card">
        <div class="card-header">
            <h3>Filter Kategori</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="filter-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="search">Cari Kategori</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Nama kategori..." class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="sort">Urutkan</label>
                        <select name="sort" id="sort" class="form-control">
                            <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Terbaru</option>
                            <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Terlama</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                            <option value="products_desc" {{ request('sort') == 'products_desc' ? 'selected' : '' }}>Produk Terbanyak</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="content-card">
        <div class="card-header">
            <h3>Daftar Kategori</h3>
            <!-- Bulk Actions -->
            <div style="display: none;" id="bulkActions" class="bulk-actions">
                <div class="bulk-info">
                    <span><span id="selectedCount">0</span> kategori dipilih</span>
                </div>
                <div class="bulk-controls">
                    <select id="bulkActionSelect" class="form-control">
                        <option value="">Pilih Aksi</option>
                        <option value="activate">Aktifkan</option>
                        <option value="deactivate">Nonaktifkan</option>
                        <option value="delete">Hapus</option>
                    </select>
                    <button onclick="executeBulkAction()" class="btn btn-warning btn-sm">
                        <i class="fas fa-play"></i> Jalankan
                    </button>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="selectAll" class="form-check">
                        </th>
                        <th>Kategori</th>
                        <th>Slug</th>
                        <th>Produk</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="category-checkbox form-check">
                            </td>
                            <td>
                                <div class="category-info">
                                    @if ($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="category-image">
                                    @else
                                        <div class="category-placeholder">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                    @endif
                                    <div class="category-details">
                                        <div class="category-name">{{ $category->name }}</div>
                                        @if ($category->description)
                                            <div class="category-desc">{{ Str::limit($category->description, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="category-slug">{{ $category->slug }}</span>
                            </td>
                            <td>
                                <div class="product-count">{{ $category->total_products_count }} produk</div>
                                <div class="product-subtext">{{ $category->active_products_count }} aktif</div>
                            </td>
                            <td>
                                @if ($category->is_active)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times-circle"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="date-info">{{ $category->created_at->format('d M Y') }}</div>
                                <div class="date-subtext">{{ $category->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning" title="Edit Category">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="toggleStatus({{ $category->id }}, {{ $category->is_active ? 'false' : 'true' }})" 
                                            class="btn btn-sm btn-secondary" title="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="fas fa-{{ $category->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                    <button onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')"
                                            class="btn btn-sm btn-danger" title="Delete Category">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <div class="empty-title">Tidak ada kategori ditemukan</div>
                                <div class="empty-subtitle">
                                    Belum ada kategori dibuat atau coba ubah filter pencarian.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($categories->hasPages())
            <div class="card-footer">
                {{ $categories->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <script>
        // Select All Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.category-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateBulkActionsVisibility();
                });
            }

            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('category-checkbox')) {
                    updateBulkActionsVisibility();
                }
            });
        });

        function updateBulkActionsVisibility() {
            const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            const bulkActionBtn = document.getElementById('bulkActionBtn');
            const selectedCount = document.getElementById('selectedCount');

            if (checkedBoxes.length > 0) {
                bulkActions.style.display = 'block';
                bulkActionBtn.style.display = 'inline-flex';
                selectedCount.textContent = checkedBoxes.length;
            } else {
                bulkActions.style.display = 'none';
                bulkActionBtn.style.display = 'none';
            }
        }

        function executeBulkAction() {
            const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
            const categoryIds = Array.from(checkedBoxes).map(cb => cb.value);
            const action = document.getElementById('bulkActionSelect').value;

            if (categoryIds.length === 0) {
                alert('Pilih kategori terlebih dahulu');
                return;
            }

            if (!action) {
                alert('Pilih aksi terlebih dahulu');
                return;
            }

            const actionLabels = {
                'activate': 'mengaktifkan',
                'deactivate': 'menonaktifkan',
                'delete': 'menghapus'
            };

            if (confirm(`Apakah Anda yakin ingin ${actionLabels[action]} ${categoryIds.length} kategori?`)) {
                fetch('{{ route('admin.categories.bulk-action') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            category_ids: categoryIds,
                            action: action
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal melakukan aksi: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat melakukan aksi');
                    });
            }
        }

        function toggleStatus(categoryId, newStatus) {
            const statusText = newStatus === 'true' ? 'mengaktifkan' : 'menonaktifkan';

            if (confirm(`Apakah Anda yakin ingin ${statusText} kategori ini?`)) {
                fetch(`/admin/categories/${categoryId}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            is_active: newStatus === 'true'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal mengubah status: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengubah status');
                    });
            }
        }

        function deleteCategory(categoryId, categoryName) {
            if (confirm(
                    `Apakah Anda yakin ingin menghapus kategori "${categoryName}"? Tindakan ini tidak dapat dibatalkan.`)) {
                fetch(`/admin/categories/${categoryId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal menghapus kategori: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus kategori');
                    });
            }
        }

        function refreshCategories() {
            location.reload();
        }
    </script>
@endsection

