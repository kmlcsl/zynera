@extends('layouts.admin')

@section('title', 'Laporan Sistem')
@section('page-title', 'Laporan Sistem')
@section('page-description', 'Monitoring performa dan status sistem platform')

@section('page-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.reports.system.performance') }}"
            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
            <i class="fas fa-tachometer-alt mr-2"></i>
            Performance Monitor
        </a>

        <a href="{{ route('admin.reports.system.error-logs') }}"
            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Error Logs
        </a>

        <form method="POST" action="{{ route('admin.reports.system.export') }}" class="inline">
            @csrf
            <input type="hidden" name="type" value="overview">
            <input type="hidden" name="format" value="csv">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700">
                <i class="fas fa-file-csv mr-2"></i>
                Export CSV
            </button>
        </form>
    </div>
@endsection

@section('content')
    <!-- System Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">System Status</p>
                    <p class="text-2xl font-bold text-green-900">Online</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-check-circle mr-1"></i>All systems operational
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-server text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Database Status</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $databaseStats['database_size_mb'] }} MB</p>
                    <p class="text-sm text-blue-600 mt-1">
                        <i class="fas fa-database mr-1"></i>{{ $databaseStats['total_records'] }} records
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-database text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Cache Status</p>
                    <p class="text-2xl font-bold text-purple-900">{{ ucfirst($cacheStats['driver']) }}</p>
                    <p class="text-sm {{ $cacheStats['functional'] === 'Yes' ? 'text-green-600' : 'text-red-600' }} mt-1">
                        <i class="fas fa-{{ $cacheStats['functional'] === 'Yes' ? 'check' : 'times' }}-circle mr-1"></i>{{ $cacheStats['functional'] === 'Yes' ? 'Functional' : 'Error' }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-memory text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Storage</p>
                    <p class="text-2xl font-bold text-orange-900">{{ $storageStats['disk_used_percent'] ?? 'N/A' }}%</p>
                    <p class="text-sm text-orange-600 mt-1">
                        <i class="fas fa-hdd mr-1"></i>{{ $storageStats['disk_free_gb'] ?? 'N/A' }} GB free
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hdd text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Server Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Server Information</h3>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">PHP Version:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $systemInfo['php_version'] }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Laravel Version:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $systemInfo['laravel_version'] }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Operating System:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $systemInfo['operating_system'] }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Server Software:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $systemInfo['server_software'] }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Memory Limit:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $systemInfo['memory_limit'] }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Max Execution Time:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $systemInfo['max_execution_time'] }}s</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Environment:</span>
                    <span class="text-sm font-medium {{ $systemInfo['environment'] === 'production' ? 'text-green-600' : 'text-orange-600' }}">
                        {{ ucfirst($systemInfo['environment']) }}
                    </span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-sm text-gray-600">Debug Mode:</span>
                    <span class="text-sm font-medium {{ $systemInfo['debug_mode'] === 'Disabled' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $systemInfo['debug_mode'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Database Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Database Information</h3>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Database Name:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $databaseStats['database_name'] }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Database Size:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $databaseStats['database_size_mb'] }} MB</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Total Records:</span>
                    <span class="text-sm font-medium text-gray-900">{{ number_format($databaseStats['total_records']) }}</span>
                </div>

                @if(isset($databaseStats['tables']) && is_array($databaseStats['tables']))
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Table Statistics:</h4>
                        @foreach($databaseStats['tables'] as $tableName => $count)
                            <div class="flex justify-between py-1">
                                <span class="text-xs text-gray-600">{{ ucfirst($tableName) }}:</span>
                                <span class="text-xs font-medium text-gray-900">{{ number_format($count) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Storage Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Storage Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <i class="fas fa-folder text-2xl text-blue-600 mb-2"></i>
                <p class="text-sm text-gray-600">Storage Path</p>
                <p class="text-xs text-gray-900 mt-1 break-all">{{ $storageStats['storage_path'] ?? 'N/A' }}</p>
                <p class="text-sm font-bold {{ $storageStats['storage_writable'] === 'Yes' ? 'text-green-600' : 'text-red-600' }} mt-2">
                    {{ $storageStats['storage_writable'] ?? 'Unknown' }}
                </p>
            </div>

            <div class="text-center p-4 bg-green-50 rounded-lg">
                <i class="fas fa-globe text-2xl text-green-600 mb-2"></i>
                <p class="text-sm text-gray-600">Public Path</p>
                <p class="text-xs text-gray-900 mt-1 break-all">{{ $storageStats['public_path'] ?? 'N/A' }}</p>
                <p class="text-sm font-bold {{ $storageStats['public_writable'] === 'Yes' ? 'text-green-600' : 'text-red-600' }} mt-2">
                    {{ $storageStats['public_writable'] ?? 'Unknown' }}
                </p>
            </div>

            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <i class="fas fa-hdd text-2xl text-purple-600 mb-2"></i>
                <p class="text-sm text-gray-600">Disk Space</p>
                <p class="text-lg font-bold text-purple-900">{{ $storageStats['disk_total_gb'] ?? 'N/A' }} GB</p>
                <p class="text-sm text-gray-600">Total</p>
            </div>

            <div class="text-center p-4 bg-orange-50 rounded-lg">
                <i class="fas fa-chart-pie text-2xl text-orange-600 mb-2"></i>
                <p class="text-sm text-gray-600">Usage</p>
                <p class="text-lg font-bold text-orange-900">{{ $storageStats['disk_used_percent'] ?? 'N/A' }}%</p>
                <p class="text-sm text-gray-600">Used</p>
            </div>
        </div>

        @if(isset($storageStats['disk_used_percent']) && is_numeric($storageStats['disk_used_percent']))
            <div class="mt-4">
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-{{ $storageStats['disk_used_percent'] > 80 ? 'red' : ($storageStats['disk_used_percent'] > 60 ? 'yellow' : 'green') }}-500 h-3 rounded-full transition-all duration-300"
                         style="width: {{ $storageStats['disk_used_percent'] }}%"></div>
                </div>
                <div class="flex justify-between text-sm text-gray-600 mt-2">
                    <span>0%</span>
                    <span>{{ $storageStats['disk_free_gb'] ?? 'N/A' }} GB Free</span>
                    <span>100%</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Cache Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Cache Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <i class="fas fa-memory text-2xl text-purple-600 mb-2"></i>
                <p class="text-sm text-gray-600">Cache Driver</p>
                <p class="text-lg font-bold text-purple-900">{{ ucfirst($cacheStats['driver']) }}</p>
            </div>

            <div class="text-center p-4 bg-{{ $cacheStats['functional'] === 'Yes' ? 'green' : 'red' }}-50 rounded-lg">
                <i class="fas fa-{{ $cacheStats['functional'] === 'Yes' ? 'check' : 'times' }}-circle text-2xl text-{{ $cacheStats['functional'] === 'Yes' ? 'green' : 'red' }}-600 mb-2"></i>
                <p class="text-sm text-gray-600">Status</p>
                <p class="text-lg font-bold text-{{ $cacheStats['functional'] === 'Yes' ? 'green' : 'red' }}-900">{{ $cacheStats['functional'] === 'Yes' ? 'Functional' : 'Error' }}</p>
            </div>

            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <i class="fas fa-bolt text-2xl text-blue-600 mb-2"></i>
                <p class="text-sm text-gray-600">Cache Status</p>
                <p class="text-lg font-bold text-blue-900">{{ $cacheStats['status'] }}</p>
            </div>
        </div>

        @if(isset($cacheStats['error']))
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                    <p class="text-sm font-medium text-red-800">Cache Error:</p>
                </div>
                <p class="text-sm text-red-700 mt-1">{{ $cacheStats['error'] }}</p>
            </div>
        @endif
    </div>

    <!-- System Health Check -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">System Health Check</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $healthChecks = [
                    [
                        'name' => 'PHP Version',
                        'status' => version_compare($systemInfo['php_version'], '8.0.0', '>=') ? 'good' : 'warning',
                        'message' => $systemInfo['php_version'] . ' (Recommended: 8.0+)'
                    ],
                    [
                        'name' => 'Environment',
                        'status' => $systemInfo['environment'] === 'production' ? 'good' : 'info',
                        'message' => ucfirst($systemInfo['environment'])
                    ],
                    [
                        'name' => 'Debug Mode',
                        'status' => $systemInfo['debug_mode'] === 'Disabled' ? 'good' : 'warning',
                        'message' => $systemInfo['debug_mode']
                    ],
                    [
                        'name' => 'Storage Writable',
                        'status' => $storageStats['storage_writable'] === 'Yes' ? 'good' : 'error',
                        'message' => $storageStats['storage_writable'] ?? 'Unknown'
                    ]
                ];
            @endphp

            @foreach($healthChecks as $check)
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-{{ $check['status'] === 'good' ? 'check-circle text-green-500' : ($check['status'] === 'warning' ? 'exclamation-triangle text-yellow-500' : ($check['status'] === 'error' ? 'times-circle text-red-500' : 'info-circle text-blue-500')) }}"></i>
                        <h4 class="font-medium text-gray-900">{{ $check['name'] }}</h4>
                    </div>
                    <p class="text-sm text-gray-600">{{ $check['message'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
