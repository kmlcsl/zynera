{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User: ' . $user->name)
@section('page-description', 'Edit informasi user admin panel')

@section('page-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.users.show', $user) }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700">
            <i class="fas fa-eye mr-2"></i>
            Lihat Detail
        </a>
        <a href="{{ route('admin.users.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informasi Dasar</h3>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="user_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe User *</label>
                            <select name="user_type" id="user_type" required
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                <option value="">Pilih Tipe User</option>
                                <option value="admin"
                                    {{ old('user_type', $user->user_type) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="produsen"
                                    {{ old('user_type', $user->user_type) == 'produsen' ? 'selected' : '' }}>Produsen
                                </option>
                                <option value="kurir"
                                    {{ old('user_type', $user->user_type) == 'kurir' ? 'selected' : '' }}>Kurir</option>
                            </select>
                            @error('user_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-end">
                            <div class="flex items-center h-10">
                                <input type="checkbox" name="is_verified" id="is_verified" value="1"
                                    {{ old('is_verified', $user->is_verified) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <label for="is_verified" class="ml-2 text-sm font-medium text-gray-700">
                                    User Terverifikasi
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Current Status Display -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Status Saat Ini</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Tipe User:</span>
                                @php
                                    $userTypeConfig = [
                                        'admin' => ['bg-purple-100', 'text-purple-800', 'fas fa-user-shield', 'Admin'],
                                        'produsen' => ['bg-green-100', 'text-green-800', 'fas fa-store', 'Produsen'],
                                        'kurir' => ['bg-orange-100', 'text-orange-800', 'fas fa-truck', 'Kurir'],
                                    ];
                                    $config = $userTypeConfig[$user->user_type] ?? [
                                        'bg-gray-100',
                                        'text-gray-800',
                                        'fas fa-user',
                                        'Unknown',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $config[0] }} {{ $config[1] }}">
                                    <i class="{{ $config[2] }} mr-1"></i>
                                    {{ $config[3] }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Status Verifikasi:</span>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->is_verified ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i
                                        class="fas {{ $user->is_verified ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                    {{ $user->is_verified ? 'Verified' : 'Unverified' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Member Since:</span>
                                <span class="text-gray-900">{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location & Security -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Lokasi & Keamanan</h3>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="village"
                                class="block text-sm font-medium text-gray-700 mb-1">Desa/Kelurahan</label>
                            <input type="text" name="village" id="village"
                                value="{{ old('village', $user->village) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            @error('village')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="district" class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                            <input type="text" name="district" id="district"
                                value="{{ old('district', $user->district) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            @error('district')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="font-medium text-yellow-800 mb-2">
                            <i class="fas fa-lock mr-2"></i>Update Password
                        </h4>
                        <p class="text-sm text-yellow-700 mb-3">Kosongkan jika tidak ingin mengubah password</p>

                        <div class="space-y-3">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password
                                    Baru</label>
                                <input type="password" name="password" id="password"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    @if ($user->id !== auth()->id())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <h4 class="font-medium text-red-800 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone
                            </h4>
                            <p class="text-sm text-red-700 mb-3">Aksi berikut tidak dapat dibatalkan</p>

                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                onsubmit="return confirm('Yakin ingin menghapus user ini? Aksi ini tidak dapat dibatalkan!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">
                                    <i class="fas fa-trash mr-2"></i>Hapus User
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('admin.users.show', $user) }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-save mr-2"></i>Update User
                </button>
            </div>
        </form>
    </div>
@endsection
