<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\SalesService;
use App\Services\Reports\InventoryService;
use App\Services\Reports\FinancialService;
use App\Services\Reports\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use ZipArchive;
use Carbon\Carbon;

class BulkController extends Controller
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
     * Export multiple reports in bulk
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $reports = $request->get('reports', []);
        $format = $request->get('format', 'csv');

        if (empty($reports)) {
            return back()->with('error', 'Please select at least one report to export');
        }

        try {
            if (count($reports) === 1) {
                // Single report export
                return $this->exportSingleReport($reports[0], $startDate, $endDate, $format);
            } else {
                // Multiple reports - create ZIP file
                return $this->exportMultipleReports($reports, $startDate, $endDate, $format);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting reports: ' . $e->getMessage());
        }
    }

    /**
     * Export single report
     */
    private function exportSingleReport($reportType, $startDate, $endDate, $format)
    {
        $data = $this->getReportData($reportType, $startDate, $endDate);

        if ($format === 'csv') {
            return $this->downloadCsv($data, $reportType . '_report');
        }

        return back()->with('error', 'Export format not supported');
    }

    /**
     * Export multiple reports as ZIP
     */
    private function exportMultipleReports($reports, $startDate, $endDate, $format)
    {
        if ($format !== 'csv') {
            return back()->with('error', 'Bulk export only supports CSV format');
        }

        $tempDir = sys_get_temp_dir();
        $zipFileName = 'bulk_reports_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = $tempDir . '/' . $zipFileName;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            return back()->with('error', 'Could not create ZIP file');
        }

        foreach ($reports as $reportType) {
            try {
                $data = $this->getReportData($reportType, $startDate, $endDate);
                $csvContent = $this->generateCsvContent($data);
                $fileName = $reportType . '_report_' . date('Y-m-d') . '.csv';
                $zip->addFromString($fileName, $csvContent);
            } catch (\Exception $e) {
                // Continue with other reports if one fails
                $zip->addFromString('error_' . $reportType . '.txt', 'Error: ' . $e->getMessage());
            }
        }

        // Add summary file
        $summaryContent = $this->generateSummaryFile($reports, $startDate, $endDate);
        $zip->addFromString('summary.txt', $summaryContent);

        $zip->close();

        return $this->downloadZip($zipPath, $zipFileName);
    }

    /**
     * Get report data based on type
     */
    private function getReportData($reportType, $startDate, $endDate)
    {
        switch ($reportType) {
            case 'sales':
                return $this->salesService->exportSalesData($startDate, $endDate);

            case 'inventory':
                return $this->inventoryService->exportInventoryData('all');

            case 'inventory_low_stock':
                return $this->inventoryService->exportInventoryData('low_stock');

            case 'inventory_out_of_stock':
                return $this->inventoryService->exportInventoryData('out_of_stock');

            case 'financial_overview':
                return $this->financialService->exportFinancialData('overview', $startDate, $endDate);

            case 'financial_profit_loss':
                return $this->financialService->exportFinancialData('profit_loss', $startDate, $endDate);

            case 'financial_revenue_category':
                return $this->financialService->exportFinancialData('revenue_category', $startDate, $endDate);

            case 'analytics_overview':
                return $this->analyticsService->exportAnalyticsData('overview', $startDate, $endDate);

            case 'analytics_customer_behavior':
                return $this->analyticsService->exportAnalyticsData('customer_behavior', $startDate, $endDate);

            case 'analytics_conversion':
                return $this->analyticsService->exportAnalyticsData('conversion', $startDate, $endDate);

            default:
                throw new \Exception('Unknown report type: ' . $reportType);
        }
    }

    /**
     * Generate CSV content from array data
     */
    private function generateCsvContent($data)
    {
        $output = fopen('php://temp', 'w');

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }

    /**
     * Generate summary file for bulk export
     */
    private function generateSummaryFile($reports, $startDate, $endDate)
    {
        $content = "BULK REPORTS EXPORT SUMMARY\n";
        $content .= "===========================\n\n";
        $content .= "Export Date: " . Carbon::now()->format('Y-m-d H:i:s') . "\n";
        $content .= "Report Period: {$startDate} to {$endDate}\n\n";
        $content .= "Exported Reports:\n";

        foreach ($reports as $index => $reportType) {
            $content .= ($index + 1) . ". " . $this->getReportTitle($reportType) . "\n";
            $content .= "   File: {$reportType}_report_" . date('Y-m-d') . ".csv\n\n";
        }

        $content .= "\nReport Descriptions:\n";
        $content .= "===================\n\n";

        foreach ($reports as $reportType) {
            $content .= $this->getReportTitle($reportType) . ":\n";
            $content .= $this->getReportDescription($reportType) . "\n\n";
        }

        return $content;
    }

    /**
     * Get human-readable report title
     */
    private function getReportTitle($reportType)
    {
        $titles = [
            'sales' => 'Sales Report',
            'inventory' => 'Inventory Report (All Products)',
            'inventory_low_stock' => 'Inventory Report (Low Stock)',
            'inventory_out_of_stock' => 'Inventory Report (Out of Stock)',
            'financial_overview' => 'Financial Overview Report',
            'financial_profit_loss' => 'Profit & Loss Report',
            'financial_revenue_category' => 'Revenue by Category Report',
            'analytics_overview' => 'Analytics Overview Report',
            'analytics_customer_behavior' => 'Customer Behavior Analysis',
            'analytics_conversion' => 'Conversion Metrics Report'
        ];

        return $titles[$reportType] ?? ucfirst(str_replace('_', ' ', $reportType));
    }

    /**
     * Get report description
     */
    private function getReportDescription($reportType)
    {
        $descriptions = [
            'sales' => 'Contains detailed sales data including order numbers, customers, amounts, and status for the selected period.',
            'inventory' => 'Complete inventory listing with stock levels, prices, categories, and product status.',
            'inventory_low_stock' => 'Products with stock levels below the minimum threshold that need restocking.',
            'inventory_out_of_stock' => 'Products that are completely out of stock and unavailable for sale.',
            'financial_overview' => 'High-level financial metrics including revenue, costs, profits, and key ratios.',
            'financial_profit_loss' => 'Detailed profit and loss statement with revenue breakdown and expense categories.',
            'financial_revenue_category' => 'Revenue analysis broken down by product categories and quantities sold.',
            'analytics_overview' => 'Customer analytics including acquisition, retention, and engagement metrics.',
            'analytics_customer_behavior' => 'Detailed analysis of customer segments, popular products, and purchasing patterns.',
            'analytics_conversion' => 'Conversion rate metrics and customer journey analysis.'
        ];

        return $descriptions[$reportType] ?? 'No description available for this report type.';
    }

    /**
     * Download ZIP file
     */
    private function downloadZip($zipPath, $zipFileName)
    {
        $headers = [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $zipFileName . '"',
            'Content-Length' => filesize($zipPath)
        ];

        $callback = function () use ($zipPath) {
            readfile($zipPath);
            unlink($zipPath); // Clean up temp file
        };

        return Response::stream($callback, 200, $headers);
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
