<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\WarehouseTransfer;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WarehouseTransferController extends Controller
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request)
    {
        $query = WarehouseTransfer::where('tenant_id', $request->user()->tenant_id)
            ->with(['sourceWarehouse', 'destinationWarehouse', 'creator', 'items.product']);

        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('warehouse_id')) {
            $warehouseId = $request->warehouse_id;
            $query->where(function ($q) use ($warehouseId) {
                $q->where('source_warehouse_id', $warehouseId)
                  ->orWhere('destination_warehouse_id', $warehouseId);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('transfer_number', 'like', "%{$search}%");
        }

        $transfers = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 20);

        return response()->json($transfers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'source_warehouse_id' => 'required|exists:warehouses,id',
            'destination_warehouse_id' => 'required|exists:warehouses,id|different:source_warehouse_id',
            'transfer_date' => 'required|date',
            'expected_arrival_date' => 'nullable|date|after_or_equal:transfer_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.lot_id' => 'nullable|exists:product_lots,id',
            'items.*.notes' => 'nullable|string',
        ]);

        $sourceWarehouse = Warehouse::findOrFail($validated['source_warehouse_id']);
        $destinationWarehouse = Warehouse::findOrFail($validated['destination_warehouse_id']);

        // Verificar que los almacenes pertenezcan al tenant
        if ($sourceWarehouse->tenant_id !== $request->user()->tenant_id ||
            $destinationWarehouse->tenant_id !== $request->user()->tenant_id) {
            return response()->json(['message' => 'Almacén no válido'], 403);
        }

        try {
            $transfer = $this->inventoryService->createTransfer(
                $sourceWarehouse,
                $destinationWarehouse,
                $validated['items'],
                [
                    'transfer_date' => $validated['transfer_date'],
                    'expected_arrival_date' => $validated['expected_arrival_date'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'user_id' => $request->user()->id,
                ]
            );

            return response()->json([
                'message' => 'Transferencia creada exitosamente',
                'data' => $transfer->load(['sourceWarehouse', 'destinationWarehouse', 'items.product']),
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function show(Request $request, string $id)
    {
        $transfer = WarehouseTransfer::where('tenant_id', $request->user()->tenant_id)
            ->with([
                'sourceWarehouse', 
                'destinationWarehouse', 
                'creator', 
                'sender', 
                'receiver',
                'items.product',
                'items.productLot'
            ])
            ->findOrFail($id);

        return response()->json($transfer);
    }

    public function send(Request $request, string $id)
    {
        $transfer = WarehouseTransfer::where('tenant_id', $request->user()->tenant_id)
            ->findOrFail($id);

        try {
            $transfer = $this->inventoryService->sendTransfer($transfer, [
                'user_id' => $request->user()->id,
            ]);

            return response()->json([
                'message' => 'Transferencia enviada exitosamente',
                'data' => $transfer,
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function receive(Request $request, string $id)
    {
        $transfer = WarehouseTransfer::where('tenant_id', $request->user()->tenant_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'items' => 'nullable|array',
            'items.*.item_id' => 'required_with:items|exists:warehouse_transfer_items,id',
            'items.*.quantity_received' => 'required_with:items|numeric|min:0',
            'items.*.status' => 'nullable|in:received,partially_received,damaged,missing',
            'items.*.notes' => 'nullable|string',
            'rejection_reason' => 'nullable|string',
        ]);

        try {
            $transfer = $this->inventoryService->receiveTransfer(
                $transfer,
                $validated['items'] ?? [],
                [
                    'user_id' => $request->user()->id,
                    'rejection_reason' => $validated['rejection_reason'] ?? null,
                ]
            );

            return response()->json([
                'message' => 'Transferencia recibida exitosamente',
                'data' => $transfer,
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function cancel(Request $request, string $id)
    {
        $transfer = WarehouseTransfer::where('tenant_id', $request->user()->tenant_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'reason' => 'required|string|min:5',
        ]);

        try {
            $transfer = $this->inventoryService->cancelTransfer(
                $transfer,
                $validated['reason'],
                ['user_id' => $request->user()->id]
            );

            return response()->json([
                'message' => 'Transferencia cancelada exitosamente',
                'data' => $transfer,
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function stats(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $stats = [
            'by_status' => WarehouseTransfer::where('tenant_id', $tenantId)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status'),
            'pending_sent' => WarehouseTransfer::where('tenant_id', $tenantId)
                ->where('source_warehouse_id', $request->warehouse_id)
                ->whereIn('status', ['pending', 'preparing'])
                ->count(),
            'pending_received' => WarehouseTransfer::where('tenant_id', $tenantId)
                ->where('destination_warehouse_id', $request->warehouse_id)
                ->whereIn('status', ['in_transit', 'partially_received'])
                ->count(),
        ];

        return response()->json($stats);
    }
}
