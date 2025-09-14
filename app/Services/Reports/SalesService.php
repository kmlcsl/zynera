<?php

namespace App\Services\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get sales overview
     */
    public function getSalesOverview($startDate = null, $endDate = null)
    {
        $dateRange = $this->getDateRange($startDate, $endDate);

        $query = Order::whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->where('status', '!=', Order::STATUS_CANCELLED);

        // Apply user scope for producer
        if ($this->isProducer()) {
            $query->whereHas('orderItems.product', function ($q) {
                $q->where('user_id', $this->getUserScope());
            });
        }

        $totalOrders = $query->count();
        $totalRevenue = $query->sum('total_amount');
        $completedOrders = $query->where('status', Order::STATUS_DELIVERED)->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'completed_orders' => $completedOrders,
            'average_order_value' => $averageOrderValue,
            'completion_rate' => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 2) : 0
        ];
    }

    /**
     * Get sales by period
     */
    public function getSalesByPeriod($startDate = null, $endDate = null, $period = 'daily')
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
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total_amount) as total_revenue')
        )
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    /**
     * Get top selling products
     */
    public function getTopSellingProducts($startDate = null, $endDate = null, $limit = 10)
    {
        $dateRange = $this->getDateRange($startDate, $endDate);

        $query = OrderItem::whereHas('order', function ($q) use ($dateRange) {
            $q->whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']])
                ->where('status', '!=', Order::STATUS_CANCELLED);
        });

        if ($this->isProducer()) {
            $query->whereHas('product', function ($q) {
                $q->where('user_id', $this->getUserScope());
            });
        }

        return $query->select(
            'product_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(total) as total_revenue')
        )
            ->with('product:id,name,price')
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get sales by status
     */
    public function getSalesByStatus($startDate = null, $endDate = null)
    {
        $dateRange = $this->getDateRange($startDate, $endDate);

        $query = Order::whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']]);

        if ($this->isProducer()) {
            $query->whereHas('orderItems.product', function ($q) {
                $q->where('user_id', $this->getUserScope());
            });
        }

        return $query->select(
            'status',
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(total_amount) as total_amount')
        )
            ->groupBy('status')
            ->get();
    }

    /**
     * Export sales data
     */
    public function exportSalesData($startDate = null, $endDate = null)
    {
        $dateRange = $this->getDateRange($startDate, $endDate);

        $query = Order::with(['user:id,name', 'orderItems.product:id,name'])
            ->whereBetween('created_at', [$dateRange['startDate'], $dateRange['endDate']]);

        if ($this->isProducer()) {
            $query->whereHas('orderItems.product', function ($q) {
                $q->where('user_id', $this->getUserScope());
            });
        }

        $orders = $query->get();

        $data = [];
        foreach ($orders as $order) {
            $data[] = [
                'order_number' => $order->order_number,
                'customer' => $order->user->name,
                'total_amount' => $order->total_amount,
                'status' => $order->status_label,
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'items_count' => $order->orderItems->count()
            ];
        }

        return $this->exportToArray($data, [
            'Order Number',
            'Customer',
            'Total Amount',
            'Status',
            'Created At',
            'Items Count'
        ]);
    }
}
