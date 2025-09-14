<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\SalesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SalesController extends Controller
{
    protected $salesService;

    public function __construct(SalesService $salesService)
    {
        $this->salesService = $salesService;
    }

    /**
     * Display sales reports
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $period = $request->get('period', 'daily');

        try {
            $overview = $this->salesService->getSalesOverview($startDate, $endDate);
            $salesByPeriod = $this->salesService->getSalesByPeriod($startDate, $endDate, $period);
            $topProducts = $this->salesService->getTopSellingProducts($startDate, $endDate, 10);
            $salesByStatus = $this->salesService->getSalesByStatus($startDate, $endDate);

            return view('admin.reports.sales.index', compact(
                'overview',
                'salesByPeriod',
                'topProducts',
                'salesByStatus',
                'startDate',
                'endDate',
                'period'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading sales report: ' . $e->getMessage());
        }
    }

    /**
     * Export sales data
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $format = $request->get('format', 'csv');

        try {
            $data = $this->salesService->exportSalesData($startDate, $endDate);

            if ($format === 'csv') {
                return $this->downloadCsv($data, 'sales_report');
            }

            return back()->with('error', 'Export format not supported');
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting sales data: ' . $e->getMessage());
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
