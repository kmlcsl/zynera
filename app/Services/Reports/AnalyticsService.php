<?php

namespace App\Services\Reports;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get analytics overview
     */
    public function getAnalyticsOverview($startDate = null, $endDate = null)
    {
        $dateRange = $this->getDateRange($startDate, $endDate);

        $totalCustomers = User::where('user_type', 'customer')
            ->whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->count();

        $activeCustomers = User::where('user_type', 'customer')
            ->whereHas('orders', function ($q) use ($dateRange) {
                $q->whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']]);
            })
            ->count();

        $repeatCustomers = User::where('user_type', 'customer')
            ->whereHas('orders', function ($q) use ($dateRange) {
                $q->whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']]);
            }, '>', 1)
            ->count();

        $query = Order::whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->where('status', '!=', Order::STATUS_CANCELLED);

        if ($this->isProducer()) {
            $query->whereHas('orderItems.product', function ($q) {
                $q->where('user_id', $this->getUserScope());
            });
        }

        $totalOrders = $query->count();
        $totalRevenue = $query->sum('total_amount');

        return [
            'total_customers' => $totalCustomers,
            'active_customers' => $activeCustomers,
            'repeat_customers' => $repeatCustomers,
            'customer_retention_rate' => $activeCustomers > 0 ? round(($repeatCustomers / $activeCustomers) * 100, 2) : 0,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'average_order_value' => $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0,
            'orders_per_customer' => $activeCustomers > 0 ? round($totalOrders / $activeCustomers, 2) : 0
        ];
    }

    /**
     * Get customer behavior analysis
     */
    public function getCustomerBehavior($startDate = null, $endDate = null)
    {
        $dateRange = $this->getDateRange($startDate, $endDate);

        // Customer segments by order frequency
        $customerSegments = $this->getCustomerSegments($dateRange);

        // Popular products
        $popularProducts = $this->getPopularProducts($dateRange, 10);

        // Customer acquisition by period
        $customerAcquisition = $this->getCustomerAcquisition($dateRange);

        // Order patterns by day of week
        $orderPatterns = $this->getOrderPatterns($dateRange);

        return [
            'customer_segments' => $customerSegments,
            'popular_products' => $popularProducts,
            'customer_acquisition' => $customerAcquisition,
            'order_patterns' => $orderPatterns
        ];
    }

    /**
     * Get conversion metrics
     */
    public function getConversionMetrics($startDate = null, $endDate = null)
    {
        $dateRange = $this->getDateRange($startDate, $endDate);

        // This is simplified - in real scenario you'd track website visits, cart additions, etc.
        $totalCustomers = User::where('user_type', 'customer')->count();
        $customersWithOrders = User::where('user_type', 'customer')
            ->whereHas('orders', function ($q) use ($dateRange) {
                $q->whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']]);
            })
            ->count();

        $conversionRate = $totalCustomers > 0 ? round(($customersWithOrders / $totalCustomers) * 100, 2) : 0;

        return [
            'total_customers' => $totalCustomers,
            'customers_with_orders' => $customersWithOrders,
            'conversion_rate' => $conversionRate
        ];
    }

    /**
     * Export analytics data
     */
    public function exportAnalyticsData($type = 'overview', $startDate = null, $endDate = null)
    {
        switch ($type) {
            case 'customer_behavior':
                return $this->exportCustomerBehaviorData($startDate, $endDate);
            case 'conversion':
                return $this->exportConversionData($startDate, $endDate);
            default:
                return $this->exportOverviewData($startDate, $endDate);
        }
    }

    /**
     * Get customer segments
     */
    public function getCustomerSegments($startDate = null, $endDate = null)
    {
        $dateRange = $this->getDateRange($startDate, $endDate);

        return DB::table('users')
            ->select(
                DB::raw('
                CASE
                    WHEN order_count = 0 THEN "No Orders"
                    WHEN order_count = 1 THEN "One-time Buyer"
                    WHEN order_count BETWEEN 2 AND 5 THEN "Regular Customer"
                    WHEN order_count > 5 THEN "VIP Customer"
                END as segment
            '),
                DB::raw('COUNT(*) as customer_count'),
                DB::raw('COALESCE(AVG(total_spent), 0) as avg_spent')
            )
            ->leftJoin(
                DB::raw('(
                SELECT
                    user_id,
                    COUNT(*) as order_count,
                    SUM(total_amount) as total_spent
                FROM orders
                WHERE created_at BETWEEN "' . $dateRange['startDate'] . '" AND "' . $dateRange['endDate'] . '"
                AND status != "cancelled"
                GROUP BY user_id
            ) as order_stats'),
                'users.id',
                '=',
                'order_stats.user_id'
            )
            ->where('users.user_type', 'customer')
            ->groupBy('segment')
            ->orderByRaw('
            CASE segment
                WHEN "VIP Customer" THEN 1
                WHEN "Regular Customer" THEN 2
                WHEN "One-time Buyer" THEN 3
                WHEN "No Orders" THEN 4
            END
        ')
            ->get();
    }

    /**
     * Get popular products
     */
    private function getPopularProducts($dateRange, $limit = 10)
    {
        $query = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->where('orders.status', '!=', Order::STATUS_CANCELLED);

        if ($this->isProducer()) {
            $query->where('products.user_id', $this->getUserScope());
        }

        return $query->select(
            'products.id',
            'products.name',
            DB::raw('SUM(order_items.quantity) as total_sold'),
            DB::raw('COUNT(DISTINCT orders.user_id) as unique_customers')
        )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get customer acquisition by period
     */
    private function getCustomerAcquisition($dateRange)
    {
        return User::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as new_customers')
        )
            ->where('user_type', 'customer')
            ->whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get order patterns by day of week
     */
    private function getOrderPatterns($dateRange)
    {
        $query = Order::select(
            DB::raw('DAYNAME(created_at) as day_name'),
            DB::raw('DAYOFWEEK(created_at) as day_number'),
            DB::raw('COUNT(*) as order_count'),
            DB::raw('SUM(total_amount) as total_revenue')
        )
            ->whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->where('status', '!=', Order::STATUS_CANCELLED);

        if ($this->isProducer()) {
            $query->whereHas('orderItems.product', function ($q) {
                $q->where('user_id', $this->getUserScope());
            });
        }

        return $query->groupBy('day_name', 'day_number')
            ->orderBy('day_number')
            ->get();
    }

    /**
     * Export overview data
     */
    private function exportOverviewData($startDate, $endDate)
    {
        $overview = $this->getAnalyticsOverview($startDate, $endDate);

        return [
            ['Metric', 'Value'],
            ['Total Customers', $overview['total_customers']],
            ['Active Customers', $overview['active_customers']],
            ['Repeat Customers', $overview['repeat_customers']],
            ['Customer Retention Rate (%)', $overview['customer_retention_rate']],
            ['Total Orders', $overview['total_orders']],
            ['Total Revenue', $overview['total_revenue']],
            ['Average Order Value', $overview['average_order_value']],
            ['Orders per Customer', $overview['orders_per_customer']]
        ];
    }

    /**
     * Export customer behavior data
     */
    private function exportCustomerBehaviorData($startDate, $endDate)
    {
        $behavior = $this->getCustomerBehavior($startDate, $endDate);

        $data = [['Analysis Type', 'Details']];

        // Customer segments
        $data[] = ['Customer Segments', ''];
        foreach ($behavior['customer_segments'] as $segment) {
            $data[] = [$segment->segment, $segment->customer_count . ' customers'];
        }

        $data[] = ['', ''];
        $data[] = ['Popular Products', ''];
        foreach ($behavior['popular_products'] as $product) {
            $data[] = [$product->name, $product->total_sold . ' sold to ' . $product->unique_customers . ' customers'];
        }

        return $data;
    }

    /**
     * Export conversion data
     */
    private function exportConversionData($startDate, $endDate)
    {
        $conversion = $this->getConversionMetrics($startDate, $endDate);

        return [
            ['Metric', 'Value'],
            ['Total Customers', $conversion['total_customers']],
            ['Customers with Orders', $conversion['customers_with_orders']],
            ['Conversion Rate (%)', $conversion['conversion_rate']]
        ];
    }

    /**
     * Get customer growth by period
     */
    public function getCustomerGrowth($startDate = null, $endDate = null, $period = 'monthly')
    {
        $dateRange = $this->getDateRange($startDate, $endDate);

        $format = $period === 'monthly' ? '%Y-%m' : '%Y-%m-%d';

        return User::select(
            DB::raw("DATE_FORMAT(created_at, '$format') as period"),
            DB::raw('COUNT(*) as new_customers'),
            DB::raw('SUM(COUNT(*)) OVER (ORDER BY DATE_FORMAT(created_at, "' . $format . '")) as total_customers')
        )
            ->where('user_type', 'customer')
            ->whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }
}
