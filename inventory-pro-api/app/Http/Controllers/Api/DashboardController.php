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
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    protected InventoryService $inventoryService;
    
    // Cache TTL: 2 minutos para dashboard (datos cambian frecuentemente)
    private const CACHE_TTL = 120;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        $cacheKey = "dashboard:{$tenantId}";
        
        // Intentar obtener de caché
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return response()->json($cached);
        }
        
        // Stats - Total de productos (solo activos, no eliminados)
        $totalProducts = Product::where('tenant_id', $tenantId)
            ->whereNull('deleted_at')
            ->count();
        
        // Valor total del inventario - consulta optimizada
        $totalValue = StockLevel::where('stock_levels.tenant_id', $tenantId)
            ->join('products', 'stock_levels.product_id', '=', 'products.id')
            ->whereNull('products.deleted_at')
            ->sum(DB::raw('stock_levels.quantity * stock_levels.avg_unit_cost'));
        
        // Productos con stock bajo - consulta optimizada
        $stockSummary = DB::table('products')
            ->leftJoin('stock_levels', function($join) use ($tenantId) {
                $join->on('products.id', '=', 'stock_levels.product_id')
                     ->where('stock_levels.tenant_id', '=', $tenantId);
            })
            ->where('products.tenant_id', $tenantId)
            ->whereNull('products.deleted_at')
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                'products.stock_min',
                DB::raw('COALESCE(SUM(stock_levels.quantity), 0) as total_stock')
            )
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.stock_min')
            ->get();
        
        $lowStockCount = 0;
        $outOfStockCount = 0;
        $lowStockProducts = [];
        
        foreach ($stockSummary as $product) {
            if ($product->total_stock <= 0) {
                $outOfStockCount++;
            } elseif ($product->stock_min > 0 && $product->total_stock <= $product->stock_min) {
                $lowStockCount++;
                if (count($lowStockProducts) < 5) {
                    $lowStockProducts[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'stock_quantity' => (int) $product->total_stock,
                        'stock_min' => $product->stock_min,
                    ];
                }
            }
        }
        
        // Recent movements - solo campos necesarios
        $recentMovements = StockMovement::where('stock_movements.tenant_id', $tenantId)
            ->join('products', 'stock_movements.product_id', '=', 'products.id')
            ->select(
                'stock_movements.id',
                'stock_movements.movement_type as type',
                'stock_movements.quantity',
                'stock_movements.created_at',
                'products.name as product_name',
                'products.sku'
            )
            ->orderBy('stock_movements.created_at', 'desc')
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
        
        $result = [
            'stats' => [
                'totalProducts' => $totalProducts,
                'totalValue' => round($totalValue ?? 0, 2),
                'lowStock' => $lowStockCount,
                'outOfStock' => $outOfStockCount,
                'activeTransfers' => $activeTransfers,
                'expiringLots' => $expiringLots,
                'unprocessedEvents' => $unprocessedEvents,
            ],
            'today_summary' => [
                'entries' => (int) $todayEntries,
                'exits' => (int) $todayExits,
            ],
            'recent_movements' => $recentMovements,
            'low_stock_products' => $lowStockProducts,
        ];
        
        // Guardar en caché
        Cache::put($cacheKey, $result, self::CACHE_TTL);
        
        return response()->json($result);
    }
    
    public function stats(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        $cacheKey = "dashboard:stats:{$tenantId}";
        
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return response()->json($cached);
        }
        
        // Movimientos de hoy
        $movementsToday = StockMovement::where('tenant_id', $tenantId)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        
        $result = [
            'products_count' => Product::where('tenant_id', $tenantId)
                ->whereNull('deleted_at')
                ->count(),
            'movements_today' => $movementsToday,
        ];
        
        Cache::put($cacheKey, $result, self::CACHE_TTL);
        
        return response()->json($result);
    }

    /**
     * Estadísticas avanzadas de inventario
     */
    public function advancedStats(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        $cacheKey = "dashboard:advanced:{$tenantId}";
        
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return response()->json($cached);
        }
        
        $stats = $this->inventoryService->getInventoryStats($tenantId);
        
        Cache::put($cacheKey, $stats, self::CACHE_TTL);
        
        return response()->json($stats);
    }

    /**
     * Vista consolidada del Almacén General
     */
    public function generalWarehouse(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        $cacheKey = "dashboard:warehouse:{$tenantId}";
        
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return response()->json($cached);
        }
        
        // Obtener almacenes PRIMERO (solo activos, no eliminados)
        $warehouses = \App\Models\Warehouse::where('tenant_id', $tenantId)
            ->whereNull('deleted_at')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        $activeWarehouseIds = $warehouses->pluck('id');
        
        // Obtener datos de productos
        $products = Product::where('products.tenant_id', $tenantId)
            ->whereNull('products.deleted_at')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                'products.unit_cost',
                'products.stock_min',
                'categories.name as category_name'
            )
            ->get()
            ->keyBy('id');
        
        // Obtener stock por producto y almacén (solo almacenes activos)
        $stockData = StockLevel::where('tenant_id', $tenantId)
            ->whereIn('warehouse_id', $activeWarehouseIds)
            ->select('product_id', 'warehouse_id', 'quantity')
            ->get()
            ->groupBy('product_id');
        
        // Construir el inventario
        $inventory = [];
        $totalValue = 0;
        $lowStockCount = 0;
        
        foreach ($products as $productId => $product) {
            $warehouseStock = [];
            $totalStock = 0;
            
            $productStocks = $stockData->get($productId, collect());
            
            foreach ($warehouses as $warehouse) {
                $stock = $productStocks->firstWhere('warehouse_id', $warehouse->id);
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
                'category' => $product->category_name ?? 'Sin categoría',
                'min_stock' => $product->stock_min,
                'total_stock' => $totalStock,
                'warehouse_stock' => $warehouseStock,
                'unit_cost' => $product->unit_cost,
                'total_value' => $productValue,
            ];
        }
        
        $result = [
            'warehouses' => $warehouses,
            'inventory' => $inventory,
            'total_products' => count($products),
            'total_value' => $totalValue,
            'low_stock_count' => $lowStockCount,
        ];
        
        Cache::put($cacheKey, $result, self::CACHE_TTL);
        
        return response()->json($result);
    }
}
