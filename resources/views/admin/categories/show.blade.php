@extends('layouts.admin')

@section('title', 'Detail Kategori - ' . $category->name)
@section('page-title', 'Detail Kategori')
@section('page-description', 'Informasi lengkap kategori ' . $category->name)

@section('page-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.categories.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>

        <a href="{{ route('admin.categories.edit', $category) }}"
            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700">
            <i class="fas fa-edit mr-2"></i>
            Edit Kategori
        </a>

        <button onclick="toggleStatus({{ $category->id }}, {{ $category->is_active ? 'false' : 'true' }})"
            class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-orange-700">
            <i class="fas fa-{{ $category->is_active ? 'eye-slash' : 'eye' }} mr-2"></i>
            {{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
        </button>
    </div>
@endsection

@section('content')
    <!-- Category Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-start gap-4">
                @if ($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                        class="w-24 h-24 rounded-lg object-cover border border-gray-200">
                @else
                    <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                        <i class="fas fa-tag text-gray-400 text-2xl"></i>
                    </div>
                @endif

                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
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
                    </div>

                    <div class="space-y-2 text-sm text-gray-600">
                        <div><strong>Slug:</strong> <code class="bg-gray-100 px-2 py-1 rounded">{{ $category->slug }}</code>
                        </div>
                        <div><strong>URL:</strong> <a href="{{ url('/categories/' . $category->slug) }}" target="_blank"
                                class="text-blue-600 hover:underline">{{ url('/categories/' . $category->slug) }}</a></div>
                        <div><strong>Dibuat:</strong> {{ $category->created_at->format('d M Y H:i') }}</div>
                        <div><strong>Diperbarui:</strong> {{ $category->updated_at->format('d M Y H:i') }}</div>
                    </div>

                    @if ($category->description)
                        <div class="mt-4">
                            <h3 class="font-medium text-gray-900 mb-2">Deskripsi</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $category->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="space-y-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-medium text-gray-900 mb-4">Statistik Produk</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Produk</span>
                        <span class="font-medium">{{ $stats['total_products'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Produk Aktif</span>
                        <span class="font-medium text-green-600">{{ $stats['active_products'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Produk Nonaktif</span>
                        <span class="font-medium text-red-600">{{ $stats['inactive_products'] }}</span>
                    </div>
                    <hr>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Revenue</span>
                        <span class="font-medium text-purple-600">Rp
                            {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products in Category -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Produk dalam Kategori</h3>
                <a href="{{ route('admin.products.index', ['category' => $category->slug]) }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        @if ($category->products->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left py-3 px-6 font-medium text-gray-600">Produk</th>
                            <th class="text-left py-3 px-6 font-medium text-gray-600">Produsen</th>
                            <th class="text-left py-3 px-6 font-medium text-gray-600">Harga</th>
                            <th class="text-left py-3 px-6 font-medium text-gray-600">Status</th>
                            <th class="text-left py-3 px-6 font-medium text-gray-600">Dibuat</th>
                            <th class="text-left py-3 px-6 font-medium text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($category->products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-6">
                                    <div class="flex items-center gap-3">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                                class="w-10 h-10 rounded object-cover border border-gray-200">
                                        @else
                                            <div
                                                class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center border border-gray-200">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900">{{ Str::limit($product->name, 30) }}
                                            </div>
                                            <div class="text-xs text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-6">
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">{{ $product->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $product->user->email ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td class="py-3 px-6">
                                    <div class="font-medium text-gray-900">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="py-3 px-6">
                                    @if ($product->is_active)
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
                                <td class="py-3 px-6">
                                    <div class="text-sm text-gray-900">{{ $product->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $product->created_at->format('H:i') }}</div>
                                </td>
                                <td class="py-3 px-6">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                            class="text-blue-600 hover:text-blue-800 p-1 rounded" title="View Product">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="text-green-600 hover:text-green-800 p-1 rounded" title="Edit Product">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-12 text-center text-gray-500">
                <i class="fas fa-shopping-basket text-4xl mb-4 text-gray-300"></i>
                <div class="text-lg font-medium mb-2">Belum ada produk dalam kategori ini</div>
                <div class="text-sm mb-4">Mulai tambahkan produk ke kategori {{ $category->name }}</div>
                <a href="{{ route('admin.products.create', ['category' => $category->slug]) }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Produk
                </a>
            </div>
        @endif
    </div>

    <script>
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
    </script>
@endsection
