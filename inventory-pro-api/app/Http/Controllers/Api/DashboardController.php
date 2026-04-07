<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        
        // Stats
        $totalProducts = Product::where('tenant_id', $tenantId)->count();
        $totalValue = Product::where('tenant_id', $tenantId)->sum('unit_cost');
        $lowStock = Product::where('tenant_id', $tenantId)
            ->whereColumn('stock_quantity', '<=', 'min_stock')
            ->count();
        $outOfStock = Product::where('tenant_id', $tenantId)
            ->where('stock_quantity', 0)
            ->count();
        
        // Recent movements
        $recentMovements = StockMovement::where('tenant_id', $tenantId)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Low stock products
        $lowStockProducts = Product::where('tenant_id', $tenantId)
            ->whereColumn('stock_quantity', '<=', 'min_stock')
            ->limit(5)
            ->get();
        
        return response()->json([
            'stats' => [
                'totalProducts' => $totalProducts,
                'totalValue' => $totalValue,
                'lowStock' => $lowStock,
                'outOfStock' => $outOfStock,
            ],
            'recent_movements' => $recentMovements,
            'low_stock_products' => $lowStockProducts,
        ]);
    }
    
    public function stats(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        
        return response()->json([
            'products_count' => Product::where('tenant_id', $tenantId)->count(),
            'movements_today' => StockMovement::where('tenant_id', $tenantId)
                ->whereDate('created_at', today())
                ->count(),
        ]);
    }
}
