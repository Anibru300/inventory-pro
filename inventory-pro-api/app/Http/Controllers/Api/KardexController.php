<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\InventoryCostingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KardexController extends Controller
{
    protected InventoryCostingService $costingService;

    public function __construct(InventoryCostingService $costingService)
    {
        $this->costingService = $costingService;
    }

    /**
     * Reporte Kardex detallado por producto
     */
    public function getKardex(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $product = Product::where('tenant_id', $request->user()->tenant_id)
            ->findOrFail($validated['product_id']);

        $query = DB::table('stock_movements')
            ->where('stock_movements.tenant_id', $request->user()->tenant_id)
            ->where('stock_movements.product_id', $validated['product_id'])
            ->leftJoin('warehouses', 'stock_movements.warehouse_id', '=', 'warehouses.id')
            ->leftJoin('product_lots', 'stock_movements.product_lot_id', '=', 'product_lots.id')
            ->leftJoin('users', 'stock_movements.created_by', '=', 'users.id')
            ->select([
                'stock_movements.id',
                'stock_movements.created_at',
                'stock_movements.movement_type',
                'stock_movements.quantity',
                'stock_movements.unit_cost',
                'stock_movements.balance_before',
                'stock_movements.running_balance',
                'stock_movements.reference_number',
                'stock_movements.notes',
                'warehouses.name as warehouse_name',
                'product_lots.lot_number',
                'users.name as created_by_name',
            ]);

        if (!empty($validated['warehouse_id'])) {
            $query->where('stock_movements.warehouse_id', $validated['warehouse_id']);
        }

        if (!empty($validated['start_date'])) {
            $query->whereDate('stock_movements.created_at', '>=', $validated['start_date']);
        }

        if (!empty($validated['end_date'])) {
            $query->whereDate('stock_movements.created_at', '<=', $validated['end_date']);
        }

        $movements = $query->orderBy('stock_movements.created_at', 'asc')
            ->orderBy('stock_movements.id', 'asc')
            ->paginate($request->per_page ?? 50);

        // Calcular saldo inicial
        $initialBalance = 0;
        if (!empty($validated['start_date'])) {
            $initialMovement = DB::table('stock_movements')
                ->where('tenant_id', $request->user()->tenant_id)
                ->where('product_id', $validated['product_id'])
                ->whereDate('created_at', '<', $validated['start_date'])
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->first();
            
            $initialBalance = $initialMovement?->running_balance ?? 0;
        }

        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'unit_of_measure' => $product->unit_of_measure,
                'valuation_method' => $product->valuation_method ?? 'FIFO',
            ],
            'period' => [
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
            ],
            'initial_balance' => $initialBalance,
            'final_balance' => $movements->last()?->running_balance ?? $initialBalance,
            'movements' => $movements,
        ]);
    }

    /**
     * Valuación de inventario por método de costeo
     */
    public function getValuation(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $query = Product::where('tenant_id', $request->user()->tenant_id)
            ->where('is_active', true);

        if (!empty($validated['category_id'])) {
            $query->where('category_id', $validated['category_id']);
        }

        $products = $query->get();
        $valuations = [];

        foreach ($products as $product) {
            $valuation = $this->costingService->getInventoryValuation($product);
            
            // Filtrar por almacén si se especifica
            if (!empty($validated['warehouse_id'])) {
                $stockLevel = $product->stockLevels()
                    ->where('warehouse_id', $validated['warehouse_id'])
                    ->first();
                
                if ($stockLevel && $stockLevel->quantity > 0) {
                    $ratio = $stockLevel->quantity / ($product->total_stock ?: 1);
                    $valuation['total_quantity'] = $stockLevel->quantity;
                    $valuation['fifo_value'] *= $ratio;
                    $valuation['lifo_value'] *= $ratio;
                    $valuation['average_value'] *= $ratio;
                    $valuation['specific_value'] *= $ratio;
                    $valuations[] = $valuation;
                }
            } else {
                if ($valuation['total_quantity'] > 0) {
                    $valuations[] = $valuation;
                }
            }
        }

        $totals = [
            'fifo_total' => collect($valuations)->sum('fifo_value'),
            'lifo_total' => collect($valuations)->sum('lifo_value'),
            'average_total' => collect($valuations)->sum('average_value'),
            'specific_total' => collect($valuations)->sum('specific_value'),
            'total_quantity' => collect($valuations)->sum('total_quantity'),
        ];

        return response()->json([
            'filters' => $validated,
            'totals' => $totals,
            'products' => $valuations,
        ]);
    }

    /**
     * Reporte de movimientos por tipo
     */
    public function getMovementsByType(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'warehouse_id' => 'nullable|exists:warehouses,id',
        ]);

        $query = DB::table('stock_movements')
            ->where('tenant_id', $request->user()->tenant_id)
            ->whereBetween('created_at', [$validated['start_date'], $validated['end_date']]);

        if (!empty($validated['warehouse_id'])) {
            $query->where('warehouse_id', $validated['warehouse_id']);
        }

        $movementsByType = $query->selectRaw('
                movement_type,
                COUNT(*) as count,
                SUM(quantity) as total_quantity,
                SUM(quantity * unit_cost) as total_value
            ')
            ->groupBy('movement_type')
            ->get();

        $entries = $movementsByType->filter(fn($m) => in_array($m->movement_type, [
            'entrada_compra', 'entrada_devolucion_cliente', 'entrada_ajuste', 'entrada_transferencia'
        ]));

        $exits = $movementsByType->filter(fn($m) => in_array($m->movement_type, [
            'salida_venta', 'salida_devolucion_proveedor', 'salida_ajuste', 'salida_transferencia', 'salida_merma'
        ]));

        return response()->json([
            'period' => [
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ],
            'summary' => [
                'total_movements' => $movementsByType->sum('count'),
                'total_entries_quantity' => $entries->sum('total_quantity'),
                'total_entries_value' => $entries->sum('total_value'),
                'total_exits_quantity' => $exits->sum('total_quantity'),
                'total_exits_value' => $exits->sum('total_value'),
                'net_quantity' => $entries->sum('total_quantity') - $exits->sum('total_quantity'),
                'net_value' => $entries->sum('total_value') - $exits->sum('total_value'),
            ],
            'by_type' => $movementsByType,
        ]);
    }

    /**
     * Rotación de inventario (Inventory Turnover)
     */
    public function getInventoryTurnover(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $tenantId = $request->user()->tenant_id;

        // Costo de bienes vendidos (COGS)
        $cogs = DB::table('stock_movements')
            ->where('tenant_id', $tenantId)
            ->where('movement_type', 'salida_venta')
            ->whereBetween('created_at', [$validated['start_date'], $validated['end_date']])
            ->sum(DB::raw('quantity * unit_cost'));

        // Inventario promedio
        $averageInventory = DB::table('stock_levels')
            ->where('tenant_id', $tenantId)
            ->join('products', 'stock_levels.product_id', '=', 'products.id')
            ->where('stock_levels.quantity', '>', 0)
            ->avg(DB::raw('stock_levels.quantity * products.unit_cost'));

        $turnover = $averageInventory > 0 ? $cogs / $averageInventory : 0;
        $daysInventory = $turnover > 0 ? 365 / $turnover : 0;

        return response()->json([
            'period' => $validated,
            'cogs' => round($cogs, 2),
            'average_inventory' => round($averageInventory, 2),
            'inventory_turnover' => round($turnover, 2),
            'days_inventory_outstanding' => round($daysInventory, 2),
        ]);
    }
}
