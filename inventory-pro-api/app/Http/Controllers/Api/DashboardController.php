<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockLevel;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'stats' => $this->getStats(),
            'recent_movements' => $this->getRecentMovements(),
            'low_stock_products' => $this->getLowStockProducts(),
        ]);
    }

    public function stats()
    {
        return response()->json($this->getStats());
    }

    private function getStats()
    {
        $products = Product::count();
        
        $stockLevels = StockLevel::selectRaw('
            SUM(quantity * products.cost) as total_value,
            SUM(quantity) as total_quantity
        ')
        ->join('products', 'stock_levels.product_id', '=', 'products.id')
        ->first();

        $lowStock = StockLevel::lowStock()->count();
        $outOfStock = StockLevel::outOfStock()->count();

        return [
            'total_products' => $products,
            'total_value' => round($stockLevels->total_value ?? 0, 2),
            'total_quantity' => $stockLevels->total_quantity ?? 0,
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
        ];
    }

    private function getRecentMovements()
    {
        return StockMovement::with(['product', 'warehouse'])
            ->latest()
            ->limit(10)
            ->get();
    }

    private function getLowStockProducts()
    {
        return StockLevel::with(['product', 'warehouse'])
            ->lowStock()
            ->limit(5)
            ->get();
    }
}