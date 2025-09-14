<?php

namespace App\Services\Reports;

use App\Models\Order;
use App\Models\Product;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get financial overview
     */
    public function getFinancialOverview($startDate = null, $endDate = null)
    {
        $dateRange = $this->getDateRange($startDate, $endDate);

        $query = Order::whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->where('status', '!=', Order::STATUS_CANCELLED);

        if ($this->isProducer()) {
            $query->whereHas('orderItems.product', function ($q) {
                $q->where('user_id', $this->getUserScope());
            });
        }

        $totalRevenue = $query->sum('total_amount');
        $totalShipping = $query->sum('shipping_cost');
        $totalOrders = $query->count();

        // Calculate costs (simplified - you might want to add a costs table)
        $totalCosts = $this->calculateTotalCosts($dateRange);
        $grossProfit = $totalRevenue - $totalCosts;
        $netProfit = $grossProfit - $totalShipping; // Simplified calculation

        return [
            'total_revenue' => $totalRevenue,
            'total_shipping' => $totalShipping,
            'total_costs' => $totalCosts,
            'gross_profit' => $grossProfit,
            'net_profit' => $netProfit,
            'profit_margin' => $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 2) : 0,
            'total_orders' => $totalOrders
        ];
    }

    /**
     * Get revenue by period
     */
    public function getRevenueByPeriod($startDate = null, $endDate = null, $period = 'monthly')
    {
        $dateRange = $this->getDateRange($startDate, $endDate);

        $query = Order::whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->where('status', '!=', Order::STATUS_CANCELLED);

        if ($this->isProducer()) {
            $query->whereHas('orderItems.product', function ($q) {
                $q->where('user_id', $this->getUserScope());
            });
        }

        $format = $period === 'monthly' ? '%Y-%m' : '%Y-%m-%d';

        return $query->select(
            DB::raw("DATE_FORMAT(created_at, '$format') as period"),
            DB::raw('SUM(total_amount) as revenue'),
            DB::raw('SUM(shipping_cost) as shipping'),
            DB::raw('COUNT(*) as orders')
        )
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    /**
     * Get profit and loss statement
     */
    public function getProfitLoss($startDate = null, $endDate = null)
    {
        $dateRange = $this->getDateRange($startDate, $endDate);

        // Revenue
        $revenue = $this->getRevenueData($dateRange);

        // Costs
        $costs = $this->getCostsData($dateRange);

        // Calculate profit/loss
        $grossProfit = $revenue['total_revenue'] - $costs['total_costs'];
        $operatingProfit = $grossProfit - $costs['operating_expenses'];
        $netProfit = $operatingProfit - $costs['other_expenses'];

        return [
            'revenue' => $revenue,
            'costs' => $costs,
            'gross_profit' => $grossProfit,
            'operating_profit' => $operatingProfit,
            'net_profit' => $netProfit,
            'gross_margin' => $revenue['total_revenue'] > 0 ? round(($grossProfit / $revenue['total_revenue']) * 100, 2) : 0,
            'net_margin' => $revenue['total_revenue'] > 0 ? round(($netProfit / $revenue['total_revenue']) * 100, 2) : 0
        ];
    }

    /**
     * Get revenue by category
     */
    public function getRevenueByCategory($startDate = null, $endDate = null)
    {
        $dateRange = $this->getDateRange($startDate, $endDate);

        $query = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('orders.created_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->where('orders.status', '!=', Order::STATUS_CANCELLED);

        if ($this->isProducer()) {
            $query->where('products.user_id', $this->getUserScope());
        }

        return $query->select(
            'categories.id',
            'categories.name',
            DB::raw('SUM(order_items.total) as total_revenue'),
            DB::raw('SUM(order_items.quantity) as total_quantity')
        )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    /**
     * Export financial data
     */
    public function exportFinancialData($type = 'overview', $startDate = null, $endDate = null)
    {
        switch ($type) {
            case 'profit_loss':
                return $this->exportProfitLossData($startDate, $endDate);
            case 'revenue_category':
                return $this->exportRevenueByCategoryData($startDate, $endDate);
            default:
                return $this->exportOverviewData($startDate, $endDate);
        }
    }

    /**
     * Calculate total costs (simplified)
     */
    private function calculateTotalCosts($dateRange)
    {
        // This is a simplified calculation
        // In a real scenario, you'd have a costs table or calculate based on product costs
        $query = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->where('orders.status', '!=', Order::STATUS_CANCELLED);

        if ($this->isProducer()) {
            $query->where('products.user_id', $this->getUserScope());
        }

        // Assuming cost is 60% of price (you should store actual cost in products table)
        return $query->sum(DB::raw('order_items.quantity * products.price * 0.6'));
    }

    /**
     * Get revenue data
     */
    private function getRevenueData($dateRange)
    {
        $query = Order::whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->where('status', '!=', Order::STATUS_CANCELLED);

        if ($this->isProducer()) {
            $query->whereHas('orderItems.product', function ($q) {
                $q->where('user_id', $this->getUserScope());
            });
        }

        return [
            'total_revenue' => $query->sum('total_amount'),
            'shipping_revenue' => $query->sum('shipping_cost')
        ];
    }

    /**
     * Get costs data (simplified)
     */
    private function getCostsData($dateRange)
    {
        $totalCosts = $this->calculateTotalCosts($dateRange);

        return [
            'total_costs' => $totalCosts,
            'operating_expenses' => $totalCosts * 0.2, // 20% of costs as operating expenses
            'other_expenses' => $totalCosts * 0.05 // 5% as other expenses
        ];
    }

    /**
     * Export overview data
     */
    private function exportOverviewData($startDate, $endDate)
    {
        $overview = $this->getFinancialOverview($startDate, $endDate);

        return [
            ['Metric', 'Amount'],
            ['Total Revenue', $overview['total_revenue']],
            ['Total Shipping', $overview['total_shipping']],
            ['Total Costs', $overview['total_costs']],
            ['Gross Profit', $overview['gross_profit']],
            ['Net Profit', $overview['net_profit']],
            ['Profit Margin (%)', $overview['profit_margin']],
            ['Total Orders', $overview['total_orders']]
        ];
    }

    /**
     * Export profit loss data
     */
    private function exportProfitLossData($startDate, $endDate)
    {
        $profitLoss = $this->getProfitLoss($startDate, $endDate);

        return [
            ['Item', 'Amount'],
            ['Total Revenue', $profitLoss['revenue']['total_revenue']],
            ['Shipping Revenue', $profitLoss['revenue']['shipping_revenue']],
            ['Total Costs', $profitLoss['costs']['total_costs']],
            ['Operating Expenses', $profitLoss['costs']['operating_expenses']],
            ['Other Expenses', $profitLoss['costs']['other_expenses']],
            ['Gross Profit', $profitLoss['gross_profit']],
            ['Operating Profit', $profitLoss['operating_profit']],
            ['Net Profit', $profitLoss['net_profit']],
            ['Gross Margin (%)', $profitLoss['gross_margin']],
            ['Net Margin (%)', $profitLoss['net_margin']]
        ];
    }

    /**
     * Export revenue by category data
     */
    private function exportRevenueByCategoryData($startDate, $endDate)
    {
        $categoryRevenue = $this->getRevenueByCategory($startDate, $endDate);

        $data = [['Category', 'Revenue', 'Quantity']];

        foreach ($categoryRevenue as $category) {
            $data[] = [
                $category->name,
                $category->total_revenue,
                $category->total_quantity
            ];
        }

        return $data;
    }
}
