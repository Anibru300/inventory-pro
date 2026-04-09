<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockLevel;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockMovementController extends Controller
{
    /**
     * List stock movements
     */
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'warehouse', 'creator'])
            ->when($request->product_id, function ($q, $productId) {
                $q->where('product_id', $productId);
            })
            ->when($request->warehouse_id, function ($q, $warehouseId) {
                $q->where('warehouse_id', $warehouseId);
            })
            ->when($request->movement_type, function ($q, $type) {
                $q->where('movement_type', $type);
            })
            ->when($request->date_from, function ($q, $date) {
                $q->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($q, $date) {
                $q->whereDate('created_at', '<=', $date);
            })
            ->latest();

        $movements = $query->paginate($request->per_page ?? 25);

        return response()->json($movements);
    }

    /**
     * Create stock movement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'location_id' => 'nullable|exists:locations,id',
            'movement_type' => 'required|in:' . implode(',', [
                StockMovement::TYPE_PURCHASE,
                StockMovement::TYPE_RETURN_CUSTOMER,
                StockMovement::TYPE_ADJUSTMENT_POSITIVE,
                StockMovement::TYPE_TRANSFER_IN,
                StockMovement::TYPE_SALE,
                StockMovement::TYPE_RETURN_SUPPLIER,
                StockMovement::TYPE_ADJUSTMENT_NEGATIVE,
                StockMovement::TYPE_TRANSFER_OUT,
                StockMovement::TYPE_WASTE,
            ]),
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'nullable|numeric|min:0',
            'reference_type' => 'nullable|string',
            'reference_id' => 'nullable|string',
            'reference_number' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'lot_number' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $isEntry = in_array($validated['movement_type'], StockMovement::getEntryTypes());
        $quantity = $isEntry ? $validated['quantity'] : -$validated['quantity'];

        DB::beginTransaction();

        try {
            // Get or create stock level
            $stockLevel = StockLevel::firstOrCreate(
                [
                    'tenant_id' => Auth::user()->tenant_id,
                    'product_id' => $validated['product_id'],
                    'warehouse_id' => $validated['warehouse_id'],
                    'location_id' => $validated['location_id'],
                ],
                ['quantity' => 0]
            );

            // Check if exit would result in negative stock
            if (! $isEntry && $stockLevel->quantity + $quantity < 0) {
                if (! Auth::user()->tenant->settings['allow_negative_stock'] ?? false) {
                    return response()->json([
                        'message' => 'Insufficient stock. Available: ' . $stockLevel->quantity,
                    ], 422);
                }
            }

            // Calculate running balance
            $runningBalance = $stockLevel->quantity + $quantity;

            // Create movement
            $movement = StockMovement::create([
                ...$validated,
                'quantity' => $quantity,
                'running_balance' => $runningBalance,
                'created_by' => Auth::id(),
            ]);

            // Update stock level
            $stockLevel->update([
                'quantity' => $runningBalance,
                'avg_unit_cost' => $this->calculateAvgCost($stockLevel, $movement),
                'last_movement_at' => now(),
                'last_movement_type' => $validated['movement_type'],
            ]);

            // Check if alert should be created
            $this->checkStockAlert($product, $stockLevel);

            // Generate receipt/vale automatically for entries and exits
            $receipt = null;
            if (in_array($validated['movement_type'], [
                StockMovement::TYPE_PURCHASE,
                StockMovement::TYPE_SALE,
                StockMovement::TYPE_RETURN_CUSTOMER,
                StockMovement::TYPE_RETURN_SUPPLIER,
                StockMovement::TYPE_TRANSFER_IN,
                StockMovement::TYPE_TRANSFER_OUT,
            ])) {
                $type = in_array($validated['movement_type'], StockMovement::getEntryTypes()) ? 'entry' : 'exit';
                $receipt = ReceiptController::createFromMovement($movement, [
                    'recipient_name' => $request->input('recipient_name'),
                    'recipient_department' => $request->input('recipient_department'),
                ]);
            }

            DB::commit();

            $response = [
                'message' => 'Stock movement created successfully',
                'movement' => $movement->load(['product', 'warehouse']),
                'stock_level' => $stockLevel,
            ];

            if ($receipt) {
                $response['receipt'] = [
                    'id' => $receipt->id,
                    'folio' => $receipt->folio,
                    'type' => $receipt->type,
                    'download_url' => "/api/receipts/{$receipt->id}/pdf",
                    'preview_url' => "/api/receipts/{$receipt->id}/preview",
                ];
            }

            return response()->json($response, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating movement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get movements summary
     */
    public function summary(Request $request)
    {
        try {
            // Usar el tenant_id del usuario autenticado
            $tenantId = Auth::user()?->tenant_id;
            
            if (!$tenantId) {
                return response()->json([
                    'message' => 'Usuario no tiene tenant asignado',
                    'entries' => 0, 'exits' => 0, 'entryUnits' => 0, 
                    'exitUnits' => 0, 'today_count' => 0, 'month_count' => 0, 'balance' => 0
                ], 200);
            }
            
            $today = now()->startOfDay();
            
            // Get entry and exit totals for current month
            $entries = StockMovement::where('tenant_id', $tenantId)
                ->whereIn('movement_type', StockMovement::getEntryTypes())
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('quantity');
                
            $exits = StockMovement::where('tenant_id', $tenantId)
                ->whereIn('movement_type', StockMovement::getExitTypes())
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('quantity');
            
            // Get today's movements count
            $todayCount = StockMovement::where('tenant_id', $tenantId)
                ->whereDate('created_at', $today)
                ->count();
                
            // Get this month's count
            $monthCount = StockMovement::where('tenant_id', $tenantId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            // Calculate totals (entry units are positive, exit units are negative in DB)
            $entryUnits = abs((int) $entries);
            $exitUnits = abs((int) $exits);
            
            return response()->json([
                'entries' => $monthCount,
                'exits' => $monthCount,
                'entryUnits' => $entryUnits,
                'exitUnits' => $exitUnits,
                'today_count' => $todayCount,
                'month_count' => $monthCount,
                'balance' => $entryUnits - $exitUnits,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en StockMovementController@summary: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Error al cargar resumen: ' . $e->getMessage(),
                'entries' => 0, 'exits' => 0, 'entryUnits' => 0, 
                'exitUnits' => 0, 'today_count' => 0, 'month_count' => 0, 'balance' => 0
            ], 500);
        }
    }

    /**
     * Get product kardex
     */
    public function kardex(Request $request, Product $product)
    {
        $movements = StockMovement::with(['warehouse', 'creator'])
            ->where('product_id', $product->id)
            ->when($request->warehouse_id, function ($q, $warehouseId) {
                $q->where('warehouse_id', $warehouseId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json($movements);
    }

    /**
     * Calculate average cost
     */
    private function calculateAvgCost(StockLevel $stockLevel, StockMovement $movement): float
    {
        if ($movement->quantity <= 0 || ! $movement->unit_cost) {
            return $stockLevel->avg_unit_cost;
        }

        $currentQty = $stockLevel->quantity - $movement->quantity;
        $currentValue = $currentQty * $stockLevel->avg_unit_cost;
        $newValue = $movement->quantity * $movement->unit_cost;
        $totalQty = $stockLevel->quantity;

        if ($totalQty <= 0) {
            return $movement->unit_cost;
        }

        return ($currentValue + $newValue) / $totalQty;
    }

    /**
     * Check and create stock alert
     */
    private function checkStockAlert(Product $product, StockLevel $stockLevel): void
    {
        if ($stockLevel->quantity <= 0) {
            // Create out of stock alert
            \App\Models\Alert::create([
                'tenant_id' => $product->tenant_id,
                'alert_type' => 'out_of_stock',
                'severity' => 'critical',
                'product_id' => $product->id,
                'warehouse_id' => $stockLevel->warehouse_id,
                'title' => 'Producto agotado',
                'message' => "{$product->name} se ha agotado en {$stockLevel->warehouse->name}",
            ]);
        } elseif ($stockLevel->quantity <= $product->stock_min) {
            // Create low stock alert
            \App\Models\Alert::create([
                'tenant_id' => $product->tenant_id,
                'alert_type' => 'low_stock',
                'severity' => 'warning',
                'product_id' => $product->id,
                'warehouse_id' => $stockLevel->warehouse_id,
                'title' => 'Stock bajo',
                'message' => "{$product->name} tiene {$stockLevel->quantity} unidades (mínimo: {$product->stock_min})",
            ]);
        }
    }
}