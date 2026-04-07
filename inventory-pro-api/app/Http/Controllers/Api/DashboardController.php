<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockLevel;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        
        // Stats - Total de productos
        $totalProducts = Product::where('tenant_id', $tenantId)->count();
        
        // Valor total del inventario (suma de cantidad * costo unitario)
        $totalValue = StockLevel::where('stock_levels.tenant_id', $tenantId)
            ->join('products', 'stock_levels.product_id', '=', 'products.id')
            ->sum(DB::raw('stock_levels.quantity * products.unit_cost'));
        
        // Productos con stock bajo - consulta simplificada para SQLite
        $productsWithStock = Product::where('tenant_id', $tenantId)
            ->with(['stockLevels', 'category'])
            ->get();
        
        $lowStockProducts = [];
        $lowStockCount = 0;
        $outOfStockCount = 0;
        
        foreach ($productsWithStock as $product) {
            $totalStock = $product->stockLevels->sum('quantity');
            
            if ($totalStock <= 0) {
                $outOfStockCount++;
            } elseif ($totalStock <= $product->stock_min) {
                $lowStockCount++;
                if (count($lowStockProducts) < 5) {
                    $lowStockProducts[] = $product;
                }
            }
        }
        
        // Recent movements
        $recentMovements = StockMovement::where('tenant_id', $tenantId)
            ->with(['product', 'warehouse'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json([
            'stats' => [
                'totalProducts' => $totalProducts,
                'totalValue' => round($totalValue ?? 0, 2),
                'lowStock' => $lowStockCount,
                'outOfStock' => $outOfStockCount,
            ],
            'recent_movements' => $recentMovements,
            'low_stock_products' => $lowStockProducts,
        ]);
    }
    
    public function stats(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        
        // Movimientos de hoy
        $movementsToday = StockMovement::where('tenant_id', $tenantId)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        
        return response()->json([
            'products_count' => Product::where('tenant_id', $tenantId)->count(),
            'movements_today' => $movementsToday,
        ]);
    }
}
