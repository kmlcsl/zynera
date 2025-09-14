@extends('layouts.admin')

@section('title', 'Laporan Pengguna')
@section('page-title', 'Laporan Pengguna')
@section('page-description', 'Analisis data pengguna dan aktivitas platform')

@section('page-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.reports.users.activity') }}"
            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
            <i class="fas fa-chart-bar mr-2"></i>
            User Activity
        </a>

        <button onclick="getRegistrationStats()"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700">
            <i class="fas fa-chart-line mr-2"></i>
            Registration Stats
        </button>

        <form method="POST" action="{{ route('admin.reports.users.export') }}" class="inline">
            @csrf
            <input type="hidden" name="start_date" value="{{ $startDate }}">
            <input type="hidden" name="end_date" value="{{ $endDate }}">
            <input type="hidden" name="type" value="{{ $userType }}">
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
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6 mb-6">
        <form method="GET" action="{{ route('admin.reports.users.index') }}"
            class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
            </div>

            <div>
                <label for="user_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Pengguna</label>
                <select name="user_type" id="user_type"
                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="all" {{ $userType == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="customer" {{ $userType == 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="produsen" {{ $userType == 'produsen' ? 'selected' : '' }}>Produsen</option>
                    <option value="kurir" {{ $userType == 'kurir' ? 'selected' : '' }}>Kurir</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.reports.users.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $overview['total_users'] }}</p>
                    <p class="text-sm text-blue-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>{{ $overview['growth_rate'] }}% growth
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Verified Users</p>
                    <p class="text-2xl font-bold text-green-900">{{ $overview['verified_users'] }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-check-circle mr-1"></i>{{ $overview['verification_rate'] }}% verified
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Users</p>
                    <p class="text-2xl font-bold text-purple-900">{{ $overview['active_users'] }}</p>
                    <p class="text-sm text-purple-600 mt-1">
                        <i class="fas fa-chart-line mr-1"></i>With orders
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-clock text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">New Users</p>
                    <p class="text-2xl font-bold text-orange-900">{{ $overview['total_users'] - $overview['active_users'] }}</p>
                    <p class="text-sm text-orange-600 mt-1">
                        <i class="fas fa-user-plus mr-1"></i>This period
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-plus text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Growth Rate</p>
                    <p class="text-2xl font-bold {{ $overview['growth_rate'] >= 0 ? 'text-green-900' : 'text-red-900' }}">
                        {{ $overview['growth_rate'] > 0 ? '+' : '' }}{{ $overview['growth_rate'] }}%
                    </p>
                    <p class="text-sm {{ $overview['growth_rate'] >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                        <i class="fas fa-{{ $overview['growth_rate'] >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>vs previous period
                    </p>
                </div>
                <div class="w-12 h-12 {{ $overview['growth_rate'] >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line {{ $overview['growth_rate'] >= 0 ? 'text-green-600' : 'text-red-600' }} text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Users by Type -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Users by Type</h3>
            <div class="space-y-4">
                @forelse($usersByType as $type)
                    @php
                        $colors = [
                            'customer' => 'blue',
                            'produsen' => 'green',
                            'kurir' => 'purple',
                            'admin' => 'red'
                        ];
                        $color = $colors[$type->user_type] ?? 'gray';
                        $verificationRate = $type->count > 0 ? round(($type->verified_count / $type->count) * 100, 1) : 0;
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-{{ $color }}-50 rounded-lg border border-{{ $color }}-200">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-{{ $color }}-500 rounded-full"></div>
                            <div>
                                <p class="font-medium text-gray-900">{{ ucfirst($type->user_type) }}</p>
                                <p class="text-sm text-gray-500">{{ $type->verified_count }}/{{ $type->count }} verified</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-{{ $color }}-600">{{ $type->count }}</p>
                            <p class="text-sm text-{{ $color }}-600">{{ $verificationRate }}%</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-chart-pie text-4xl mb-2"></i>
                        <p>No user data available</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Top Customers -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Customers</h3>
            <div class="space-y-4">
                @forelse($topCustomers as $index => $customer)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 {{ $index === 0 ? 'bg-gold-100' : ($index === 1 ? 'bg-silver-100' : 'bg-bronze-100') }} rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium {{ $index === 0 ? 'text-yellow-600' : ($index === 1 ? 'text-gray-600' : 'text-orange-600') }}">{{ $index + 1 }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                                <p class="text-sm text-gray-500">{{ $customer->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">{{ $customer->total_orders }} orders</p>
                            <p class="text-sm text-green-600">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-crown text-4xl mb-2"></i>
                        <p>No customer data available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Registration Activity -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Registration Activity</h3>
        <div class="space-y-3">
            @forelse($userActivity as $activity)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($activity->date)->format('d M Y') }}</p>
                        <p class="text-sm text-gray-500">New registrations</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-blue-600">{{ $activity->new_registrations }}</p>
                        <div class="w-20 h-2 bg-gray-200 rounded-full mt-1">
                            <div class="h-2 bg-blue-500 rounded-full" style="width: {{ min(100, ($activity->new_registrations / 10) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-calendar text-4xl mb-2"></i>
                    <p>No registration activity data</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Registration Stats Modal Container -->
    <div id="registration-stats-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Registration Statistics</h3>
                <button onclick="closeRegistrationStats()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="registration-stats-content">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function getRegistrationStats() {
            const modal = document.getElementById('registration-stats-modal');
            const content = document.getElementById('registration-stats-content');

            // Show modal and loading
            modal.classList.remove('hidden');
            content.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-gray-400 text-2xl"></i><p class="mt-2 text-gray-500">Loading registration statistics...</p></div>';

            // Make AJAX request
            fetch('{{ route("admin.reports.users.registration-stats") }}?' + new URLSearchParams({
                start_date: '{{ $startDate }}',
                end_date: '{{ $endDate }}',
                period: 'daily'
            }), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let html = '<div class="space-y-4">';

                    // User Growth Summary
                    html += `
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <p class="text-sm text-gray-600">Total Users</p>
                                <p class="text-2xl font-bold text-blue-900">${data.user_growth.total_users}</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <p class="text-sm text-gray-600">New Users</p>
                                <p class="text-2xl font-bold text-green-900">${data.user_growth.new_users}</p>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <p class="text-sm text-gray-600">Growth Rate</p>
                                <p class="text-2xl font-bold text-purple-900">${data.user_growth.growth_rate}%</p>
                            </div>
                        </div>
                    `;

                    // Registration by Period
                    html += '<h4 class="font-medium text-gray-900 mb-3">Daily Registrations</h4>';
                    if (data.registration_stats && data.registration_stats.length > 0) {
                        data.registration_stats.forEach(stat => {
                            html += `
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">${stat.period}</p>
                                        <p class="text-sm text-gray-500">${stat.user_type || 'All types'}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900">${stat.registrations}</p>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html += '<div class="text-center text-gray-500 py-4">No registration data available</div>';
                    }

                    html += '</div>';
                    content.innerHTML = html;
                } else {
                    content.innerHTML = '<div class="text-center text-red-500 py-4"><i class="fas fa-exclamation-triangle"></i> Error: ' + data.message + '</div>';
                }
            })
            .catch(error => {
                content.innerHTML = '<div class="text-center text-red-500 py-4"><i class="fas fa-exclamation-triangle"></i> Error loading registration statistics</div>';
            });
        }

        function closeRegistrationStats() {
            document.getElementById('registration-stats-modal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('registration-stats-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRegistrationStats();
            }
        });
    </script>
@endsection
