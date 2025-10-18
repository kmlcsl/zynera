@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')
@section('page-description', 'Tambahkan produk baru ke platform')

@section('page-actions')
    <a href="{{ route('admin.products.index') }}"
        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informasi Dasar</h3>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
                        <textarea name="description" id="description" rows="4" required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                            <select name="category_id" id="category_id" required
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Produsen *</label>
                            <select name="user_id" id="user_id" required
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                <option value="">Pilih Produsen</option>
                                @foreach ($producers as $producer)
                                    <option value="{{ $producer->id }}"
                                        {{ old('user_id') == $producer->id ? 'selected' : '' }}>
                                        {{ $producer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="district" class="block text-sm font-medium text-gray-700 mb-1">Kecamatan *</label>
                        <select name="district" id="district" required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            <option value="">Pilih Kecamatan</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}"
                                    {{ old('district') == $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('district')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="village_id" class="block text-sm font-medium text-gray-700 mb-1">Desa/Gampong *</label>
                        <select name="village_id" id="village_id" required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            <option value="">Pilih Desa/Gampong</option>
                        </select>
                        @error('village_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> --}}

                <!-- Price & Stock -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Harga & Stok</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga *</label>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" min="0"
                                step="0.01" required
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Satuan *</label>
                            <input type="text" name="unit" id="unit" value="{{ old('unit') }}"
                                placeholder="kg, pcs, liter" required
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            @error('unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stok *</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock') }}" min="0"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            @error('stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Berat (kg)</label>
                            <input type="number" name="weight" id="weight" value="{{ old('weight') }}" min="0"
                                step="0.01"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label>
                        <input type="file" name="images[]" id="images" multiple accept="image/*"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB per file.</p>
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Alamat Asal Produk</h3>
                <p class="text-sm text-gray-600 mb-4">Alamat ini akan digunakan untuk sistem penjemputan produk oleh pembeli</p>

                <div>
                    <label for="alamat_asal" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap *</label>
                    <textarea name="alamat_asal" id="alamat_asal" rows="4" required
                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                        placeholder="Masukkan alamat lengkap (Jalan, No. Rumah, RT/RW, Desa/Kelurahan, Kecamatan, Kode Pos)">{{ old('alamat_asal') }}</textarea>
                    @error('alamat_asal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        Contoh: Jl. Merdeka No. 123, RT 001/RW 002, Desa Sukamaju, Kecamatan Meulaboh, 23617
                    </p>
                </div>
            </div>

            <!-- Additional Options -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan Tambahan</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', 1) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                            </label>

                            <label class="flex items-center">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" value="1"
                                    {{ old('is_featured') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Produk Unggulan</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('admin.products.index') }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
@endsection

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const districtSelect = document.getElementById('district');
        const villageSelect = document.getElementById('village_id');

        districtSelect.addEventListener('change', function() {
            const districtId = this.value;

            // Clear village options
            villageSelect.innerHTML = '<option value="">Pilih Desa/Gampong</option>';

            if (districtId) {
                fetch(`/admin/products/villages-by-district?district_id=${districtId}`)
                    .then(response => response.json())
                    .then(villages => {
                        villages.forEach(function(village) {
                            const option = document.createElement('option');
                            option.value = village.id;
                            option.textContent = village.name;
                            villageSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching villages:', error);
                    });
            }
        });
    });
</script> --}}
