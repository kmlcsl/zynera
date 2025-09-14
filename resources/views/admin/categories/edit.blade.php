@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')
@section('page-description', 'Edit informasi kategori')

@section('page-actions')
    <a href="{{ route('admin.categories.index') }}"
        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informasi Dasar</h3>

                    <!-- Category Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('name') @enderror"
                            placeholder="Masukkan nama kategori...">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('slug') @enderror"
                            placeholder="kategori-url-friendly">
                        <p class="mt-1 text-xs text-gray-500">URL: <span id="preview-url">{{ url('/categories/') }}/</span>
                        </p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('description') @enderror"
                            placeholder="Deskripsi kategori (opsional)...">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                                Kategori Aktif
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Tandai untuk menampilkan kategori di frontend</p>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Image & Statistics -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Gambar & Statistik</h3>

                    <!-- Upload New Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar
                            Baru</label>
                        <input type="file" name="image" id="image" accept="image/*"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 @error('image') @enderror">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB. (Kosongkan jika tidak
                            ingin mengubah gambar)</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remove Current Image Option -->
                    @if ($category->image)
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" name="remove_image" id="remove_image" value="1"
                                    class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <label for="remove_image" class="ml-2 text-sm font-medium text-gray-700">
                                    Hapus Gambar Saat Ini
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Centang untuk menghapus gambar yang ada</p>
                        </div>
                    @endif

                    <!-- Category Statistics -->
                    @php
                        $totalProducts = $category->products()->count();
                        $activeProducts = $category->products()->where('is_active', true)->count();
                        $inactiveProducts = $totalProducts - $activeProducts;
                    @endphp

                    <div class="grid grid-cols-1 gap-3">
                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-blue-700">Total Produk</span>
                                <span class="text-lg font-bold text-blue-800">{{ $totalProducts }}</span>
                            </div>
                        </div>

                        <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-green-700">Produk Aktif</span>
                                <span class="text-lg font-bold text-green-800">{{ $activeProducts }}</span>
                            </div>
                        </div>

                        <div class="p-3 bg-red-50 rounded-lg border border-red-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-red-700">Produk Nonaktif</span>
                                <span class="text-lg font-bold text-red-800">{{ $inactiveProducts }}</span>
                            </div>
                        </div>
                    </div>

                    @if ($totalProducts > 0)
                        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-yellow-600 mr-2 mt-0.5"></i>
                                <div class="text-sm text-yellow-800">
                                    <strong>Perhatian:</strong> Kategori ini memiliki {{ $totalProducts }} produk.
                                    Mengubah status akan mempengaruhi visibilitas produk di frontend.
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="space-y-1 text-xs text-gray-600">
                            <div><strong>Dibuat:</strong> {{ $category->created_at->format('d M Y H:i') }}</div>
                            <div><strong>Diperbarui:</strong> {{ $category->updated_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Image Display -->
            @if ($category->image)
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Gambar Saat Ini</h3>
                    <div class="grid grid-cols-4 gap-4">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                class="w-full h-24 object-cover rounded-lg border border-gray-200">
                            <div
                                class="absolute top-1 right-1 bg-black bg-opacity-50 text-white text-xs px-1 py-0.5 rounded">
                                Current
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- New Image Preview -->
            <div id="image-preview" class="border-t pt-6 hidden">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Preview Gambar Baru</h3>
                <div class="grid grid-cols-4 gap-4">
                    <div class="relative">
                        <img id="preview-img" src="" alt="Preview"
                            class="w-full h-24 object-cover rounded-lg border border-gray-200">
                        <div class="absolute top-1 right-1 bg-green-500 text-white text-xs px-1 py-0.5 rounded">
                            New
                        </div>
                        <button type="button" onclick="removeNewImage()"
                            class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600">
                            ×
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('admin.categories.index') }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Update Kategori
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
            const originalSlug = '{{ $category->slug }}';

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
            const baseUrl = '{{ url('/categories') }}/';
            document.getElementById('preview-url').textContent = baseUrl + (slug || 'kategori-url');
        }

        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB = 2 * 1024 * 1024 bytes)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    this.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Tipe file tidak diizinkan. Gunakan JPG, PNG, atau GIF.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        function removeNewImage() {
            document.getElementById('image').value = '';
            document.getElementById('image-preview').classList.add('hidden');
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const slug = document.getElementById('slug').value.trim();

            if (!name) {
                e.preventDefault();
                alert('Nama kategori harus diisi.');
                document.getElementById('name').focus();
                return;
            }

            if (!slug) {
                e.preventDefault();
                alert('Slug kategori harus diisi.');
                document.getElementById('slug').focus();
                return;
            }

            if (name.length < 2) {
                e.preventDefault();
                alert('Nama kategori minimal 2 karakter.');
                document.getElementById('name').focus();
                return;
            }
        });

        // Initialize preview URL on page load
        updatePreviewUrl();
    </script>
@endsection
