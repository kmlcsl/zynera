<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Display inventory reports
     */
    public function index(Request $request)
    {
        $stockType = $request->get('stock_type', 'all');
        $limit = $request->get('limit', 20);

        try {
            $overview = $this->inventoryService->getInventoryOverview();
            $products = $this->inventoryService->getProductsByStock($stockType, $limit);
            $categoryInventory = $this->inventoryService->getInventoryByCategory();
            $expiredProducts = $this->inventoryService->getExpiredProducts(30);

            return view('admin.reports.inventory.index', compact(
                'overview',
                'products',
                'categoryInventory',
                'expiredProducts',
                'stockType',
                'limit'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading inventory report: ' . $e->getMessage());
        }
    }

    /**
     * Export inventory data
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'all');
        $format = $request->get('format', 'csv');

        try {
            $data = $this->inventoryService->exportInventoryData($type);

            if ($format === 'csv') {
                return $this->downloadCsv($data, 'inventory_report');
            }

            return back()->with('error', 'Export format not supported');
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting inventory data: ' . $e->getMessage());
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
