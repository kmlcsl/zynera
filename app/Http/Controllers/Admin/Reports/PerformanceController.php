<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\SalesService;
use App\Services\Reports\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PerformanceController extends Controller
{
    protected $salesService;
    protected $inventoryService;

    public function __construct(SalesService $salesService, InventoryService $inventoryService)
    {
        $this->salesService = $salesService;
        $this->inventoryService = $inventoryService;
    }

    /**
     * Display performance reports
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        try {
            // Sales performance
            $salesOverview = $this->salesService->getSalesOverview($startDate, $endDate);
            $salesByPeriod = $this->salesService->getSalesByPeriod($startDate, $endDate, 'monthly');
            $topProducts = $this->salesService->getTopSellingProducts($startDate, $endDate, 5);

            // Inventory performance
            $inventoryOverview = $this->inventoryService->getInventoryOverview();
            $categoryInventory = $this->inventoryService->getInventoryByCategory();

            // Calculate performance metrics
            $metrics = $this->calculatePerformanceMetrics($salesOverview, $inventoryOverview);

            return view('admin.reports.performance.index', compact(
                'salesOverview',
                'salesByPeriod',
                'topProducts',
                'inventoryOverview',
                'categoryInventory',
                'metrics',
                'startDate',
                'endDate'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading performance report: ' . $e->getMessage());
        }
    }

    /**
     * Generate performance insights
     */
    public function generateInsights(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        try {
            $salesOverview = $this->salesService->getSalesOverview($startDate, $endDate);
            $inventoryOverview = $this->inventoryService->getInventoryOverview();

            $insights = $this->generateInsightsData($salesOverview, $inventoryOverview);

            return response()->json([
                'success' => true,
                'insights' => $insights
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating insights: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export performance data
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $format = $request->get('format', 'csv');

        try {
            $data = $this->prepareExportData($startDate, $endDate);

            if ($format === 'csv') {
                return $this->downloadCsv($data, 'performance_report');
            }

            return back()->with('error', 'Export format not supported');
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting performance data: ' . $e->getMessage());
        }
    }

    /**
     * Calculate performance metrics
     */
    private function calculatePerformanceMetrics($salesOverview, $inventoryOverview)
    {
        $totalProducts = $inventoryOverview['total_products'];
        $activeProducts = $inventoryOverview['active_products'];
        $totalOrders = $salesOverview['total_orders'];
        $totalRevenue = $salesOverview['total_revenue'];

        return [
            'product_performance' => $totalProducts > 0 ? round(($activeProducts / $totalProducts) * 100, 2) : 0,
            'revenue_per_product' => $activeProducts > 0 ? round($totalRevenue / $activeProducts, 2) : 0,
            'orders_per_day' => $totalOrders > 0 ? round($totalOrders / 30, 2) : 0, // Assuming 30 days
            'stock_turnover' => $inventoryOverview['in_stock'] > 0 ? round($totalOrders / $inventoryOverview['in_stock'], 2) : 0
        ];
    }

    /**
     * Generate insights data
     */
    private function generateInsightsData($salesOverview, $inventoryOverview)
    {
        $insights = [];

        // Sales insights
        if ($salesOverview['completion_rate'] < 80) {
            $insights[] = [
                'type' => 'warning',
                'title' => 'Low Order Completion Rate',
                'message' => 'Order completion rate is ' . $salesOverview['completion_rate'] . '%. Consider improving order processing.'
            ];
        }

        // Inventory insights
        if ($inventoryOverview['out_of_stock'] > 0) {
            $insights[] = [
                'type' => 'danger',
                'title' => 'Out of Stock Products',
                'message' => $inventoryOverview['out_of_stock'] . ' products are out of stock. Restock immediately.'
            ];
        }

        if ($inventoryOverview['low_stock'] > 0) {
            $insights[] = [
                'type' => 'warning',
                'title' => 'Low Stock Alert',
                'message' => $inventoryOverview['low_stock'] . ' products have low stock levels.'
            ];
        }

        // Revenue insights
        if ($salesOverview['average_order_value'] > 0) {
            $insights[] = [
                'type' => 'info',
                'title' => 'Average Order Value',
                'message' => 'Current AOV is Rp ' . number_format($salesOverview['average_order_value'], 0, ',', '.') . '. Consider upselling strategies.'
            ];
        }

        return $insights;
    }

    /**
     * Prepare export data
     */
    private function prepareExportData($startDate, $endDate)
    {
        $salesOverview = $this->salesService->getSalesOverview($startDate, $endDate);
        $inventoryOverview = $this->inventoryService->getInventoryOverview();
        $metrics = $this->calculatePerformanceMetrics($salesOverview, $inventoryOverview);

        return [
            ['Metric', 'Value'],
            ['Total Orders', $salesOverview['total_orders']],
            ['Total Revenue', $salesOverview['total_revenue']],
            ['Average Order Value', $salesOverview['average_order_value']],
            ['Completion Rate (%)', $salesOverview['completion_rate']],
            ['Total Products', $inventoryOverview['total_products']],
            ['Active Products', $inventoryOverview['active_products']],
            ['Out of Stock', $inventoryOverview['out_of_stock']],
            ['Low Stock', $inventoryOverview['low_stock']],
            ['Product Performance (%)', $metrics['product_performance']],
            ['Revenue per Product', $metrics['revenue_per_product']],
            ['Orders per Day', $metrics['orders_per_day']],
            ['Stock Turnover', $metrics['stock_turnover']]
        ];
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
