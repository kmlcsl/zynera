@extends('layouts.admin')

@section('title', 'Detail Produk')
@section('page-title', 'Detail Produk')
@section('page-description', 'Informasi lengkap produk')

@section('page-actions')
    <div class="flex gap-3">
        <a href="{{ route('admin.products.edit', $product) }}"
            class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-yellow-700">
            <i class="fas fa-edit mr-2"></i>
            Edit Produk
        </a>
        <a href="{{ route('admin.products.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Product Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Product Images -->
            @if ($product->images && count($product->images) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Gambar Produk</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($product->images as $image)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}"
                                    class="w-full h-32 object-cover rounded-lg">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 rounded-lg flex items-center justify-center">
                                    <button onclick="openImageModal('{{ asset('storage/' . $image) }}')"
                                        class="opacity-0 group-hover:opacity-100 bg-white text-gray-800 px-3 py-1 rounded-lg text-sm font-medium transition-opacity">
                                        <i class="fas fa-expand mr-1"></i> Perbesar
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Product Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Produk</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nama Produk</label>
                        <p class="text-gray-900 font-medium">{{ $product->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Kategori</label>
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $product->category->name }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Harga</label>
                        <p class="text-gray-900 font-medium text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                        <p class="text-gray-500 text-sm">per {{ $product->unit }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Stok</label>
                        <p class="text-gray-900 font-medium {{ $product->stock <= 5 ? 'text-red-600' : '' }}">
                            {{ $product->stock }} {{ $product->unit }}
                            @if ($product->stock <= 5)
                                <i class="fas fa-exclamation-triangle text-red-500 ml-1"></i>
                            @endif
                        </p>
                    </div>

                    @if ($product->weight)
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Berat</label>
                            <p class="text-gray-900">{{ $product->weight }} kg</p>
                        </div>
                    @endif

                    @if ($product->expired_date)
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Kadaluarsa</label>
                            <p class="text-gray-900">{{ $product->expired_date->format('d M Y') }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                        <div class="flex gap-2">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                            @if ($product->is_featured)
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-star mr-1"></i> Unggulan
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Slug</label>
                        <p class="text-gray-500 text-sm font-mono">{{ $product->slug }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Deskripsi</label>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900 leading-relaxed">{{ $product->description }}</p>
                    </div>
                </div>

                @php
                    $ingredients = is_array($product->ingredients)
                        ? $product->ingredients
                        : (json_decode($product->ingredients, true) ?:
                        []);
                @endphp
                @if ($ingredients && count($ingredients) > 0)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Bahan-bahan</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($ingredients as $ingredient)
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-sm bg-gray-100 text-gray-800">
                                    {{ $ingredient }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Reviews -->
            @if ($product->reviews->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Review Pelanggan
                        <span class="text-sm font-normal text-gray-500">({{ $product->reviews->count() }} review)</span>
                    </h3>

                    <div class="space-y-4">
                        @foreach ($product->reviews->take(5) as $review)
                            <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ $review->user->name }}&background=8FBC8F&color=fff"
                                            alt="{{ $review->user->name }}" class="w-8 h-8 rounded-full">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $review->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex text-yellow-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-300' }}"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                @if ($review->comment)
                                    <p class="text-gray-700">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Producer Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Produsen</h3>

                <div class="flex items-center gap-3 mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ $product->user->name }}&background=8FBC8F&color=fff"
                        alt="{{ $product->user->name }}" class="w-12 h-12 rounded-full">
                    <div>
                        <p class="font-medium text-gray-900">{{ $product->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $product->user->email }}</p>
                    </div>
                </div>

                @if ($product->user->village || $product->user->district)
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        {{ $product->user->village ?? '' }}{{ $product->user->village && $product->user->district ? ', ' : '' }}{{ $product->user->district ?? '' }}
                    </div>
                @endif

                @if ($product->user->phone)
                    <div class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-phone mr-1"></i>
                        {{ $product->user->phone }}
                    </div>
                @endif
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h3>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Rating Rata-rata</span>
                        <div class="flex items-center gap-1">
                            <span class="font-medium">{{ number_format($product->average_rating, 1) }}</span>
                            <div class="flex text-yellow-400">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-3 h-3 {{ $i <= $product->average_rating ? 'fill-current' : 'text-gray-300' }}"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Review</span>
                        <span class="font-medium">{{ $product->total_reviews }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Dibuat</span>
                        <span class="font-medium">{{ $product->created_at->format('d M Y') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Terakhir Update</span>
                        <span class="font-medium">{{ $product->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>

                <div class="space-y-3">
                    <a href="{{ route('products.show', $product->slug) }}" target="_blank"
                        class="w-full bg-blue-600 text-white text-center px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors block">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Lihat di Website
                    </a>

                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                        onsubmit="return confirm('Yakin ingin menghapus produk ini?')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus Produk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 p-4 hidden items-center justify-center">
    </div>
    <div class="relative max-w-4xl max-h-full">
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
        <button onclick="closeImageModal()"
            class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75">
            <i class="fas fa-times"></i>
        </button>
    </div>
    </div>

    <script>
        function openImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
    </script>
@endsection
