@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('page-title', 'Kelola Produk')
@section('page-description', 'Kelola semua produk di platform Zynera')

@section('page-actions')
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Tambah Produk
    </a>
@endsection

@section('content')
    <!-- Filters -->
    <div class="content-card">
        <div class="card-header">
            <h3>Filter Produk</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}" class="filter-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="search">Cari Produk</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Nama produk..." class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="content-card">
        <div class="card-header">
            <h3>Daftar Produk</h3>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="product-info">
                                    <img src="{{ $product->main_image_url ?: 'https://via.placeholder.com/60x60' }}"
                                         alt="{{ $product->name }}" class="product-image">
                                    <div class="product-details">
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="product-desc">{{ Str::limit($product->description, 40) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $product->category->name }}</span>
                            </td>
                            <td>
                                <div class="price-info">
                                    <div class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    <div class="unit">per {{ $product->unit }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="stock-info">
                                    <span class="stock-count {{ $product->stock <= 5 ? 'stock-low' : '' }}">
                                        {{ $product->stock }}
                                    </span>
                                    @if ($product->stock <= 5)
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $product->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                          class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <div class="empty-title">Belum ada produk</div>
                                <div class="empty-subtitle">Tambahkan produk pertama untuk memulai.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($products->hasPages())
            <div class="card-footer">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-card-1">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>Total Produk</h3>
                    <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card stat-card-2">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>Produk Aktif</h3>
                    <div class="stat-value">{{ $stats['active'] ?? 0 }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card stat-card-3">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>Stok Rendah</h3>
                    <div class="stat-value">{{ $stats['low_stock'] ?? 0 }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card stat-card-4">
            <div class="stat-content">
                <div class="stat-info">
                    <h3>Produk Unggulan</h3>
                    <div class="stat-value">{{ $stats['featured'] ?? 0 }}</div>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>
@endsection

