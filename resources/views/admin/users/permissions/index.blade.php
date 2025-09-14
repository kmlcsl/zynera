@extends('layouts.admin')

@section('title', 'Permission Menu Users')
@section('page-title', 'Permission Menu Users')
@section('page-description', 'Kelola akses menu dan permission untuk setiap user admin panel')

@section('page-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.users.index') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700">
            <i class="fas fa-users mr-2"></i>
            Setting Akun Users
        </a>
        <button onclick="refreshPermissions()"
            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700">
            <i class="fas fa-sync-alt mr-2"></i>
            Refresh Permissions
        </button>
    </div>
@endsection

@section('content')
    <!-- Permission Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Users</p>
                    <p class="text-xl font-bold text-blue-600">{{ $users->total() }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Full Access</p>
                    <p class="text-xl font-bold text-green-600">{{ $users->where('user_type', 'admin')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shield-alt text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Limited Access</p>
                    <p class="text-xl font-bold text-orange-600">
                        {{ $users->whereIn('user_type', ['produsen', 'kurir'])->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-key text-orange-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Custom Permissions</p>
                    <p class="text-xl font-bold text-purple-600">
                        {{ $users->filter(function ($user) {return $user->permissions->count() > 0;})->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cogs text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6 mb-6">
        <form method="GET" action="{{ route('admin.users.permissions.index') }}"
            class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <label for="permission_status" class="block text-sm font-medium text-gray-700 mb-1">Status
                    Permission</label>
                <select name="permission_status" id="permission_status"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="">Semua Status</option>
                    <option value="custom" {{ request('permission_status') == 'custom' ? 'selected' : '' }}>Custom
                        Permissions</option>
                    <option value="default" {{ request('permission_status') == 'default' ? 'selected' : '' }}>Default
                        Permissions</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.users.permissions.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Users Permission Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">User Info</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">User Type & Role</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Permission Status</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Last Modified</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Quick Actions</th>
                        <th class="text-left py-4 px-6 font-medium text-gray-600">Manage</th>
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
                                        <div class="text-gray-500 text-xs">{{ $user->email }}</div>
                                        <div class="text-gray-400 text-xs">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $userTypeConfig = [
                                        'admin' => [
                                            'bg-purple-100',
                                            'text-purple-800',
                                            'fas fa-user-shield',
                                            'Admin',
                                            'Full Access',
                                        ],
                                        'produsen' => [
                                            'bg-green-100',
                                            'text-green-800',
                                            'fas fa-store',
                                            'Produsen',
                                            'Product Management',
                                        ],
                                        'kurir' => [
                                            'bg-orange-100',
                                            'text-orange-800',
                                            'fas fa-truck',
                                            'Kurir',
                                            'Delivery Management',
                                        ],
                                    ];
                                    $config = $userTypeConfig[$user->user_type] ?? [
                                        'bg-gray-100',
                                        'text-gray-800',
                                        'fas fa-user',
                                        'Unknown',
                                        'No Access',
                                    ];
                                @endphp
                                <div class="space-y-1">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $config[0] }} {{ $config[1] }}">
                                        <i class="{{ $config[2] }} mr-1"></i>
                                        {{ $config[3] }}
                                    </span>
                                    <div class="text-xs text-gray-500">{{ $config[4] }}</div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if ($user->permissions->count() > 0)
                                    <div class="space-y-1">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-cog mr-1"></i>
                                            Custom Permissions
                                        </span>
                                        <div class="text-xs text-gray-500">
                                            {{ $user->permissions->where('is_allowed', true)->count() }} allowed,
                                            {{ $user->permissions->where('is_allowed', false)->count() }} denied
                                        </div>
                                    </div>
                                @else
                                    <div class="space-y-1">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-user-check mr-1"></i>
                                            Default Permissions
                                        </span>
                                        <div class="text-xs text-gray-500">Based on user type</div>
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if ($user->permissions->count() > 0)
                                    <div class="text-gray-900 text-sm">
                                        {{ $user->permissions->max('updated_at')->diffForHumans() }}</div>
                                    <div class="text-gray-500 text-xs">
                                        {{ $user->permissions->max('updated_at')->format('d M Y H:i') }}</div>
                                @else
                                    <div class="text-gray-500 text-sm">Never modified</div>
                                    <div class="text-gray-400 text-xs">Using defaults</div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-1">
                                    @if ($user->permissions->count() > 0)
                                        <button onclick="resetPermissions({{ $user->id }})"
                                            class="text-orange-600 hover:text-orange-800 p-1 rounded"
                                            title="Reset to Default">
                                            <i class="fas fa-undo text-xs"></i>
                                        </button>
                                    @endif

                                    <button onclick="copyPermissions({{ $user->id }})"
                                        class="text-blue-600 hover:text-blue-800 p-1 rounded" title="Copy Permissions">
                                        <i class="fas fa-copy text-xs"></i>
                                    </button>

                                    @if ($user->user_type !== 'admin')
                                        <button onclick="toggleAllPermissions({{ $user->id }})"
                                            class="text-purple-600 hover:text-purple-800 p-1 rounded" title="Toggle All">
                                            <i class="fas fa-toggle-on text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.permissions.show', $user) }}"
                                        class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-key mr-1"></i>
                                        Manage
                                    </a>

                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="text-blue-600 hover:text-blue-800 p-1" title="View User">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500">
                                <i class="fas fa-key text-4xl mb-4 text-gray-300"></i>
                                <div class="text-lg font-medium mb-2">No permission data found</div>
                                <div class="text-sm">Add users to start managing permissions.</div>
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

    <!-- Bulk Actions Modal -->
    <div id="bulkActionsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeBulkModal()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6" onclick="event.stopPropagation()">
                <h3 class="text-lg font-semibold mb-4">Bulk Permission Actions</h3>
                <div class="space-y-3">
                    <button onclick="bulkResetPermissions()"
                        class="w-full text-left px-4 py-2 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-undo mr-2 text-orange-600"></i>
                        Reset All to Default
                    </button>
                    <button onclick="bulkCopyFromAdmin()" class="w-full text-left px-4 py-2 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-copy mr-2 text-blue-600"></i>
                        Copy from Admin Template
                    </button>
                    <button onclick="bulkGeneratePermissions()"
                        class="w-full text-left px-4 py-2 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-sync-alt mr-2 text-green-600"></i>
                        Regenerate All Permissions
                    </button>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button onclick="closeBulkModal()"
                        class="px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Permission Overview Panel -->
    <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold mb-4">Permission Overview by User Type</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="border border-purple-200 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-3">
                    <i class="fas fa-user-shield text-purple-600"></i>
                    <h4 class="font-medium text-purple-800">Admin ({{ $users->where('user_type', 'admin')->count() }})
                    </h4>
                </div>
                <div class="text-sm text-gray-600 space-y-1">
                    <div class="flex justify-between">
                        <span>Full Access:</span>
                        <span class="text-green-600 font-medium">✓ All</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Restrictions:</span>
                        <span class="text-gray-400">None</span>
                    </div>
                </div>
            </div>

            <div class="border border-green-200 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-3">
                    <i class="fas fa-store text-green-600"></i>
                    <h4 class="font-medium text-green-800">Produsen
                        ({{ $users->where('user_type', 'produsen')->count() }})</h4>
                </div>
                <div class="text-sm text-gray-600 space-y-1">
                    <div class="flex justify-between">
                        <span>Products:</span>
                        <span class="text-green-600 font-medium">✓ Own Only</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Orders:</span>
                        <span class="text-green-600 font-medium">✓ Related</span>
                    </div>
                </div>
            </div>

            <div class="border border-orange-200 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-3">
                    <i class="fas fa-truck text-orange-600"></i>
                    <h4 class="font-medium text-orange-800">Kurir ({{ $users->where('user_type', 'kurir')->count() }})
                    </h4>
                </div>
                <div class="text-sm text-gray-600 space-y-1">
                    <div class="flex justify-between">
                        <span>Deliveries:</span>
                        <span class="text-green-600 font-medium">✓ Assigned</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Status Update:</span>
                        <span class="text-green-600 font-medium">✓ Yes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function refreshPermissions() {
            // Show loading
            const button = event.target.closest('button');
            const originalHtml = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Refreshing...';
            button.disabled = true;

            // Simulate refresh (replace with actual AJAX call)
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }

        function resetPermissions(userId) {
            if (confirm('Reset this user\'s permissions to default settings?')) {
                // Add AJAX call to reset permissions
                console.log('Resetting permissions for user:', userId);
            }
        }

        function copyPermissions(userId) {
            // Show copy modal or perform copy action
            console.log('Copying permissions for user:', userId);
        }

        function toggleAllPermissions(userId) {
            if (confirm('Toggle all permissions for this user?')) {
                // Add AJAX call to toggle permissions
                console.log('Toggling all permissions for user:', userId);
            }
        }

        function closeBulkModal() {
            document.getElementById('bulkActionsModal').classList.add('hidden');
        }

        function bulkResetPermissions() {
            if (confirm('Reset all users\' permissions to default? This cannot be undone.')) {
                // Add bulk reset logic
                console.log('Bulk reset permissions');
                closeBulkModal();
            }
        }

        function bulkCopyFromAdmin() {
            if (confirm('Copy admin permissions template to all users?')) {
                // Add bulk copy logic
                console.log('Bulk copy from admin');
                closeBulkModal();
            }
        }

        function bulkGeneratePermissions() {
            if (confirm('Regenerate all permissions based on user types?')) {
                // Add bulk generate logic
                console.log('Bulk generate permissions');
                closeBulkModal();
            }
        }
    </script>
@endsection
