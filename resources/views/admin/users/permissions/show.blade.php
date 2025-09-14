@extends('layouts.admin')

@section('title', 'Mengelola Izin')
@section('page-title', 'Mengelola Izin: ' . $user->name)
@section('page-description', 'Atur akses menu dan permission untuk ' . $user->name . ' (' . ucfirst($user->user_type) .
    ')')

@section('page-actions')
    <div class="flex items-center gap-3">
        <button onclick="resetToDefault()"
            class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-orange-700">
            <i class="fas fa-undo mr-2"></i>
            Reset ke Bawaan
        </button>
        <a href="{{ route('admin.users.permissions.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar
        </a>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.users.permissions.update', $user) }}" id="permissionForm">
        @csrf

        <!-- User Info Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center gap-4">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=8FBC8F&color=fff' }}"
                    alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    @php
                        $userTypeConfig = [
                            'admin' => ['bg-purple-100', 'text-purple-800', 'fas fa-user-shield', 'Administrator'],
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
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config[0] }} {{ $config[1] }} mt-2">
                        <i class="{{ $config[2] }} mr-2"></i>
                        {{ $config[3] }}
                    </span>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Total Izin</div>
                    <div class="text-2xl font-bold text-gray-900" id="totalPermissions">0</div>
                </div>
            </div>
        </div>

        <!-- Permission Controls -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <h3 class="text-lg font-semibold text-gray-900">Kontrol Izin</h3>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="toggleAll(true)"
                            class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                            <i class="fas fa-check mr-1"></i>Izinkan Semua
                        </button>
                        <button type="button" onclick="toggleAll(false)"
                            class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                            <i class="fas fa-times mr-1"></i>Tolak Semua
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-sm">
                        <span class="text-green-600 font-medium" id="allowedCount">0</span> Diizinkan,
                        <span class="text-red-600 font-medium" id="deniedCount">0</span> Ditolak
                    </div>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Perizinan
                    </button>
                </div>
            </div>
        </div>

        <!-- Permissions by Menu -->
        @foreach ($userRoutes as $menuIndex => $menu)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <!-- Menu Header -->
                <div class="border-b border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="{{ $menu['icon'] ?? 'fas fa-folder' }} text-lg text-gray-600"></i>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $menu['title'] }}</h4>
                            <span class="text-sm text-gray-500">({{ count($menu['item']) }} permissions)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="toggleMenu('menu-{{ $menuIndex }}', true)"
                                class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded hover:bg-green-200">
                                Izin Semua
                            </button>
                            <button type="button" onclick="toggleMenu('menu-{{ $menuIndex }}', false)"
                                class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded hover:bg-red-200">
                                Tolak Semua
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Menu Items -->
                <div class="p-4" id="menu-{{ $menuIndex }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($menu['item'] as $route)
                            @if (!empty($route['name']) && !in_array($route['type'], ['system', 'ajax', 'api']))
                                @php
                                    $routeName = $route['name'];
                                    $isAllowed = $userPermissions[$routeName] ?? true;
                                    $typeColors = [
                                        'index' => 'border-blue-200 bg-blue-50',
                                        'create' => 'border-green-200 bg-green-50',
                                        'edit' => 'border-yellow-200 bg-yellow-50',
                                        'update' => 'border-orange-200 bg-orange-50',
                                        'delete' => 'border-red-200 bg-red-50',
                                        'show' => 'border-purple-200 bg-purple-50',
                                        'permission' => 'border-indigo-200 bg-indigo-50',
                                    ];
                                    $cardColor = $typeColors[$route['type']] ?? 'border-gray-200 bg-gray-50';
                                @endphp

                                <div class="border rounded-lg p-3 {{ $cardColor }} permission-card"
                                    data-route="{{ $routeName }}">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0">
                                            <h5 class="font-medium text-sm text-gray-900 truncate">{{ $route['title'] }}
                                            </h5>
                                            <p class="text-xs text-gray-500 mt-1">{{ $route['name'] }}</p>
                                            <span
                                                class="inline-block px-2 py-1 text-xs rounded mt-2
                                        {{ $route['type'] === 'index' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $route['type'] === 'create' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $route['type'] === 'edit' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $route['type'] === 'update' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $route['type'] === 'delete' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $route['type'] === 'show' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $route['type'] === 'permission' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                        {{ !in_array($route['type'], ['index', 'create', 'edit', 'update', 'delete', 'show', 'permission']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                                {{ ucfirst($route['type']) }}
                                            </span>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <!-- Allow Option -->
                                            <label class="flex items-center cursor-pointer">
                                                <input type="radio" name="permissions[{{ $routeName }}]"
                                                    value="1" {{ $isAllowed ? 'checked' : '' }}
                                                    class="permission-radio allow-radio" onchange="updateCounts()">
                                                <span class="ml-1 text-xs text-green-700">Izinkan</span>
                                            </label>

                                            <!-- Deny Option -->
                                            <label class="flex items-center cursor-pointer">
                                                <input type="radio" name="permissions[{{ $routeName }}]"
                                                    value="0" {{ !$isAllowed ? 'checked' : '' }}
                                                    class="permission-radio deny-radio" onchange="updateCounts()">
                                                <span class="ml-1 text-xs text-red-700">Tolak</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Submit Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Pastikan untuk menyimpan perubahan Anda sebelum meninggalkan halaman ini.
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="confirmReset()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Reset Semua
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Perizinan
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Update permission counts
        function updateCounts() {
            const allowedInputs = document.querySelectorAll('.allow-radio:checked');
            const deniedInputs = document.querySelectorAll('.deny-radio:checked');
            const totalInputs = document.querySelectorAll('.permission-radio');

            document.getElementById('allowedCount').textContent = allowedInputs.length;
            document.getElementById('deniedCount').textContent = deniedInputs.length;
            document.getElementById('totalPermissions').textContent = totalInputs.length /
                2; // Divided by 2 because each permission has 2 radio buttons
        }

        // Toggle all permissions
        function toggleAll(allow) {
            const radios = document.querySelectorAll(allow ? '.allow-radio' : '.deny-radio');
            radios.forEach(radio => {
                radio.checked = true;
            });
            updateCounts();
        }

        // Toggle permissions for specific menu
        function toggleMenu(menuId, allow) {
            const menuElement = document.getElementById(menuId);
            const radios = menuElement.querySelectorAll(allow ? '.allow-radio' : '.deny-radio');
            radios.forEach(radio => {
                radio.checked = true;
            });
            updateCounts();
        }

        // Reset to default permissions
        function resetToDefault() {
            if (confirm('Reset all permissions to default settings based on user type?')) {
                // Reset to default based on user type
                const userType = '{{ $user->user_type }}';

                // Get all permission cards
                const cards = document.querySelectorAll('.permission-card');

                cards.forEach(card => {
                    const routeName = card.dataset.route;
                    const allowRadio = card.querySelector('.allow-radio');
                    const denyRadio = card.querySelector('.deny-radio');

                    // Default logic based on user type and route
                    let shouldAllow = true;

                    if (userType === 'admin') {
                        shouldAllow = true; // Admin gets everything
                    } else if (userType === 'produsen') {
                        shouldAllow = routeName.includes('producer') || routeName.includes('product');
                    } else if (userType === 'kurir') {
                        shouldAllow = routeName.includes('courier') || routeName.includes('deliver');
                    }


                    if (shouldAllow) {
                        allowRadio.checked = true;
                    } else {
                        denyRadio.checked = true;
                    }
                });

                updateCounts();
            }
        }

        // Confirm reset all
        function confirmReset() {
            if (confirm('Are you sure you want to reset all permissions? This will set all permissions to denied.')) {
                toggleAll(false);
            }
        }

        // Form submission handler
        document.getElementById('permissionForm').addEventListener('submit', function(e) {
            const allowedCount = document.querySelectorAll('.allow-radio:checked').length;

            if (allowedCount === 0) {
                if (!confirm(
                        'Warning: You are about to deny all permissions for this user. This will prevent them from accessing any features. Continue?'
                    )) {
                    e.preventDefault();
                    return false;
                }
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            submitBtn.disabled = true;
        });

        // Initialize counts on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCounts();
        });

        // Auto-save draft (optional)
        let autoSaveTimeout;
        document.querySelectorAll('.permission-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    // You can implement auto-save logic here
                    console.log('Auto-saving draft...');
                }, 2000);
            });
        });
    </script>

    <style>
        .permission-card {
            transition: all 0.3s ease;
        }

        .permission-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .permission-radio {
            width: 16px;
            height: 16px;
        }

        .permission-radio:checked {
            accent-color: #3B82F6;
        }

        .allow-radio:checked {
            accent-color: #10B981;
        }

        .deny-radio:checked {
            accent-color: #EF4444;
        }
    </style>
@endsection
