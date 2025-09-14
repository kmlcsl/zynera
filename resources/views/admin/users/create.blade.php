@extends('layouts.admin')

@section('title', 'Tambah User Admin Panel')
@section('page-title', 'Tambah User Admin Panel')
@section('page-description', 'Tambah user baru untuk admin panel')

@section('page-actions')
    <a href="{{ route('admin.users.index') }}"
        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Informasi Dasar</h3>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
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
                                <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="produsen" {{ old('user_type') == 'produsen' ? 'selected' : '' }}>Produsen
                                </option>
                                <option value="kurir" {{ old('user_type') == 'kurir' ? 'selected' : '' }}>Kurir</option>
                            </select>
                            @error('user_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-end">
                            <div class="flex items-center h-10">
                                <input type="checkbox" name="is_verified" id="is_verified" value="1"
                                    {{ old('is_verified') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <label for="is_verified" class="ml-2 text-sm font-medium text-gray-700">
                                    User Terverifikasi
                                </label>
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
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="village"
                                class="block text-sm font-medium text-gray-700 mb-1">Desa/Kelurahan</label>
                            <input type="text" name="village" id="village" value="{{ old('village') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            @error('village')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="district" class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                            <input type="text" name="district" id="district" value="{{ old('district') }}"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            @error('district')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                        <input type="password" name="password" id="password" required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi
                            Password *</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('admin.users.index') }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Tambah User
                </button>
            </div>
        </form>
    </div>
@endsection
