<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\SalesService;
use App\Services\Reports\InventoryService;
use App\Services\Reports\FinancialService;
use App\Services\Reports\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $salesService;
    protected $inventoryService;
    protected $financialService;
    protected $analyticsService;

    public function __construct(
        SalesService $salesService,
        InventoryService $inventoryService,
        FinancialService $financialService,
        AnalyticsService $analyticsService
    ) {
        $this->salesService = $salesService;
        $this->inventoryService = $inventoryService;
        $this->financialService = $financialService;
        $this->analyticsService = $analyticsService;
    }

    /**
     * Export comprehensive dashboard report
     */
    public function exportPDF(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $format = $request->get('format', 'csv');

        try {
            $dashboardData = $this->prepareDashboardData($startDate, $endDate);

            if ($format === 'csv') {
                return $this->downloadCsv($dashboardData, 'dashboard_report');
            }

            // For future PDF implementation
            return back()->with('error', 'PDF export not yet implemented');
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting dashboard report: ' . $e->getMessage());
        }
    }

    /**
     * Prepare comprehensive dashboard data
     */
    private function prepareDashboardData($startDate, $endDate)
    {
        // Get data from all services
        try {
            $salesOverview = $this->salesService->getSalesOverview($startDate, $endDate);
            if (!$salesOverview) {
                $salesOverview = [];
            }
        } catch (\Exception $e) {
            $salesOverview = [];
        }
        $inventoryOverview = $this->inventoryService->getInventoryOverview();
        $financialOverview = $this->financialService->getFinancialOverview($startDate, $endDate);
        $analyticsOverview = $this->analyticsService->getAnalyticsOverview($startDate, $endDate);

        // Get top selling products
        $topProducts = $this->salesService->getTopSellingProducts($startDate, $endDate, 5);

        // Get sales by status
        $salesByStatus = $this->salesService->getSalesByStatus($startDate, $endDate);

        // Get revenue by category
        $revenueByCategory = $this->financialService->getRevenueByCategory($startDate, $endDate);

        // Prepare CSV data
        $data = [
            ['DASHBOARD REPORT'],
            ['Period: ' . $startDate . ' to ' . $endDate],
            ['Generated: ' . Carbon::now()->format('Y-m-d H:i:s')],
            [''],

            // Sales Overview
            ['=== SALES OVERVIEW ==='],
            ['Total Orders', $salesOverview['total_orders']],
            ['Total Revenue', 'Rp ' . number_format($salesOverview['total_revenue'], 0, ',', '.')],
            ['Completed Orders', $salesOverview['completed_orders']],
            ['Average Order Value', 'Rp ' . number_format($salesOverview['average_order_value'], 0, ',', '.')],
            ['Completion Rate', $salesOverview['completion_rate'] . '%'],
            [''],

            // Inventory Overview
            ['=== INVENTORY OVERVIEW ==='],
            ['Total Products', $inventoryOverview['total_products']],
            ['Active Products', $inventoryOverview['active_products']],
            ['Out of Stock', $inventoryOverview['out_of_stock']],
            ['Low Stock', $inventoryOverview['low_stock']],
            ['In Stock', $inventoryOverview['in_stock']],
            [''],

            // Financial Overview
            ['=== FINANCIAL OVERVIEW ==='],
            ['Total Revenue', 'Rp ' . number_format($financialOverview['total_revenue'], 0, ',', '.')],
            ['Total Costs', 'Rp ' . number_format($financialOverview['total_costs'], 0, ',', '.')],
            ['Gross Profit', 'Rp ' . number_format($financialOverview['gross_profit'], 0, ',', '.')],
            ['Net Profit', 'Rp ' . number_format($financialOverview['net_profit'], 0, ',', '.')],
            ['Profit Margin', $financialOverview['profit_margin'] . '%'],
            [''],

            // Analytics Overview
            ['=== ANALYTICS OVERVIEW ==='],
            ['Total Customers', $analyticsOverview['total_customers']],
            ['Active Customers', $analyticsOverview['active_customers']],
            ['Repeat Customers', $analyticsOverview['repeat_customers']],
            ['Customer Retention Rate', $analyticsOverview['customer_retention_rate'] . '%'],
            ['Orders per Customer', $analyticsOverview['orders_per_customer']],
            [''],

            // Top Selling Products
            ['=== TOP SELLING PRODUCTS ==='],
            ['Product Name', 'Quantity Sold', 'Revenue']
        ];

        foreach ($topProducts as $product) {
            $data[] = [
                $product->product->name ?? 'N/A',
                $product->total_quantity,
                'Rp ' . number_format($product->total_revenue, 0, ',', '.')
            ];
        }

        $data[] = [''];

        // Sales by Status
        $data[] = ['=== SALES BY STATUS ==='];
        $data[] = ['Status', 'Count', 'Total Amount'];

        foreach ($salesByStatus as $status) {
            $data[] = [
                ucfirst(str_replace('_', ' ', $status->status)),
                $status->count,
                'Rp ' . number_format($status->total_amount, 0, ',', '.')
            ];
        }

        $data[] = [''];

        // Revenue by Category
        $data[] = ['=== REVENUE BY CATEGORY ==='];
        $data[] = ['Category', 'Revenue', 'Quantity'];

        foreach ($revenueByCategory as $category) {
            $data[] = [
                $category->name,
                'Rp ' . number_format($category->total_revenue, 0, ',', '.'),
                $category->total_quantity
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
