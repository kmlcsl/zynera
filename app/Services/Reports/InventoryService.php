<?php

namespace App\Services\Reports;

use App\Models\Product;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get inventory overview
     */
    public function getInventoryOverview()
    {
        $query = Product::query();

        if ($this->isProducer()) {
            $query->where('user_id', $this->getUserScope());
        }

        $totalProducts = $query->count();
        $activeProducts = $query->where('is_active', true)->count();
        $outOfStock = $query->where('stock', 0)->count();
        $lowStock = $query->where('stock', '>', 0)->where('stock', '<=', 10)->count();

        return [
            'total_products' => $totalProducts,
            'active_products' => $activeProducts,
            'out_of_stock' => $outOfStock,
            'low_stock' => $lowStock,
            'in_stock' => $totalProducts - $outOfStock
        ];
    }

    /**
     * Get products by stock level
     */
    public function getProductsByStock($type = 'all', $limit = 20)
    {
        $query = Product::with(['category:id,name', 'user:id,name']);

        if ($this->isProducer()) {
            $query->where('user_id', $this->getUserScope());
        }

        switch ($type) {
            case 'out_of_stock':
                $query->where('stock', 0);
                break;
            case 'low_stock':
                $query->where('stock', '>', 0)->where('stock', '<=', 10);
                break;
            case 'in_stock':
                $query->where('stock', '>', 10);
                break;
        }

        return $query->orderBy('stock', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get inventory by category
     */
    public function getInventoryByCategory()
    {
        $query = Product::select(
            'category_id',
            DB::raw('COUNT(*) as total_products'),
            DB::raw('SUM(stock) as total_stock'),
            DB::raw('SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) as out_of_stock'),
            DB::raw('SUM(CASE WHEN stock > 0 AND stock <= 10 THEN 1 ELSE 0 END) as low_stock')
        )
            ->with('category:id,name');

        if ($this->isProducer()) {
            $query->where('user_id', $this->getUserScope());
        }

        return $query->groupBy('category_id')
            ->orderBy('total_products', 'desc')
            ->get();
    }

    /**
     * Get expired products
     */
    public function getExpiredProducts($days = 30)
    {
        $query = Product::with(['category:id,name', 'user:id,name'])
            ->where('expired_date', '<=', Carbon::now()->addDays($days));

        if ($this->isProducer()) {
            $query->where('user_id', $this->getUserScope());
        }

        return $query->orderBy('expired_date', 'asc')->get();
    }

    /**
     * Get stock movement (could be enhanced with stock history table)
     */
    public function getStockMovement($productId = null, $startDate = null, $endDate = null)
    {
        // This is a simplified version - in real scenario, you'd have a stock_movements table
        $dateRange = $this->getDateRange($startDate, $endDate);

        $query = Product::with(['category:id,name', 'user:id,name']);

        if ($this->isProducer()) {
            $query->where('user_id', $this->getUserScope());
        }

        if ($productId) {
            $query->where('id', $productId);
        }

        return $query->select('id', 'name', 'stock', 'category_id', 'user_id', 'updated_at')
            ->whereBetween('updated_at', [$dateRange['startDate'], $dateRange['endDate']])
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    private function formatExpiredDate($expiredDate)
    {
        if (!$expiredDate) {
            return 'N/A';
        }

        try {
            if ($expiredDate instanceof \Carbon\Carbon) {
                return $expiredDate->format('Y-m-d');
            }

            if (is_string($expiredDate)) {
                return Carbon::createFromFormat('Y-m-d', $expiredDate)->format('Y-m-d');
            }

            return Carbon::parse($expiredDate)->format('Y-m-d');
        } catch (\Exception $e) {
            return 'Invalid Date';
        }
    }

    /**
     * Export inventory data
     */
    public function exportInventoryData($type = 'all')
    {
        $query = Product::with(['category:id,name', 'user:id,name']);

        if ($this->isProducer()) {
            $query->where('user_id', $this->getUserScope());
        }

        switch ($type) {
            case 'out_of_stock':
                $query->where('stock', 0);
                break;
            case 'low_stock':
                $query->where('stock', '>', 0)->where('stock', '<=', 10);
                break;
            case 'expired':
                $query->where('expired_date', '<=', Carbon::now()->addDays(30));
                break;
        }

        $products = $query->get();

        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'name' => $product->name,
                'category' => $product->category->name ?? 'N/A',
                'stock' => $product->stock,
                'price' => $product->price,
                'status' => $product->is_active ? 'Active' : 'Inactive',
                'expired_date' => $this->formatExpiredDate($product->expired_date),
                'producer' => $product->user->name ?? 'N/A'
            ];
        }

        return $this->exportToArray($data, [
            'Product Name',
            'Category',
            'Stock',
            'Price',
            'Status',
            'Expired Date',
            'Producer'
        ]);
    }
}
