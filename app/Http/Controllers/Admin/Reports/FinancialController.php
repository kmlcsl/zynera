<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\FinancialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FinancialController extends Controller
{
    protected $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
    }

    /**
     * Display financial reports
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $period = $request->get('period', 'monthly');

        try {
            $overview = $this->financialService->getFinancialOverview($startDate, $endDate);
            $revenueByPeriod = $this->financialService->getRevenueByPeriod($startDate, $endDate, $period);
            $revenueByCategory = $this->financialService->getRevenueByCategory($startDate, $endDate);

            return view('admin.reports.financial.index', compact(
                'overview',
                'revenueByPeriod',
                'revenueByCategory',
                'startDate',
                'endDate',
                'period'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading financial report: ' . $e->getMessage());
        }
    }

    /**
     * Display profit and loss report
     */
    public function profitLoss(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        try {
            $profitLoss = $this->financialService->getProfitLoss($startDate, $endDate);
            $revenueByPeriod = $this->financialService->getRevenueByPeriod($startDate, $endDate, 'monthly');

            return view('admin.reports.financial.profit-loss', compact(
                'profitLoss',
                'revenueByPeriod',
                'startDate',
                'endDate'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading profit & loss report: ' . $e->getMessage());
        }
    }

    /**
     * Export financial data
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $type = $request->get('type', 'overview');
        $format = $request->get('format', 'csv');

        try {
            $data = $this->financialService->exportFinancialData($type, $startDate, $endDate);

            if ($format === 'csv') {
                return $this->downloadCsv($data, 'financial_report_' . $type);
            }

            return back()->with('error', 'Export format not supported');
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting financial data: ' . $e->getMessage());
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
