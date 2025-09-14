<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Display analytics reports
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        try {
            $overview = $this->analyticsService->getAnalyticsOverview($startDate, $endDate);
            $conversionMetrics = $this->analyticsService->getConversionMetrics($startDate, $endDate);
            $customerGrowth = $this->analyticsService->getCustomerGrowth($startDate, $endDate, 'monthly');
            $customerSegments = $this->analyticsService->getCustomerSegments($startDate, $endDate);

            return view('admin.reports.analytics.index', compact(
                'overview',
                'conversionMetrics',
                'customerGrowth',
                'customerSegments',
                'startDate',
                'endDate'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading analytics report: ' . $e->getMessage());
        }
    }

    /**
     * Display customer behavior analysis
     */
    public function customerBehavior(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        try {
            $customerBehavior = $this->analyticsService->getCustomerBehavior($startDate, $endDate);
            $overview = $this->analyticsService->getAnalyticsOverview($startDate, $endDate);

            return view('admin.reports.analytics.customer-behavior', compact(
                'customerBehavior',
                'overview',
                'startDate',
                'endDate'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading customer behavior analysis: ' . $e->getMessage());
        }
    }

    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $type = $request->get('type', 'overview');
        $format = $request->get('format', 'csv');

        try {
            $data = $this->analyticsService->exportAnalyticsData($type, $startDate, $endDate);

            if ($format === 'csv') {
                return $this->downloadCsv($data, 'analytics_report_' . $type);
            }

            return back()->with('error', 'Export format not supported');
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting analytics data: ' . $e->getMessage());
        }
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
