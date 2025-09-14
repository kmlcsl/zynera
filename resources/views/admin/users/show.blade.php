@extends('layouts.admin')

@section('title', 'Detail User')
@section('page-title', 'Detail User: ' . $user->name)
@section('page-description', 'Informasi lengkap user admin panel')

@section('page-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.users.edit', $user) }}"
            class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-yellow-700">
            <i class="fas fa-edit mr-2"></i>
            Edit User
        </a>
        <a href="{{ route('admin.users.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=8FBC8F&color=fff&size=150' }}"
                        alt="{{ $user->name }}" class="w-24 h-24 rounded-full mx-auto object-cover mb-4">

                    <h3 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-gray-500">{{ $user->email }}</p>

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

                    <div class="mt-4">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config[0] }} {{ $config[1] }}">
                            <i class="{{ $config[2] }} mr-2"></i>
                            {{ $config[3] }}
                        </span>
                    </div>

                    <div class="mt-4 space-y-2">
                        <div class="flex items-center justify-center gap-2">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->is_verified ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas {{ $user->is_verified ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                {{ $user->is_verified ? 'Verified' : 'Unverified' }}
                            </span>
                        </div>

                        @if ($user->email_verified_at)
                            <div class="text-xs text-green-600">
                                <i class="fas fa-envelope-check mr-1"></i>Email Verified
                            </div>
                        @else
                            <div class="text-xs text-red-600">
                                <i class="fas fa-envelope mr-1"></i>Email Not Verified
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                <h4 class="font-semibold mb-4">Quick Actions</h4>
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.users.toggle-verification', $user) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm rounded-lg border border-gray-200 hover:bg-gray-50">
                            <i
                                class="fas {{ $user->is_verified ? 'fa-times-circle' : 'fa-check-circle' }} mr-2 text-gray-400"></i>
                            {{ $user->is_verified ? 'Unverify User' : 'Verify User' }}
                        </button>
                    </form>

                    @if ($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                            onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 rounded-lg border border-red-200 hover:bg-red-50">
                                <i class="fas fa-trash mr-2"></i>
                                Delete User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold mb-4">Personal Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Full Name</label>
                        <p class="text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email Address</label>
                        <p class="text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Phone Number</label>
                        <p class="text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">User Type</label>
                        <p class="text-gray-900">{{ ucfirst($user->user_type) }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">Address</label>
                        <p class="text-gray-900">{{ $user->address ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Village</label>
                        <p class="text-gray-900">{{ $user->village ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">District</label>
                        <p class="text-gray-900">{{ $user->district ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Member Since</label>
                        <p class="text-gray-900">{{ $user->created_at->format('d F Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Last Updated</label>
                        <p class="text-gray-900">{{ $user->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Permissions & Access -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold mb-4">Permissions & Access Level</h4>

                @switch($user->user_type)
                    @case('admin')
                        <div class="border border-purple-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="fas fa-user-shield text-purple-600"></i>
                                <h5 class="font-medium text-purple-800">Administrator</h5>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Full platform access
                                </li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Manage all users</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Manage all products &
                                    orders</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>System configuration
                                </li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Analytics & reports
                                </li>
                            </ul>
                        </div>
                    @break

                    @case('produsen')
                        <div class="border border-green-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="fas fa-store text-green-600"></i>
                                <h5 class="font-medium text-green-800">Produsen</h5>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Manage own products
                                </li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>View orders for their
                                    products</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Update product status
                                </li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Manage reviews</li>
                            </ul>
                        </div>
                    @break

                    @case('kurir')
                        <div class="border border-orange-200 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="fas fa-truck text-orange-600"></i>
                                <h5 class="font-medium text-orange-800">Kurir</h5>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>View assigned
                                    deliveries</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Update delivery
                                    status</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Upload delivery
                                    proof</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Track delivery
                                    history</li>
                            </ul>
                        </div>
                    @break
                @endswitch
            </div>

            <!-- Activity Statistics -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold mb-4">Activity Statistics</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @if ($user->user_type === 'produsen')
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $user->products->count() ?? 0 }}</div>
                            <div class="text-sm text-gray-600">Products</div>
                        </div>
                    @endif

                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $user->orders->count() ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Orders</div>
                    </div>

                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">{{ $user->reviews->count() ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Reviews</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
