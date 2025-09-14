@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('page-title', 'Kelola Produk')
@section('page-description', 'Kelola semua produk di platform AgriConnect')

@section('page-actions')
    <a href="{{ route('admin.products.create') }}"
        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
        <i class="fas fa-plus mr-2"></i>
        Tambah Produk
    </a>
@endsection

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6 mb-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Produk</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Nama produk..."
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category" id="category"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.products.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Produk</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Kategori</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Produsen</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Harga</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Stok</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Status</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->images ? asset('storage/' . $product->images[0]) : 'https://via.placeholder.com/60x60' }}"
                                        alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-gray-500 text-xs">{{ Str::limit($product->description, 40) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            {{-- <td class="py-4 px-6">
                                <div class="text-gray-900">{{ $product->user->name }}</div>
                                <div class="text-gray-500 text-xs">
                                    @if ($product->village)
                                        {{ $product->village->name }}, {{ $product->village->parent->name }}
                                    @else
                                        {{ $product->user->village ?? 'N/A' }}
                                    @endif
                                </div>
                            </td> --}}
                            <td class="py-4 px-6">
                                <div class="font-medium text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                                <div class="text-gray-500 text-xs">per {{ $product->unit }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-1">
                                    <span
                                        class="font-medium {{ $product->stock <= 5 ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ $product->stock }}
                                    </span>
                                    @if ($product->stock <= 5)
                                        <i class="fas fa-exclamation-triangle text-red-500 text-xs"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.products.show', $product) }}"
                                        class="text-blue-600 hover:text-blue-800 p-1" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                        class="text-yellow-600 hover:text-yellow-800 p-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                        class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-4 text-gray-300"></i>
                                <div class="text-lg font-medium mb-2">Belum ada produk</div>
                                <div class="text-sm">Tambahkan produk pertama untuk memulai.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Produk Aktif</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Stok Rendah</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['low_stock'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Produk Unggulan</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['featured'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
@endsection
