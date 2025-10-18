@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('page-description', 'Kelola pengguna dan permission akses platform Zynera')

@section('page-actions')
    <a href="{{ route('admin.users.create') }}"
        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
        <i class="fas fa-plus mr-2"></i>
        Tambah User
    </a>
@endsection

@section('content')
    <!-- User Type Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Admin</p>
                    <p class="text-xl font-bold text-purple-600">{{ $stats['admin'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-shield text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Produsen</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['produsen'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-store text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Kurir</p>
                    <p class="text-xl font-bold text-orange-600">{{ $stats['kurir'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-truck text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari User</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Nama atau email..."
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
            </div>

            <div>
                <label for="user_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe User</label>
                <select name="user_type" id="user_type"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="">Semua Tipe</option>
                    <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="produsen" {{ request('user_type') == 'produsen' ? 'selected' : '' }}>Produsen</option>
                    <option value="kurir" {{ request('user_type') == 'kurir' ? 'selected' : '' }}>Kurir</option>
                </select>
            </div>

            <div>
                <label for="verification" class="block text-sm font-medium text-gray-700 mb-1">Status Verifikasi</label>
                <select name="verification" id="verification"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="">Semua Status</option>
                    <option value="verified" {{ request('verification') == 'verified' ? 'selected' : '' }}>Terverifikasi
                    </option>
                    <option value="unverified" {{ request('verification') == 'unverified' ? 'selected' : '' }}>Belum
                        Terverifikasi</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">User</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Kontak</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Lokasi</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Tipe & Permission</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Status</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Bergabung</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=8FBC8F&color=fff' }}"
                                        alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-gray-500 text-xs">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-gray-900">{{ $user->email }}</div>
                                <div class="text-gray-500 text-xs">{{ $user->phone ?? 'Tidak ada' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-gray-900">{{ $user->village ?? 'N/A' }}</div>
                                <div class="text-gray-500 text-xs">{{ $user->district ?? 'N/A' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $userTypeConfig = [
                                        'admin' => ['bg-purple-100', 'text-purple-800', 'fas fa-user-shield', 'Admin'],
                                        'produsen' => ['bg-green-100', 'text-green-800', 'fas fa-store', 'Produsen'],
                                        // 'konsumen' => [
                                        //     'bg-blue-100',
                                        //     'text-blue-800',
                                        //     'fas fa-shopping-cart',
                                        //     'Konsumen',
                                        // ],
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
                                <div class="text-xs text-gray-500 mt-1">
                                    @switch($user->user_type)
                                        @case('admin')
                                            Full Access • Manage All
                                        @break

                                        @case('produsen')
                                            Manage Products • Orders
                                        @break

                                        @case('konsumen')
                                            Browse • Purchase
                                        @break

                                        @case('kurir')
                                            Manage Deliveries
                                        @break
                                    @endswitch
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="space-y-1">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->is_verified ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i
                                            class="fas {{ $user->is_verified ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $user->is_verified ? 'Verified' : 'Unverified' }}
                                    </span>
                                    @if ($user->email_verified_at)
                                        <div class="text-xs text-green-600">
                                            <i class="fas fa-envelope-check mr-1"></i>Email OK
                                        </div>
                                    @else
                                        <div class="text-xs text-red-600">
                                            <i class="fas fa-envelope mr-1"></i>Email Pending
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-gray-900">{{ $user->created_at->format('d M Y') }}</div>
                                <div class="text-gray-500 text-xs">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="text-blue-600 hover:text-blue-800 p-1" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="text-yellow-600 hover:text-yellow-800 p-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-500">
                                    <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                                    <div class="text-lg font-medium mb-2">Belum ada user</div>
                                    <div class="text-sm">Tambahkan user pertama untuk memulai.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- Permission Access Info -->
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold mb-4">Permission & Access Level</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="border border-purple-200 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-user-shield text-purple-600"></i>
                        <h4 class="font-medium text-purple-800">Admin</h4>
                    </div>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Full platform access</li>
                        <li>• Manage all users</li>
                        <li>• Manage all products & orders</li>
                        <li>• System configuration</li>
                    </ul>
                </div>

                <div class="border border-green-200 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-store text-green-600"></i>
                        <h4 class="font-medium text-green-800">Produsen</h4>
                    </div>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Manage own products</li>
                        <li>• View orders for their products</li>
                        <li>• Update product status</li>
                        <li>• Manage reviews</li>
                    </ul>
                </div>

                <div class="border border-orange-200 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-truck text-orange-600"></i>
                        <h4 class="font-medium text-orange-800">Kurir</h4>
                    </div>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• View assigned deliveries</li>
                        <li>• Update delivery status</li>
                        <li>• Upload delivery proof</li>
                        <li>• Track delivery history</li>
                    </ul>
                </div>

                <div class="border border-teal-200 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-user-md text-teal-600"></i>
                        <h4 class="font-medium text-teal-800">Ahli Gizi</h4>
                    </div>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Manage nutrition articles</li>
                        <li>• Create healthy recipes</li>
                        <li>• Product nutrition labeling</li>
                        <li>• Health consultation</li>
                    </ul>
                </div>

                {{-- <div class="border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-shopping-cart text-blue-600"></i>
                        <h4 class="font-medium text-blue-800">Konsumen</h4>
                    </div>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Browse products</li>
                        <li>• Place orders</li>
                        <li>• Write reviews</li>
                        <li>• Track order status</li>
                    </ul>
                </div> --}}
            </div>
        </div>
    @endsection

