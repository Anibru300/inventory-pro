<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\StockLevel;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        
        $query = PurchaseOrder::byTenant($tenantId)
            ->with(['supplier', 'warehouse', 'creator', 'items.product']);

        // Filter by status
        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        // Filter by supplier
        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->where('order_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('order_date', '<=', $request->date_to);
        }

        // Search by order number
        if ($request->has('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|uuid|exists:suppliers,id',
            'warehouse_id' => 'required|uuid|exists:warehouses,id',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'shipping_cost' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|uuid|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            // Generate order number
            $orderNumber = $this->generateOrderNumber($request->user()->tenant_id);

            $order = PurchaseOrder::create([
                'tenant_id' => $request->user()->tenant_id,
                'created_by' => $request->user()->id,
                'order_number' => $orderNumber,
                'supplier_id' => $validated['supplier_id'],
                'warehouse_id' => $validated['warehouse_id'],
                'order_date' => $validated['order_date'],
                'expected_date' => $validated['expected_date'] ?? null,
                'status' => PurchaseOrder::STATUS_DRAFT,
                'notes' => $validated['notes'] ?? null,
                'terms' => $validated['terms'] ?? null,
                'tax_rate' => $validated['tax_rate'] ?? 0,
                'shipping_cost' => $validated['shipping_cost'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'reference' => $validated['reference'] ?? null,
            ]);

            // Create items
            foreach ($validated['items'] as $itemData) {
                $order->items()->create([
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_cost' => $itemData['unit_cost'],
                    'notes' => $itemData['notes'] ?? null,
                ]);
            }

            // Calculate totals
            $order->calculateTotals();

            // Add status history
            $order->addStatusHistory('', PurchaseOrder::STATUS_DRAFT, 'Orden creada');

            DB::commit();

            return response()->json([
                'message' => 'Orden de compra creada exitosamente',
                'order' => $order->load(['supplier', 'warehouse', 'items.product']),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear la orden: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $tenantId = $request->user()->tenant_id;
        
        $order = PurchaseOrder::byTenant($tenantId)
            ->with(['supplier', 'warehouse', 'creator', 'items.product', 'statusHistory.user'])
            ->findOrFail($id);

        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $tenantId = $request->user()->tenant_id;
        $order = PurchaseOrder::byTenant($tenantId)->findOrFail($id);

        if (!$order->canEdit()) {
            return response()->json([
                'message' => 'No se puede editar una orden que ya ha sido enviada o recibida',
            ], 422);
        }

        $validated = $request->validate([
            'supplier_id' => 'sometimes|uuid|exists:suppliers,id',
            'warehouse_id' => 'sometimes|uuid|exists:warehouses,id',
            'order_date' => 'sometimes|date',
            'expected_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'shipping_cost' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'reference' => 'nullable|string',
            'items' => 'sometimes|array|min:1',
            'items.*.id' => 'nullable|uuid',
            'items.*.product_id' => 'required|uuid|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            $order->update([
                'supplier_id' => $validated['supplier_id'] ?? $order->supplier_id,
                'warehouse_id' => $validated['warehouse_id'] ?? $order->warehouse_id,
                'order_date' => $validated['order_date'] ?? $order->order_date,
                'expected_date' => $validated['expected_date'] ?? $order->expected_date,
                'notes' => $validated['notes'] ?? $order->notes,
                'terms' => $validated['terms'] ?? $order->terms,
                'tax_rate' => $validated['tax_rate'] ?? $order->tax_rate,
                'shipping_cost' => $validated['shipping_cost'] ?? $order->shipping_cost,
                'discount' => $validated['discount'] ?? $order->discount,
                'reference' => $validated['reference'] ?? $order->reference,
            ]);

            // Update items if provided
            if (isset($validated['items'])) {
                // Delete existing items
                $order->items()->delete();
                
                // Create new items
                foreach ($validated['items'] as $itemData) {
                    $order->items()->create([
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'unit_cost' => $itemData['unit_cost'],
                        'notes' => $itemData['notes'] ?? null,
                    ]);
                }
            }

            $order->calculateTotals();

            DB::commit();

            return response()->json([
                'message' => 'Orden de compra actualizada exitosamente',
                'order' => $order->load(['supplier', 'warehouse', 'items.product']),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualizar la orden: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $tenantId = $request->user()->tenant_id;
        $order = PurchaseOrder::byTenant($tenantId)->findOrFail($id);

        if (!$order->canEdit()) {
            return response()->json([
                'message' => 'No se puede eliminar una orden que ya ha sido enviada o recibida',
            ], 422);
        }

        $order->delete();

        return response()->json([
            'message' => 'Orden de compra eliminada exitosamente',
        ]);
    }

    public function send(Request $request, $id)
    {
        $tenantId = $request->user()->tenant_id;
        $order = PurchaseOrder::byTenant($tenantId)->findOrFail($id);

        if ($order->status !== PurchaseOrder::STATUS_DRAFT) {
            return response()->json([
                'message' => 'Solo se pueden enviar órdenes en estado borrador',
            ], 422);
        }

        $oldStatus = $order->status;
        $order->status = PurchaseOrder::STATUS_SENT;
        $order->save();

        $order->addStatusHistory($oldStatus, $order->status, 'Orden enviada al proveedor');

        return response()->json([
            'message' => 'Orden enviada exitosamente',
            'order' => $order->load(['supplier', 'warehouse', 'items.product']),
        ]);
    }

    public function receive(Request $request, $id)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|uuid|exists:purchase_order_items,id',
            'items.*.quantity_received' => 'required|numeric|min:0.001',
            'notes' => 'nullable|string',
        ]);

        $tenantId = $request->user()->tenant_id;
        $order = PurchaseOrder::byTenant($tenantId)
            ->with('items')
            ->findOrFail($id);

        if (!$order->canReceive()) {
            return response()->json([
                'message' => 'No se puede recibir esta orden',
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            $oldStatus = $order->status;
            
            // Update received quantities
            $order->updateReceivedQuantities($validated['items']);

            // Create stock movements and update stock levels
            foreach ($validated['items'] as $itemData) {
                $item = $order->items()->find($itemData['id']);
                
                if ($item) {
                    // Create stock movement
                    StockMovement::create([
                        'tenant_id' => $tenantId,
                        'product_id' => $item->product_id,
                        'warehouse_id' => $order->warehouse_id,
                        'type' => 'entry',
                        'quantity' => $itemData['quantity_received'],
                        'unit_cost' => $item->unit_cost,
                        'reason' => 'Recepción OC: ' . $order->order_number,
                        'reference_id' => $order->id,
                        'reference_type' => 'purchase_order',
                        'created_by' => $request->user()->id,
                    ]);

                    // Update or create stock level
                    $stockLevel = StockLevel::firstOrNew([
                        'tenant_id' => $tenantId,
                        'product_id' => $item->product_id,
                        'warehouse_id' => $order->warehouse_id,
                    ]);
                    
                    $stockLevel->quantity = ($stockLevel->quantity ?? 0) + $itemData['quantity_received'];
                    $stockLevel->save();

                    // Update product unit cost if needed
                    $product = Product::find($item->product_id);
                    if ($product && $item->unit_cost > 0) {
                        $product->unit_cost = $item->unit_cost;
                        $product->save();
                    }
                }
            }

            $order->addStatusHistory($oldStatus, $order->status, $validated['notes'] ?? 'Recepción de mercancía');

            DB::commit();

            return response()->json([
                'message' => 'Recepción procesada exitosamente',
                'order' => $order->load(['supplier', 'warehouse', 'items.product']),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al procesar la recepción: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function cancel(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string',
        ]);

        $tenantId = $request->user()->tenant_id;
        $order = PurchaseOrder::byTenant($tenantId)->findOrFail($id);

        if ($order->status === PurchaseOrder::STATUS_RECEIVED) {
            return response()->json([
                'message' => 'No se puede cancelar una orden ya recibida',
            ], 422);
        }

        $oldStatus = $order->status;
        $order->status = PurchaseOrder::STATUS_CANCELLED;
        $order->save();

        $order->addStatusHistory($oldStatus, $order->status, $validated['reason']);

        return response()->json([
            'message' => 'Orden cancelada exitosamente',
            'order' => $order,
        ]);
    }

    public function stats(Request $request)
    {
        $tenantId = $request->user()->tenant_id;

        $stats = [
            'total' => PurchaseOrder::byTenant($tenantId)->count(),
            'draft' => PurchaseOrder::byTenant($tenantId)->byStatus(PurchaseOrder::STATUS_DRAFT)->count(),
            'sent' => PurchaseOrder::byTenant($tenantId)->byStatus(PurchaseOrder::STATUS_SENT)->count(),
            'partial' => PurchaseOrder::byTenant($tenantId)->byStatus(PurchaseOrder::STATUS_PARTIAL)->count(),
            'received' => PurchaseOrder::byTenant($tenantId)->byStatus(PurchaseOrder::STATUS_RECEIVED)->count(),
            'pending_value' => PurchaseOrder::byTenant($tenantId)->pending()->sum('total'),
        ];

        return response()->json($stats);
    }

    private function generateOrderNumber($tenantId): string
    {
        $prefix = 'OC-';
        $year = date('Y');
        $count = PurchaseOrder::byTenant($tenantId)
            ->whereYear('created_at', date('Y'))
            ->count() + 1;
        
        return $prefix . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
