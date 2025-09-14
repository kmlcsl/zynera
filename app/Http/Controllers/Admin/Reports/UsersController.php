<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersController extends Controller
{
    /**
     * Display users reports (Admin only)
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $userType = $request->get('user_type', 'all');

        try {
            $overview = $this->getUsersOverview($startDate, $endDate, $userType);
            $usersByType = $this->getUsersByType($startDate, $endDate);
            $topCustomers = $this->getTopCustomers($startDate, $endDate);
            $userActivity = $this->getUserActivity($startDate, $endDate);

            return view('admin.reports.users.index', compact(
                'overview',
                'usersByType',
                'topCustomers',
                'userActivity',
                'startDate',
                'endDate',
                'userType'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading users report: ' . $e->getMessage());
        }
    }

    /**
     * Get registration statistics
     */
    public function registrationStats(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30));
        $endDate = $request->get('end_date', Carbon::now());
        $period = $request->get('period', 'daily');

        try {
            $registrationStats = $this->getRegistrationStats($startDate, $endDate, $period);
            $userGrowth = $this->getUserGrowth($startDate, $endDate);

            return response()->json([
                'success' => true,
                'registration_stats' => $registrationStats,
                'user_growth' => $userGrowth
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading registration stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user activity report
     */
    public function activityReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30));
        $endDate = $request->get('end_date', Carbon::now());

        try {
            $activeUsers = $this->getActiveUsers($startDate, $endDate);
            $inactiveUsers = $this->getInactiveUsers($startDate, $endDate);
            $userEngagement = $this->getUserEngagement($startDate, $endDate);

            return view('admin.reports.users.activity', compact(
                'activeUsers',
                'inactiveUsers',
                'userEngagement',
                'startDate',
                'endDate'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading user activity report: ' . $e->getMessage());
        }
    }

    /**
     * Export users data
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $type = $request->get('type', 'overview');
        $format = $request->get('format', 'csv');

        try {
            $data = $this->prepareExportData($type, $startDate, $endDate);

            if ($format === 'csv') {
                return $this->downloadCsv($data, 'users_report_' . $type);
            }

            return back()->with('error', 'Export format not supported');
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting users data: ' . $e->getMessage());
        }
    }

    /**
     * Get users overview
     */
    private function getUsersOverview($startDate, $endDate, $userType = 'all')
    {
        $query = User::whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);

        if ($userType !== 'all') {
            $query->where('user_type', $userType);
        }

        $totalUsers = $query->count();
        $verifiedUsers = $query->where('is_verified', true)->count();
        $activeUsers = $this->getActiveUsersCount($startDate, $endDate, $userType);

        // Previous period for comparison
        $previousStart = Carbon::parse($startDate)->subDays(
            Carbon::parse($endDate)->diffInDays(Carbon::parse($startDate))
        );
        $previousEnd = Carbon::parse($startDate)->subDay();

        $previousQuery = User::whereBetween('created_at', [$previousStart, $previousEnd]);
        if ($userType !== 'all') {
            $previousQuery->where('user_type', $userType);
        }
        $previousTotal = $previousQuery->count();

        $growth = $previousTotal > 0 ? round((($totalUsers - $previousTotal) / $previousTotal) * 100, 2) : 0;

        return [
            'total_users' => $totalUsers,
            'verified_users' => $verifiedUsers,
            'active_users' => $activeUsers,
            'verification_rate' => $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100, 2) : 0,
            'growth_rate' => $growth
        ];
    }

    /**
     * Get users by type
     */
    private function getUsersByType($startDate, $endDate)
    {
        return User::select(
            'user_type',
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(CASE WHEN is_verified = 1 THEN 1 ELSE 0 END) as verified_count')
        )
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->groupBy('user_type')
            ->get();
    }

    /**
     * Get top customers by orders and revenue
     */
    private function getTopCustomers($startDate, $endDate, $limit = 10)
    {
        return User::select(
            'users.id',
            'users.name',
            'users.email',
            DB::raw('COUNT(orders.id) as total_orders'),
            DB::raw('SUM(orders.total_amount) as total_spent')
        )
            ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
            ->where('users.user_type', 'customer')
            ->whereBetween('orders.created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->where('orders.status', '!=', Order::STATUS_CANCELLED)
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get user activity by period
     */
    private function getUserActivity($startDate, $endDate)
    {
        return User::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'),
            DB::raw('COUNT(*) as new_registrations')
        )
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get registration statistics
     */
    private function getRegistrationStats($startDate, $endDate, $period = 'daily')
    {
        $format = $period === 'monthly' ? '%Y-%m' : '%Y-%m-%d';

        return User::select(
            DB::raw("DATE_FORMAT(created_at, '$format') as period"),
            DB::raw('COUNT(*) as registrations'),
            'user_type'
        )
            ->whereBetween('created_at', [Carbon::parse($startDate), Carbon::parse($endDate)])
            ->groupBy('period', 'user_type')
            ->orderBy('period')
            ->get();
    }

    /**
     * Get user growth
     */
    private function getUserGrowth($startDate, $endDate)
    {
        $totalUsers = User::count();
        $newUsers = User::whereBetween('created_at', [
            Carbon::parse($startDate),
            Carbon::parse($endDate)
        ])->count();

        return [
            'total_users' => $totalUsers,
            'new_users' => $newUsers,
            'growth_rate' => $totalUsers > 0 ? round(($newUsers / $totalUsers) * 100, 2) : 0
        ];
    }

    /**
     * Get active users (users with orders in the period)
     */
    private function getActiveUsers($startDate, $endDate)
    {
        return User::whereHas('orders', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate),
                Carbon::parse($endDate)
            ]);
        })
            ->with(['orders' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [
                    Carbon::parse($startDate),
                    Carbon::parse($endDate)
                ]);
            }])
            ->get();
    }

    /**
     * Get inactive users
     */
    private function getInactiveUsers($startDate, $endDate)
    {
        return User::whereDoesntHave('orders', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate),
                Carbon::parse($endDate)
            ]);
        })
            ->where('created_at', '<', Carbon::parse($startDate))
            ->limit(100) // Limit for performance
            ->get();
    }

    /**
     * Get user engagement metrics
     */
    private function getUserEngagement($startDate, $endDate)
    {
        $totalUsers = User::count();
        $activeUsers = $this->getActiveUsersCount($startDate, $endDate);

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'engagement_rate' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0
        ];
    }

    /**
     * Get active users count
     */
    private function getActiveUsersCount($startDate, $endDate, $userType = 'all')
    {
        $query = User::whereHas('orders', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [
                Carbon::parse($startDate),
                Carbon::parse($endDate)
            ]);
        });

        if ($userType !== 'all') {
            $query->where('user_type', $userType);
        }

        return $query->count();
    }

    /**
     * Prepare export data based on type
     */
    private function prepareExportData($type, $startDate, $endDate)
    {
        switch ($type) {
            case 'customers':
                return $this->exportCustomersData($startDate, $endDate);
            case 'activity':
                return $this->exportActivityData($startDate, $endDate);
            default:
                return $this->exportOverviewData($startDate, $endDate);
        }
    }

    /**
     * Export overview data
     */
    private function exportOverviewData($startDate, $endDate)
    {
        $overview = $this->getUsersOverview($startDate, $endDate);
        $usersByType = $this->getUsersByType($startDate, $endDate);

        $data = [
            ['Metric', 'Value'],
            ['Total Users', $overview['total_users']],
            ['Verified Users', $overview['verified_users']],
            ['Active Users', $overview['active_users']],
            ['Verification Rate (%)', $overview['verification_rate']],
            ['Growth Rate (%)', $overview['growth_rate']],
            ['', ''],
            ['User Type', 'Count', 'Verified']
        ];

        foreach ($usersByType as $userType) {
            $data[] = [
                $userType->user_type,
                $userType->count,
                $userType->verified_count
            ];
        }

        return $data;
    }

    /**
     * Export customers data
     */
    private function exportCustomersData($startDate, $endDate)
    {
        $customers = $this->getTopCustomers($startDate, $endDate, 100);

        $data = [['Name', 'Email', 'Total Orders', 'Total Spent']];

        foreach ($customers as $customer) {
            $data[] = [
                $customer->name,
                $customer->email,
                $customer->total_orders,
                $customer->total_spent
            ];
        }

        return $data;
    }

    /**
     * Export activity data
     */
    private function exportActivityData($startDate, $endDate)
    {
        $activity = $this->getUserActivity($startDate, $endDate);

        $data = [['Date', 'New Registrations']];

        foreach ($activity as $day) {
            $data[] = [
                $day->date,
                $day->new_registrations
            ];
        }

        return $data;
    }

    /**
     * Download CSV file
     */
    private function downloadCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
