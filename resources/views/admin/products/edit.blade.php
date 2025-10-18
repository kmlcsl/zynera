@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')
@section('page-description', 'Edit informasi produk')

@section('page-actions')
    <a href="{{ route('admin.products.index') }}"
        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informasi Dasar</h3>

                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                            required
                            class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('name') border-red-300 @enderror"
                            placeholder="Masukkan nama produk...">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}"
                            required
                            class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('slug') border-red-300 @enderror"
                            placeholder="produk-url-friendly">
                        <p class="mt-1 text-xs text-gray-500">URL: <span id="preview-url">{{ url('/products/') }}/</span>
                        </p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
                        <textarea name="description" id="description" rows="4" required
                            class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('description') border-red-300 @enderror"
                            placeholder="Deskripsi produk...">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                        <select name="category_id" id="category_id" required
                            class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('category_id') border-red-300 @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Producer -->
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Produsen *</label>
                        <select name="user_id" id="user_id" required
                            class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('user_id') border-red-300 @enderror">
                            <option value="">Pilih Produsen</option>
                            @foreach ($producers as $producer)
                                <option value="{{ $producer->id }}"
                                    {{ old('user_id', $product->user_id) == $producer->id ? 'selected' : '' }}>
                                    {{ $producer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="district" class="block text-sm font-medium text-gray-700 mb-1">Kecamatan *</label>
                            <select name="district" id="district" required
                                class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('district') border-red-300 @enderror">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}"
                                        {{ old('district', $product->village ? $product->village->parent_id : '') == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('district')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="village_id" class="block text-sm font-medium text-gray-700 mb-1">Desa/Gampong
                                *</label>
                            <select name="village_id" id="village_id" required
                                class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('village_id') border-red-300 @enderror">
                                <option value="">Pilih Desa/Gampong</option>
                                @if ($product->village_id)
                                    <option value="{{ $product->village_id }}" selected>
                                        {{ $product->village->name }}
                                    </option>
                                @endif
                            </select>
                            @error('village_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div> --}}

                    <!-- Status -->
                    <div>
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                                Produk Aktif
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">
                                Produk Unggulan
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Tandai untuk menampilkan produk di frontend</p>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('is_featured')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Price, Stock & Statistics -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Harga, Stok & Statistik</h3>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) *</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}"
                            required min="0" step="0.01"
                            class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('price') border-red-300 @enderror"
                            placeholder="0">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock & Unit -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stok *</label>
                            <input type="number" name="stock" id="stock"
                                value="{{ old('stock', $product->stock) }}" required min="0"
                                class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('stock') border-red-300 @enderror"
                                placeholder="0">
                            @error('stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Satuan *</label>
                            <input type="text" name="unit" id="unit"
                                value="{{ old('unit', $product->unit) }}" required
                                class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('unit') border-red-300 @enderror"
                                placeholder="kg, pcs, liter">
                            @error('unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Weight & Expired Date -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Berat
                                (gram)</label>
                            <input type="number" name="weight" id="weight"
                                value="{{ old('weight', $product->weight) }}" min="0" step="0.01"
                                class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('weight') border-red-300 @enderror"
                                placeholder="0">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="expired_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                Kadaluarsa</label>
                            <input type="date" name="expired_date" id="expired_date"
                                value="{{ old('expired_date', $product->expired_date ? $product->expired_date->format('Y-m-d') : '') }}"
                                class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('expired_date') border-red-300 @enderror">
                            @error('expired_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Statistics -->
                    @php
                        $totalReviews = $product->reviews()->count();
                        $averageRating = $product->reviews()->avg('rating') ?? 0;
                        $totalOrders = $product->orderItems()->sum('quantity');
                    @endphp

                    <div class="grid grid-cols-1 gap-3">
                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-blue-700">Total Ulasan</span>
                                <span class="text-lg font-bold text-blue-800">{{ $totalReviews }}</span>
                            </div>
                        </div>

                        <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-green-700">Rating Rata-rata</span>
                                <span
                                    class="text-lg font-bold text-green-800">{{ number_format($averageRating, 1) }}/5</span>
                            </div>
                        </div>

                        <div class="p-3 bg-purple-50 rounded-lg border border-purple-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-purple-700">Total Terjual</span>
                                <span class="text-lg font-bold text-purple-800">{{ $totalOrders }}</span>
                            </div>
                        </div>

                        @if ($product->stock <= 5)
                            <div class="p-3 bg-orange-50 rounded-lg border border-orange-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-orange-700">Status Stok</span>
                                    <span class="text-sm font-bold text-orange-800">Stok Rendah</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Timestamps -->
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="space-y-1 text-xs text-gray-600">
                            <div><strong>Dibuat:</strong> {{ $product->created_at->format('d M Y H:i') }}</div>
                            <div><strong>Diperbarui:</strong> {{ $product->updated_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images Section -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Gambar Produk</h3>

                <!-- Upload New Images -->
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar Baru</label>
                    <input type="file" name="images[]" id="images" accept="image/*" multiple
                        class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('images.*') border-red-300 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB per file. (Kosongkan jika
                        tidak ingin mengubah gambar)</p>
                    @error('images.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Ingredients Section -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Bahan/Komposisi (Opsional)</h3>

                <div id="ingredients-container">
                    @php
                        $ingredients = old('ingredients', $product->ingredients);
                        if (!is_array($ingredients)) {
                            $ingredients = [];
                        }
                    @endphp

                    @if (count($ingredients) > 0)
                        @foreach ($ingredients as $index => $ingredient)
                            <div class="ingredient-item flex gap-2 mb-2">
                                <input type="text" name="ingredients[]" value="{{ $ingredient }}"
                                    class="flex-1 rounded-lg focus:border-green-500 focus:ring-green-500"
                                    placeholder="Nama bahan...">
                                <button type="button"
                                    class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-ingredient">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="ingredient-item flex gap-2 mb-2">
                            <input type="text" name="ingredients[]"
                                class="flex-1 rounded-lg focus:border-green-500 focus:ring-green-500"
                                placeholder="Nama bahan...">
                            <button type="button"
                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-ingredient">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <button type="button" id="add-ingredient"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    <i class="fas fa-plus mr-2"></i>Tambah Bahan
                </button>
            </div>

            <!-- Current Images Display -->
            @if ($product->has_images)
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Gambar Saat Ini ({{ count($product->image_urls) }}
                        gambar)</h3>
                    <div class="grid grid-cols-4 gap-4">
                        @foreach ($product->image_urls as $imageUrl)
                            <div class="relative">
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                    class="w-full h-24 object-cover rounded-lg border border-gray-200">
                                <div
                                    class="absolute top-1 right-1 bg-black bg-opacity-50 text-white text-xs px-1 py-0.5 rounded">
                                    Current
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- New Images Preview -->
            <div id="images-preview" class="border-t pt-6 hidden">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Preview Gambar Baru</h3>
                <div id="preview-container" class="grid grid-cols-4 gap-4">
                    <!-- Preview images will be added here -->
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('admin.products.index') }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Update Produk
                </button>
            </div>
        </form>
    </div>

    <script>
        // Auto update slug from name
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const slug = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/-+/g, '-') // Replace multiple hyphens with single
                .trim('-'); // Remove leading/trailing hyphens

            // Only auto-update if slug field is empty or matches the original
            const slugField = document.getElementById('slug');
            const originalSlug = '{{ $product->slug }}';

            if (!slugField.value || slugField.value === originalSlug) {
                slugField.value = slug;
            }

            updatePreviewUrl();
        });

        // Update slug preview
        document.getElementById('slug').addEventListener('input', function() {
            updatePreviewUrl();
        });

        function updatePreviewUrl() {
            const slug = document.getElementById('slug').value;
            const baseUrl = '{{ url('/products') }}/';
            document.getElementById('preview-url').textContent = baseUrl + (slug || 'produk-url');
        }

        // Multiple images preview functionality
        document.getElementById('images').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const previewContainer = document.getElementById('preview-container');
            const previewSection = document.getElementById('images-preview');

            // Clear previous previews
            previewContainer.innerHTML = '';

            if (files.length > 0) {
                files.forEach((file, index) => {
                    // Validate file size (2MB = 2 * 1024 * 1024 bytes)
                    if (file.size > 2 * 1024 * 1024) {
                        alert(`File ${file.name} terlalu besar. Maksimal 2MB.`);
                        return;
                    }

                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                    if (!allowedTypes.includes(file.type)) {
                        alert(`File ${file.name} tidak diizinkan. Gunakan JPG, PNG, atau GIF.`);
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'relative';
                        previewDiv.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${index + 1}"
                                class="w-full h-24 object-cover rounded-lg border border-gray-200">
                            <div class="absolute top-1 right-1 bg-green-500 text-white text-xs px-1 py-0.5 rounded">
                                New
                            </div>
                        `;
                        previewContainer.appendChild(previewDiv);
                    };
                    reader.readAsDataURL(file);
                });

                previewSection.classList.remove('hidden');
            } else {
                previewSection.classList.add('hidden');
            }
        });

        // Add ingredient functionality
        document.getElementById('add-ingredient').addEventListener('click', function() {
            const container = document.getElementById('ingredients-container');
            const newItem = document.createElement('div');
            newItem.className = 'ingredient-item flex gap-2 mb-2';
            newItem.innerHTML = `
                <input type="text" name="ingredients[]"
                    class="flex-1 rounded-lg focus:border-green-500 focus:ring-green-500"
                    placeholder="Nama bahan...">
                <button type="button" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-ingredient">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newItem);
        });

        // Remove ingredient functionality
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-ingredient') || e.target.closest('.remove-ingredient')) {
                const ingredientItem = e.target.closest('.ingredient-item');
                if (document.querySelectorAll('.ingredient-item').length > 1) {
                    ingredientItem.remove();
                } else {
                    // Clear the input instead of removing if it's the last one
                    ingredientItem.querySelector('input').value = '';
                }
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const slug = document.getElementById('slug').value.trim();
            const price = document.getElementById('price').value;
            const stock = document.getElementById('stock').value;

            if (!name) {
                e.preventDefault();
                alert('Nama produk harus diisi.');
                document.getElementById('name').focus();
                return;
            }

            if (!slug) {
                e.preventDefault();
                alert('Slug produk harus diisi.');
                document.getElementById('slug').focus();
                return;
            }

            if (!price || price < 0) {
                e.preventDefault();
                alert('Harga produk harus diisi dan tidak boleh negatif.');
                document.getElementById('price').focus();
                return;
            }

            if (!stock || stock < 0) {
                e.preventDefault();
                alert('Stok produk harus diisi dan tidak boleh negatif.');
                document.getElementById('stock').focus();
                return;
            }

            if (name.length < 2) {
                e.preventDefault();
                alert('Nama produk minimal 2 karakter.');
                document.getElementById('name').focus();
                return;
            }
        });

        // Initialize preview URL on page load
        updatePreviewUrl();

        // const districtSelect = document.getElementById('district');
        // const villageSelect = document.getElementById('village_id');

        // function loadVillages(districtId, selectedVillageId = null) {
        //     if (!districtId) {
        //         villageSelect.innerHTML = '<option value="">Pilih Desa/Gampong</option>';
        //         return;
        //     }

        //     fetch(`/admin/products/villages-by-district?district_id=${districtId}`)
        //         .then(response => response.json())
        //         .then(villages => {
        //             villageSelect.innerHTML = '<option value="">Pilih Desa/Gampong</option>';

        //             villages.forEach(function(village) {
        //                 const option = document.createElement('option');
        //                 option.value = village.id;
        //                 option.textContent = village.name;

        //                 // Set selected jika ini adalah village yang sudah dipilih
        //                 if (selectedVillageId && village.id == selectedVillageId) {
        //                     option.selected = true;
        //                 }

        //                 villageSelect.appendChild(option);
        //             });
        //         })
        //         .catch(error => {
        //             console.error('Error fetching villages:', error);
        //             villageSelect.innerHTML = '<option value="">Error loading villages</option>';
        //         });
        // }

        // districtSelect.addEventListener('change', function() {
        //     const districtId = this.value;
        //     loadVillages(districtId);
        // });

        // // PENTING: Load villages saat halaman pertama kali dibuka
        // document.addEventListener('DOMContentLoaded', function() {
        //     const initialDistrictId = districtSelect.value;
        //     const initialVillageId = '{{ $product->village_id ?? '' }}';

        //     if (initialDistrictId) {
        //         loadVillages(initialDistrictId, initialVillageId);
        //     }
        // });
    </script>
@endsection
