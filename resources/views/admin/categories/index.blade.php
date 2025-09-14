@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('page-title', 'Kelola Kategori')
@section('page-description', 'Kelola kategori produk di platform AgriConnect')

@section('page-actions')
    <div class="flex items-center gap-3">
        <button onclick="bulkAction()" id="bulkActionBtn" style="display: none;"
            class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-orange-700">
            <i class="fas fa-tasks mr-2"></i>
            Bulk Action
        </button>

        <a href="{{ route('admin.categories.create') }}"
            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>
            Tambah Kategori
        </a>

        <button onclick="refreshCategories()"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700">
            <i class="fas fa-sync-alt mr-2"></i>
            Refresh
        </button>
    </div>
@endsection

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Kategori</p>
                    <p class="text-xl font-bold text-gray-900">{{ $categories->total() }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tags text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Kategori Aktif</p>
                    <p class="text-xl font-bold text-green-600">{{ $categories->where('is_active', true)->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Kategori Nonaktif</p>
                    <p class="text-xl font-bold text-red-600">{{ $categories->where('is_active', false)->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Produk</p>
                    <p class="text-xl font-bold text-purple-600">{{ $categories->sum('total_products_count') }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-basket text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6 mb-6">
        <form method="GET" action="{{ route('admin.categories.index') }}"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Kategori</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Nama kategori..."
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                <select name="sort" id="sort"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Terbaru
                    </option>
                    <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>Terlama
                    </option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                    <option value="products_desc" {{ request('sort') == 'products_desc' ? 'selected' : '' }}>Produk
                        Terbanyak</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.categories.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Bulk Actions -->
        <div class="border-b border-gray-200 p-4 bg-gray-50" style="display: none;" id="bulkActions">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">
                        <span id="selectedCount">0</span> kategori dipilih
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <select id="bulkActionSelect" class="text-sm border-gray-300 rounded">
                        <option value="">Pilih Aksi</option>
                        <option value="activate">Aktifkan</option>
                        <option value="deactivate">Nonaktifkan</option>
                        <option value="delete">Hapus</option>
                    </select>
                    <button onclick="executeBulkAction()"
                        class="px-3 py-1 bg-orange-600 text-white text-sm rounded hover:bg-orange-700">
                        <i class="fas fa-play mr-1"></i>Jalankan
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left py-4 px-4">
                            <input type="checkbox" id="selectAll"
                                class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        </th>
                        <th class="text-left py-4 px-4 font-medium text-gray-600">Kategori</th>
                        <th class="text-left py-4 px-4 font-medium text-gray-600">Slug</th>
                        <th class="text-left py-4 px-4 font-medium text-gray-600">Produk</th>
                        <th class="text-left py-4 px-4 font-medium text-gray-600">Status</th>
                        <th class="text-left py-4 px-4 font-medium text-gray-600">Dibuat</th>
                        <th class="text-left py-4 px-4 font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-4">
                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                    class="category-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500">
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    @if ($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}"
                                            alt="{{ $category->name }}"
                                            class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                    @else
                                        <div
                                            class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                            <i class="fas fa-tag text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $category->name }}</div>
                                        @if ($category->description)
                                            <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">
                                                {{ Str::limit($category->description, 50) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $category->slug }}</code>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">
                                        {{ $category->total_products_count }} produk
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $category->active_products_count }} aktif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                @if ($category->is_active)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm text-gray-900">
                                    {{ $category->created_at->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $category->created_at->format('H:i') }}
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.categories.show', $category) }}"
                                        class="text-blue-600 hover:text-blue-800 p-1 rounded" title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>

                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="text-green-600 hover:text-green-800 p-1 rounded" title="Edit Category">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>

                                    <button
                                        onclick="toggleStatus({{ $category->id }}, {{ $category->is_active ? 'false' : 'true' }})"
                                        class="text-orange-600 hover:text-orange-800 p-1 rounded"
                                        title="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="fas fa-{{ $category->is_active ? 'eye-slash' : 'eye' }} text-sm"></i>
                                    </button>

                                    <button onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')"
                                        class="text-red-600 hover:text-red-800 p-1 rounded" title="Delete Category">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">
                                <i class="fas fa-tags text-4xl mb-4 text-gray-300"></i>
                                <div class="text-lg font-medium mb-2">Tidak ada kategori ditemukan</div>
                                <div class="text-sm">
                                    Belum ada kategori dibuat atau coba ubah filter pencarian.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
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
