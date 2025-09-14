@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')
@section('page-description', 'Buat kategori produk baru')

@section('page-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.categories.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('name') border-red-300 @enderror"
                            placeholder="Masukkan nama kategori...">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                            Slug
                            <span class="text-gray-400 text-xs">(Opsional - akan dibuat otomatis)</span>
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                            class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('slug') border-red-300 @enderror"
                            placeholder="kategori-url-friendly">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">URL: <span id="preview-url">{{ url('/categories/') }}/</span>
                        </p>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Deskripsi
                    </label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full rounded-lg focus:border-green-500 focus:ring-green-500 @error('description') border-red-300 @enderror"
                        placeholder="Deskripsi kategori (opsional)...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Image Upload -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar Kategori</h3>

                <div class="space-y-4">
                    <!-- File Input -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                            Upload Gambar
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="image"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="mb-2 text-sm text-gray-500">
                                        <span class="font-semibold">Klik untuk upload</span> atau drag & drop
                                    </p>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF (MAX. 2MB)</p>
                                </div>
                                <input id="image" name="image" type="file" class="hidden" accept="image/*" />
                            </label>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Preview -->
                    <div id="image-preview" class="hidden">
                        <div class="relative inline-block">
                            <img id="preview-img" src="" alt="Preview"
                                class="h-32 w-32 object-cover rounded-lg border border-gray-200">
                            <button type="button" onclick="removeImage()"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Status Kategori</h3>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-900">
                        Aktifkan kategori ini
                    </label>
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    Kategori yang tidak aktif tidak akan ditampilkan di frontend
                </p>
            </div>

            <!-- Submit Buttons -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.categories.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Kategori
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Auto generate slug from name
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const slug = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/-+/g, '-') // Replace multiple hyphens with single
                .trim('-'); // Remove leading/trailing hyphens

            if (!document.getElementById('slug').value) {
                document.getElementById('slug').value = slug;
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
                    document.getElementById('upload-placeholder').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        function removeImage() {
            document.getElementById('image').value = '';
            document.getElementById('image-preview').classList.add('hidden');
            document.getElementById('upload-placeholder').classList.remove('hidden');
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();

            if (!name) {
                e.preventDefault();
                alert('Nama kategori harus diisi.');
                document.getElementById('name').focus();
                return;
            }

            if (name.length < 2) {
                e.preventDefault();
                alert('Nama kategori minimal 2 karakter.');
                document.getElementById('name').focus();
                return;
            }
        });

        // Initialize preview URL
        updatePreviewUrl();
    </script>
@endsection
