<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryEvent;
use App\Models\Product;
use App\Models\ProductLot;
use App\Models\StockLevel;
use App\Models\StockMovement;
use App\Models\WarehouseTransfer;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        
        // Stats - Total de productos
        $totalProducts = Product::where('tenant_id', $tenantId)->count();
        
        // Valor total del inventario (suma de cantidad * costo unitario)
        $totalValue = StockLevel::where('stock_levels.tenant_id', $tenantId)
            ->join('products', 'stock_levels.product_id', '=', 'products.id')
            ->sum(DB::raw('stock_levels.quantity * products.unit_cost'));
        
        // Valor en tránsito
        $inTransitValue = StockLevel::where('stock_levels.tenant_id', $tenantId)
            ->where('stock_levels.in_transit_quantity', '>', 0)
            ->join('products', 'stock_levels.product_id', '=', 'products.id')
            ->sum(DB::raw('stock_levels.in_transit_quantity * products.unit_cost'));
        
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
        
        // Transferencias activas
        $activeTransfers = WarehouseTransfer::where('tenant_id', $tenantId)
            ->whereIn('status', [WarehouseTransfer::STATUS_PENDING, WarehouseTransfer::STATUS_IN_TRANSIT])
            ->count();
        
        // Lotes próximos a vencer
        $expiringLots = ProductLot::where('tenant_id', $tenantId)
            ->expiring(30)
            ->count();
        
        // Eventos no procesados
        $unprocessedEvents = InventoryEvent::where('tenant_id', $tenantId)
            ->where('processed', false)
            ->count();
        
        // Resumen de movimientos de hoy
        $todayEntries = StockMovement::where('tenant_id', $tenantId)
            ->whereDate('created_at', now())
            ->entries()
            ->sum('quantity');
            
        $todayExits = StockMovement::where('tenant_id', $tenantId)
            ->whereDate('created_at', now())
            ->exits()
            ->sum('quantity');
        
        return response()->json([
            'stats' => [
                'totalProducts' => $totalProducts,
                'totalValue' => round($totalValue ?? 0, 2),
                'inTransitValue' => round($inTransitValue ?? 0, 2),
                'lowStock' => $lowStockCount,
                'outOfStock' => $outOfStockCount,
                'activeTransfers' => $activeTransfers,
                'expiringLots' => $expiringLots,
                'unprocessedEvents' => $unprocessedEvents,
            ],
            'today_summary' => [
                'entries' => $todayEntries,
                'exits' => $todayExits,
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

    /**
     * Estadísticas avanzadas de inventario
     */
    public function advancedStats(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        
        $stats = $this->inventoryService->getInventoryStats($tenantId);
        
        return response()->json($stats);
    }

    /**
     * Vista consolidada del Almacén General
     */
    public function generalWarehouse(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        
        // Obtener todos los productos con sus niveles de stock
        $products = Product::where('tenant_id', $tenantId)
            ->with(['category', 'stockLevels.warehouse'])
            ->get();
        
        // Obtener todos los almacenes
        $warehouses = \App\Models\Warehouse::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get();
        
        // Construir el inventario general
        $inventory = [];
        $totalValue = 0;
        $lowStockCount = 0;
        
        foreach ($products as $product) {
            $warehouseStock = [];
            $totalStock = 0;
            
            foreach ($warehouses as $warehouse) {
                $stock = $product->stockLevels->where('warehouse_id', $warehouse->id)->first();
                $qty = $stock ? $stock->quantity : 0;
                $warehouseStock[$warehouse->id] = $qty;
                $totalStock += $qty;
            }
            
            $productValue = $totalStock * $product->unit_cost;
            $totalValue += $productValue;
            
            if ($totalStock <= $product->stock_min) {
                $lowStockCount++;
            }
            
            $inventory[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'category_id' => $product->category_id,
                'category' => $product->category ? $product->category->name : 'Sin categoría',
                'min_stock' => $product->stock_min,
                'total_stock' => $totalStock,
                'warehouse_stock' => $warehouseStock,
                'unit_cost' => $product->unit_cost,
                'total_value' => $productValue,
                'image' => $product->image_url,
            ];
        }
        
        return response()->json([
            'warehouses' => $warehouses,
            'inventory' => $inventory,
            'total_products' => count($products),
            'total_value' => $totalValue,
            'low_stock_count' => $lowStockCount,
        ]);
    }
}
