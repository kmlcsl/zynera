@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('profile.show') }}" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-user mr-2"></i>Profil
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-3"></i>
                            <span class="text-gray-900 font-medium">Edit Profil</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 mt-4">Edit Profil</h1>
            <p class="text-gray-600 mt-2">Perbarui informasi profil dan pengaturan akun Anda</p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <i class="fas fa-check-circle text-green-600 mt-0.5 mr-3"></i>
                    <div>
                        <h4 class="text-green-800 font-medium">Berhasil!</h4>
                        <p class="text-green-700 text-sm mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan Akun</h3>
                        <nav class="space-y-2" x-data="{ activeTab: 'profile' }">
                            <button @click="activeTab = 'profile'"
                                :class="activeTab === 'profile' ? 'bg-gampong-primary text-white' :
                                    'text-gray-700 hover:bg-gray-100'"
                                class="w-full flex items-center px-3 py-2 rounded-lg transition-colors text-left">
                                <i class="fas fa-user mr-3"></i>Informasi Pribadi
                            </button>
                            <button @click="activeTab = 'avatar'"
                                :class="activeTab === 'avatar' ? 'bg-gampong-primary text-white' :
                                    'text-gray-700 hover:bg-gray-100'"
                                class="w-full flex items-center px-3 py-2 rounded-lg transition-colors text-left">
                                <i class="fas fa-camera mr-3"></i>Foto Profil
                            </button>
                            <button @click="activeTab = 'password'"
                                :class="activeTab === 'password' ? 'bg-gampong-primary text-white' :
                                    'text-gray-700 hover:bg-gray-100'"
                                class="w-full flex items-center px-3 py-2 rounded-lg transition-colors text-left">
                                <i class="fas fa-lock mr-3"></i>Ubah Password
                            </button>
                            <button @click="activeTab = 'notifications'"
                                :class="activeTab === 'notifications' ? 'bg-gampong-primary text-white' :
                                    'text-gray-700 hover:bg-gray-100'"
                                class="w-full flex items-center px-3 py-2 rounded-lg transition-colors text-left">
                                <i class="fas fa-bell mr-3"></i>Notifikasi
                            </button>
                            <button @click="activeTab = 'danger'"
                                :class="activeTab === 'danger' ? 'bg-red-600 text-white' : 'text-red-600 hover:bg-red-50'"
                                class="w-full flex items-center px-3 py-2 rounded-lg transition-colors text-left">
                                <i class="fas fa-trash mr-3"></i>Hapus Akun
                            </button>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2" x-data="{ activeTab: 'profile' }">

                <!-- Profile Information -->
                <div x-show="activeTab === 'profile'" x-transition class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Informasi Pribadi</h2>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gampong-primary focus:border-transparent transition-colors">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gampong-primary focus:border-transparent transition-colors">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if ($user->email_verified_at)
                                    <p class="mt-1 text-sm text-green-600 flex items-center">
                                        <i class="fas fa-check-circle mr-1"></i>Email sudah terverifikasi
                                    </p>
                                @else
                                    <p class="mt-1 text-sm text-yellow-600 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Email belum terverifikasi
                                    </p>
                                @endif
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor WhatsApp
                                </label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gampong-primary focus:border-transparent transition-colors"
                                    placeholder="08123456789">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- User Type (Read Only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jenis Akun
                                </label>
                                <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg">
                                    <span
                                        class="text-gray-900 capitalize">{{ str_replace('_', ' ', $user->user_type) }}</span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Jenis akun tidak dapat diubah</p>
                            </div>

                            <!-- Village -->
                            <div>
                                <label for="village" class="block text-sm font-medium text-gray-700 mb-2">
                                    Desa/Gampong
                                </label>
                                <input type="text" name="village" id="village"
                                    value="{{ old('village', $user->village) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gampong-primary focus:border-transparent transition-colors"
                                    placeholder="Contoh: Gampong Pante Ceureumen">
                                @error('village')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- District -->
                            <div>
                                <label for="district" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kecamatan
                                </label>
                                <input type="text" name="district" id="district"
                                    value="{{ old('district', $user->district) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gampong-primary focus:border-transparent transition-colors"
                                    placeholder="Contoh: Meureubo">
                                @error('district')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat Lengkap
                                </label>
                                <textarea name="address" id="address" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gampong-primary focus:border-transparent transition-colors resize-none"
                                    placeholder="Masukkan alamat lengkap untuk pengiriman">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Alamat ini akan digunakan sebagai alamat pengiriman
                                    default</p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Avatar Upload -->
                <div x-show="activeTab === 'avatar'" x-transition class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Foto Profil</h2>

                    <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                        <!-- Current Avatar -->
                        <div class="flex-shrink-0">
                            <div class="relative">
                                @if ($user->avatar)
                                    <img class="w-32 h-32 rounded-full object-cover border-4 border-gampong-secondary"
                                        src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                                        id="avatar-preview">
                                @else
                                    <img class="w-32 h-32 rounded-full object-cover border-4 border-gampong-secondary"
                                        src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=8FBC8F&color=fff&size=128"
                                        alt="{{ $user->name }}" id="avatar-preview">
                                @endif
                            </div>
                        </div>

                        <!-- Upload Form -->
                        <div class="flex-1">
                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('patch')

                                <div class="mb-4">
                                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                                        Pilih Foto Baru
                                    </label>
                                    <input type="file" name="avatar" id="avatar" accept="image/*"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gampong-primary focus:border-transparent transition-colors"
                                        onchange="previewAvatar(this)">
                                    @error('avatar')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">
                                        Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB.
                                    </p>
                                </div>

                                <div class="flex space-x-3">
                                    <button type="submit" class="btn-primary">
                                        <i class="fas fa-upload mr-2"></i>Upload Foto
                                    </button>

                                    @if ($user->avatar)
                                        <form method="POST" action="{{ route('profile.remove-avatar') }}"
                                            class="inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit"
                                                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors"
                                                onclick="return confirm('Yakin ingin menghapus foto profil?')">
                                                <i class="fas fa-trash mr-2"></i>Hapus Foto
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Change Password -->
                <div x-show="activeTab === 'password'" x-transition class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Ubah Password</h2>

                    <form method="POST" action="{{ route('profile.update-password') }}">
                        @csrf
                        @method('patch')

                        <div class="space-y-6">
                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Saat Ini <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="current_password" id="current_password" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gampong-primary focus:border-transparent transition-colors">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Baru <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" id="password" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gampong-primary focus:border-transparent transition-colors">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter dengan kombinasi huruf dan angka
                                </p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gampong-primary focus:border-transparent transition-colors">
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-key mr-2"></i>Ubah Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Notification Settings -->
                <div x-show="activeTab === 'notifications'" x-transition class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Pengaturan Notifikasi</h2>

                    <form method="POST" action="{{ route('profile.update-notifications') }}">
                        @csrf

                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Email Notifikasi</h3>
                                    <p class="text-sm text-gray-600">Terima notifikasi umum melalui email</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_notifications" value="1"
                                        class="sr-only peer"
                                        {{ old('email_notifications', $user->notification_preferences['email_notifications'] ?? true) ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Update Pesanan</h3>
                                    <p class="text-sm text-gray-600">Notifikasi status pesanan dan pengiriman</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="order_updates" value="1" class="sr-only peer"
                                        {{ old('order_updates', $user->notification_preferences['order_updates'] ?? true) ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Promosi & Penawaran</h3>
                                    <p class="text-sm text-gray-600">Notifikasi produk baru dan penawaran khusus</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="promotion_notifications" value="1"
                                        class="sr-only peer"
                                        {{ old('promotion_notifications', $user->notification_preferences['promotion_notifications'] ?? false) ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Newsletter</h3>
                                    <p class="text-sm text-gray-600">Artikel gizi dan tips sehat mingguan</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="newsletter" value="1" class="sr-only peer"
                                        {{ old('newsletter', $user->notification_preferences['newsletter'] ?? false) ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-bell mr-2"></i>Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Danger Zone -->
                <div x-show="activeTab === 'danger'" x-transition
                    class="bg-white rounded-lg shadow-md p-6 border border-red-200">
                    <h2 class="text-xl font-semibold text-red-600 mb-6">Zona Berbahaya</h2>

                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-red-600 mt-0.5 mr-3"></i>
                            <div>
                                <h4 class="text-red-800 font-medium">Peringatan!</h4>
                                <p class="text-red-700 text-sm mt-1">
                                    Tindakan ini akan menghapus akun Anda secara permanen dan tidak dapat dibatalkan.
                                    Semua data termasuk riwayat pesanan akan hilang.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.destroy') }}" x-data="{ confirmDelete: false }">
                        @csrf
                        @method('delete')

                        <div class="space-y-4">
                            <div>
                                <label for="password_delete" class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi dengan Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" id="password_delete" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-colors">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="confirm_delete" x-model="confirmDelete"
                                    class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <label for="confirm_delete" class="ml-2 block text-sm text-gray-700">
                                    Saya memahami bahwa tindakan ini tidak dapat dibatalkan
                                </label>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" :disabled="!confirmDelete"
                                :class="confirmDelete ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-400 cursor-not-allowed'"
                                class="px-6 py-2 text-white rounded-lg transition-colors"
                                onclick="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan!')">
                                <i class="fas fa-trash mr-2"></i>Hapus Akun Permanen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
