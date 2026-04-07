<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductLot;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ProductLotController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductLot::where('tenant_id', $request->user()->tenant_id)
            ->with(['product', 'warehouse', 'supplier']);

        // Filtros
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('expiring')) {
            $days = $request->expiring;
            $query->expiring($days);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('lot_number', 'like', "%{$search}%");
        }

        $lots = $query->orderBy('expiry_date', 'asc')->paginate($request->per_page ?? 20);

        return response()->json($lots);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'lot_number' => 'required|string|max:50',
            'initial_quantity' => 'required|numeric|min:0.0001',
            'unit_cost' => 'required|numeric|min:0',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:manufacturing_date',
            'received_date' => 'nullable|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_order_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $warehouse = Warehouse::findOrFail($validated['warehouse_id']);

        // Verificar pertenencia al tenant
        if ($product->tenant_id !== $request->user()->tenant_id ||
            $warehouse->tenant_id !== $request->user()->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $lot = ProductLot::create([
            'tenant_id' => $request->user()->tenant_id,
            'product_id' => $validated['product_id'],
            'warehouse_id' => $validated['warehouse_id'],
            'lot_number' => $validated['lot_number'],
            'initial_quantity' => $validated['initial_quantity'],
            'remaining_quantity' => $validated['initial_quantity'],
            'unit_cost' => $validated['unit_cost'],
            'manufacturing_date' => $validated['manufacturing_date'] ?? null,
            'expiry_date' => $validated['expiry_date'] ?? null,
            'received_date' => $validated['received_date'] ?? now(),
            'supplier_id' => $validated['supplier_id'] ?? null,
            'purchase_order_number' => $validated['purchase_order_number'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Lote creado exitosamente',
            'data' => $lot->load(['product', 'warehouse']),
        ], 201);
    }

    public function show(Request $request, string $id)
    {
        $lot = ProductLot::where('tenant_id', $request->user()->tenant_id)
            ->with(['product', 'warehouse', 'supplier', 'stockMovements'])
            ->findOrFail($id);

        return response()->json($lot);
    }

    public function update(Request $request, string $id)
    {
        $lot = ProductLot::where('tenant_id', $request->user()->tenant_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'status' => 'nullable|in:active,depleted,expired,quarantine',
            'notes' => 'nullable|string',
        ]);

        $lot->update($validated);

        return response()->json([
            'message' => 'Lote actualizado exitosamente',
            'data' => $lot->fresh(),
        ]);
    }

    public function getAvailableLots(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
        ]);

        $query = ProductLot::where('tenant_id', $request->user()->tenant_id)
            ->where('product_id', $request->product_id)
            ->where('status', 'active')
            ->where('remaining_quantity', '>', 0);

        if ($request->has('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        $lots = $query->orderBy('received_date', 'asc')->get();

        return response()->json($lots);
    }

    public function getExpiringLots(Request $request)
    {
        $days = $request->days ?? 30;

        $lots = ProductLot::where('tenant_id', $request->user()->tenant_id)
            ->expiring($days)
            ->with(['product', 'warehouse'])
            ->get();

        return response()->json([
            'days' => $days,
            'count' => $lots->count(),
            'lots' => $lots,
            'total_value' => $lots->sum('total_value'),
        ]);
    }

    public function getLotStats(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $stats = [
            'total_lots' => ProductLot::where('tenant_id', $tenantId)->count(),
            'active_lots' => ProductLot::where('tenant_id', $tenantId)->where('status', 'active')->count(),
            'depleted_lots' => ProductLot::where('tenant_id', $tenantId)->where('status', 'depleted')->count(),
            'expired_lots' => ProductLot::where('tenant_id', $tenantId)->where('status', 'expired')->count(),
            'expiring_soon' => ProductLot::where('tenant_id', $tenantId)->expiring(30)->count(),
            'total_value' => ProductLot::where('tenant_id', $tenantId)
                ->where('status', 'active')
                ->selectRaw('SUM(remaining_quantity * unit_cost) as total')
                ->value('total') ?? 0,
        ];

        return response()->json($stats);
    }
}
