<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductLot;
use App\Models\StockLevel;
use App\Models\StockMovement;
use App\Models\StockReservation;
use App\Models\Warehouse;
use App\Models\WarehouseTransfer;
use App\Models\WarehouseTransferItem;
use App\Models\InventoryEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Servicio principal de gestión de inventario
 * Implementa operaciones transaccionales con ACID compliance
 * Según especificaciones del documento técnico ERP
 */
class InventoryService
{
    protected InventoryCostingService $costingService;

    public function __construct(InventoryCostingService $costingService)
    {
        $this->costingService = $costingService;
    }

    // ===========================================
    // OPERACIONES BÁSICAS DE STOCK
    // ===========================================

    /**
     * Registrar entrada de inventario
     */
    public function registerEntry(
        Product $product,
        Warehouse $warehouse,
        float $quantity,
        float $unitCost,
        string $movementType,
        array $options = []
    ): StockMovement {
        return DB::transaction(function () use ($product, $warehouse, $quantity, $unitCost, $movementType, $options) {
            // Validar tipo de movimiento
            if (!in_array($movementType, StockMovement::getEntryTypes())) {
                throw new \InvalidArgumentException('Tipo de movimiento no válido para entrada');
            }

            // Obtener stock level actual con lock
            $stockLevel = StockLevel::lockForUpdate()
                ->firstOrCreate(
                    [
                        'tenant_id' => $product->tenant_id,
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouse->id,
                    ],
                    [
                        'quantity' => 0,
                        'reserved_quantity' => 0,
                        'in_transit_quantity' => 0,
                        'reorder_point' => $product->reorder_point ?? 0,
                        'max_stock' => $product->stock_max ?? 0,
                    ]
                );

            $balanceBefore = $stockLevel->quantity;
            $balanceAfter = $balanceBefore + $quantity;

            // Crear lote si aplica
            $productLotId = null;
            if ($product->is_lotted || !empty($options['lot_number'])) {
                $lot = $this->createOrUpdateLot($product, $warehouse, $quantity, $unitCost, $options);
                $productLotId = $lot->id;
            }

            // Crear movimiento
            $movement = StockMovement::create([
                'tenant_id' => $product->tenant_id,
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'product_lot_id' => $productLotId,
                'movement_type' => $movementType,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'balance_before' => $balanceBefore,
                'running_balance' => $balanceAfter,
                'reference_type' => $options['reference_type'] ?? null,
                'reference_id' => $options['reference_id'] ?? null,
                'reference_number' => $options['reference_number'] ?? null,
                'notes' => $options['notes'] ?? null,
                'created_by' => $options['user_id'] ?? auth()->id(),
            ]);

            // Actualizar stock level
            $stockLevel->quantity = $balanceAfter;
            $stockLevel->save();

            // Recalcular costos si es promedio
            if ($product->valuation_method === InventoryCostingService::METHOD_AVERAGE) {
                $this->costingService->recalculateAfterEntry($product, $quantity, $unitCost);
            }

            // Verificar niveles de stock para alertas
            $this->checkStockLevels($product, $warehouse, $stockLevel);

            Log::info("Entrada de inventario registrada", [
                'movement_id' => $movement->id,
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'quantity' => $quantity,
                'balance_after' => $balanceAfter,
            ]);

            return $movement;
        });
    }

    /**
     * Registrar salida de inventario
     */
    public function registerExit(
        Product $product,
        Warehouse $warehouse,
        float $quantity,
        string $movementType,
        array $options = []
    ): StockMovement {
        return DB::transaction(function () use ($product, $warehouse, $quantity, $movementType, $options) {
            // Validar tipo de movimiento
            if (!in_array($movementType, StockMovement::getExitTypes())) {
                throw new \InvalidArgumentException('Tipo de movimiento no válido para salida');
            }

            // Obtener stock level con lock
            $stockLevel = StockLevel::lockForUpdate()
                ->where('product_id', $product->id)
                ->where('warehouse_id', $warehouse->id)
                ->first();

            if (!$stockLevel) {
                throw ValidationException::withMessages([
                    'quantity' => "No hay stock disponible en el almacén {$warehouse->name}",
                ]);
            }

            $availableStock = $stockLevel->available_quantity;

            // Verificar stock disponible
            if ($availableStock < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => "Stock insuficiente. Disponible: {$availableStock}, Solicitado: {$quantity}",
                ]);
            }

            $balanceBefore = $stockLevel->quantity;
            $balanceAfter = $balanceBefore - $quantity;

            // Calcular costos según método de valuación
            $lotId = $options['lot_id'] ?? null;
            $costCalculation = $this->costingService->calculateExitCost($product, $quantity, $lotId);
            $unitCost = $costCalculation['weighted_average_cost'];

            // Consumir de lotes
            $this->costingService->consumeLots($product, $quantity, $lotId);

            // Crear movimiento
            $movement = StockMovement::create([
                'tenant_id' => $product->tenant_id,
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'product_lot_id' => $lotId,
                'movement_type' => $movementType,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'balance_before' => $balanceBefore,
                'running_balance' => $balanceAfter,
                'reference_type' => $options['reference_type'] ?? null,
                'reference_id' => $options['reference_id'] ?? null,
                'reference_number' => $options['reference_number'] ?? null,
                'notes' => $options['notes'] ?? null,
                'created_by' => $options['user_id'] ?? auth()->id(),
            ]);

            // Actualizar stock level
            $stockLevel->quantity = $balanceAfter;
            $stockLevel->save();

            // Verificar niveles de stock para alertas
            $this->checkStockLevels($product, $warehouse, $stockLevel);

            Log::info("Salida de inventario registrada", [
                'movement_id' => $movement->id,
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'balance_after' => $balanceAfter,
            ]);

            return $movement;
        });
    }

    /**
     * Ajustar stock (entrada o salida según el signo)
     */
    public function adjustStock(
        Product $product,
        Warehouse $warehouse,
        float $newQuantity,
        string $reason,
        array $options = []
    ): StockMovement {
        return DB::transaction(function () use ($product, $warehouse, $newQuantity, $reason, $options) {
            $stockLevel = StockLevel::lockForUpdate()
                ->firstOrCreate(
                    [
                        'tenant_id' => $product->tenant_id,
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouse->id,
                    ],
                    ['quantity' => 0]
                );

            $currentQuantity = $stockLevel->quantity;
            $difference = $newQuantity - $currentQuantity;

            if ($difference == 0) {
                throw new \InvalidArgumentException('La nueva cantidad es igual a la actual');
            }

            $movementType = $difference > 0 
                ? StockMovement::TYPE_ADJUSTMENT_POSITIVE 
                : StockMovement::TYPE_ADJUSTMENT_NEGATIVE;

            $unitCost = $product->unit_cost ?? 0;

            $movement = StockMovement::create([
                'tenant_id' => $product->tenant_id,
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'movement_type' => $movementType,
                'quantity' => abs($difference),
                'unit_cost' => $unitCost,
                'balance_before' => $currentQuantity,
                'running_balance' => $newQuantity,
                'notes' => "Ajuste: {$reason}. Cantidad anterior: {$currentQuantity}",
                'created_by' => $options['user_id'] ?? auth()->id(),
            ]);

            $stockLevel->quantity = $newQuantity;
            $stockLevel->save();

            // Crear evento de ajuste
            InventoryEvent::create([
                'tenant_id' => $product->tenant_id,
                'event_type' => InventoryEvent::EVENT_ADJUSTMENT_CREATED,
                'entity_type' => Product::class,
                'entity_id' => $product->id,
                'payload' => [
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'previous_quantity' => $currentQuantity,
                    'new_quantity' => $newQuantity,
                    'difference' => $difference,
                    'reason' => $reason,
                    'movement_id' => $movement->id,
                ],
                'priority' => InventoryEvent::PRIORITY_MEDIUM,
            ]);

            $this->checkStockLevels($product, $warehouse, $stockLevel);

            return $movement;
        });
    }

    // ===========================================
    // TRANSFERENCIAS ENTRE ALMACÉNES
    // ===========================================

    /**
     * Crear una transferencia entre almacenes
     */
    public function createTransfer(
        Warehouse $sourceWarehouse,
        Warehouse $destinationWarehouse,
        array $items,
        array $options = []
    ): WarehouseTransfer {
        return DB::transaction(function () use ($sourceWarehouse, $destinationWarehouse, $items, $options) {
            // Validar que no sean el mismo almacén
            if ($sourceWarehouse->id === $destinationWarehouse->id) {
                throw new \InvalidArgumentException('El almacén origen y destino no pueden ser el mismo');
            }

            // Generar número de transferencia
            $transferNumber = $this->generateTransferNumber($sourceWarehouse->tenant_id);

            // Crear transferencia
            $transfer = WarehouseTransfer::create([
                'tenant_id' => $sourceWarehouse->tenant_id,
                'source_warehouse_id' => $sourceWarehouse->id,
                'destination_warehouse_id' => $destinationWarehouse->id,
                'transfer_number' => $transferNumber,
                'transfer_date' => $options['transfer_date'] ?? now(),
                'expected_arrival_date' => $options['expected_arrival_date'] ?? null,
                'status' => WarehouseTransfer::STATUS_PENDING,
                'tracking_number' => $options['tracking_number'] ?? null,
                'carrier_name' => $options['carrier_name'] ?? null,
                'shipping_method' => $options['shipping_method'] ?? null,
                'shipping_cost' => $options['shipping_cost'] ?? null,
                'notes' => $options['notes'] ?? null,
                'created_by' => $options['user_id'] ?? auth()->id(),
            ]);

            // Crear items de transferencia
            foreach ($items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                // Validar stock disponible
                $stockLevel = StockLevel::where('product_id', $product->id)
                    ->where('warehouse_id', $sourceWarehouse->id)
                    ->first();

                $availableStock = $stockLevel?->available_quantity ?? 0;
                $requestedQuantity = $itemData['quantity'];

                if ($availableStock < $requestedQuantity) {
                    throw ValidationException::withMessages([
                        "items.{$product->id}" => "Stock insuficiente para {$product->name}. Disponible: {$availableStock}",
                    ]);
                }

                // Obtener costo según método de valuación
                $costCalculation = $this->costingService->calculateExitCost($product, $requestedQuantity, $itemData['lot_id'] ?? null);

                WarehouseTransferItem::create([
                    'tenant_id' => $transfer->tenant_id,
                    'warehouse_transfer_id' => $transfer->id,
                    'product_id' => $product->id,
                    'product_lot_id' => $itemData['lot_id'] ?? null,
                    'quantity_requested' => $requestedQuantity,
                    'unit_cost' => $costCalculation['weighted_average_cost'],
                    'total_cost' => $costCalculation['total_cost'],
                    'status' => WarehouseTransferItem::STATUS_PENDING,
                    'notes' => $itemData['notes'] ?? null,
                ]);
            }

            // Calcular totales
            $transfer->calculateTotals();

            Log::info("Transferencia creada", [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
                'source' => $sourceWarehouse->name,
                'destination' => $destinationWarehouse->name,
                'items_count' => count($items),
            ]);

            return $transfer;
        });
    }

    /**
     * Enviar transferencia (cambiar estado a EN_TRÁNSITO)
     */
    public function sendTransfer(WarehouseTransfer $transfer, array $options = []): WarehouseTransfer
    {
        return DB::transaction(function () use ($transfer, $options) {
            if (!$transfer->canBeSent()) {
                throw new \InvalidArgumentException("La transferencia no puede ser enviada. Estado actual: {$transfer->status}");
            }

            $sourceWarehouse = $transfer->sourceWarehouse;

            foreach ($transfer->items as $item) {
                $product = $item->product;
                $quantity = $item->quantity_requested;

                // Registrar salida del almacén origen
                $movement = $this->registerExit(
                    $product,
                    $sourceWarehouse,
                    $quantity,
                    StockMovement::TYPE_TRANSFER_OUT,
                    [
                        'reference_type' => WarehouseTransfer::class,
                        'reference_id' => $transfer->id,
                        'reference_number' => $transfer->transfer_number,
                        'notes' => "Transferencia a {$transfer->destinationWarehouse->name}",
                        'user_id' => $options['user_id'] ?? auth()->id(),
                        'lot_id' => $item->product_lot_id,
                    ]
                );

                // Actualizar item
                $item->quantity_sent = $quantity;
                $item->status = WarehouseTransferItem::STATUS_SENT;
                $item->save();

                // Actualizar stock en tránsito en el destino
                $this->updateInTransitStock($product, $transfer->destinationWarehouse, $quantity);
            }

            // Actualizar transferencia
            $transfer->status = WarehouseTransfer::STATUS_IN_TRANSIT;
            $transfer->sent_by = $options['user_id'] ?? auth()->id();
            $transfer->sent_at = now();
            $transfer->save();

            // Crear evento
            InventoryEvent::createTransferEvent($transfer, InventoryEvent::EVENT_TRANSFER_IN_TRANSIT);

            Log::info("Transferencia enviada", [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
            ]);

            return $transfer->fresh('items');
        });
    }

    /**
     * Recibir transferencia (cambiar estado a RECIBIDA)
     */
    public function receiveTransfer(WarehouseTransfer $transfer, array $receivedItems = [], array $options = []): WarehouseTransfer
    {
        return DB::transaction(function () use ($transfer, $receivedItems, $options) {
            if (!$transfer->canBeReceived()) {
                throw new \InvalidArgumentException("La transferencia no puede ser recibida. Estado actual: {$transfer->status}");
            }

            $destinationWarehouse = $transfer->destinationWarehouse;
            $allReceived = true;
            $anyReceived = false;

            foreach ($transfer->items as $item) {
                $product = $item->product;
                $receivedData = collect($receivedItems)->firstWhere('item_id', $item->id);
                
                $receivedQuantity = $receivedData['quantity_received'] ?? $item->quantity_sent;
                $itemStatus = $receivedData['status'] ?? WarehouseTransferItem::STATUS_RECEIVED;
                $itemNotes = $receivedData['notes'] ?? null;

                // Si no se recibió nada y no hay daños/pérdidas
                if ($receivedQuantity == 0 && $itemStatus === WarehouseTransferItem::STATUS_RECEIVED) {
                    $allReceived = false;
                    continue;
                }

                if ($receivedQuantity > 0) {
                    // Registrar entrada en almacén destino
                    $this->registerEntry(
                        $product,
                        $destinationWarehouse,
                        $receivedQuantity,
                        $item->unit_cost,
                        StockMovement::TYPE_TRANSFER_IN,
                        [
                            'reference_type' => WarehouseTransfer::class,
                            'reference_id' => $transfer->id,
                            'reference_number' => $transfer->transfer_number,
                            'notes' => "Transferencia desde {$transfer->sourceWarehouse->name}. {$itemNotes}",
                            'user_id' => $options['user_id'] ?? auth()->id(),
                        ]
                    );

                    $anyReceived = true;
                }

                // Actualizar item
                $item->quantity_received = $receivedQuantity;
                $item->status = $itemStatus;
                $item->notes = $itemNotes ? $item->notes . " | " . $itemNotes : $item->notes;
                $item->save();

                if ($receivedQuantity < $item->quantity_sent) {
                    $allReceived = false;
                }

                // Reducir stock en tránsito
                $this->updateInTransitStock($product, $destinationWarehouse, -$item->quantity_sent);
            }

            // Determinar estado final
            if (!$anyReceived) {
                $transfer->status = WarehouseTransfer::STATUS_REJECTED;
                $transfer->rejection_reason = $options['rejection_reason'] ?? 'Nada fue recibido';
            } elseif ($allReceived) {
                $transfer->status = WarehouseTransfer::STATUS_RECEIVED;
            } else {
                $transfer->status = WarehouseTransfer::STATUS_PARTIALLY_RECEIVED;
            }

            $transfer->received_by = $options['user_id'] ?? auth()->id();
            $transfer->received_at = now();
            $transfer->actual_arrival_date = now();
            $transfer->save();

            // Crear evento
            InventoryEvent::createTransferEvent($transfer, InventoryEvent::EVENT_TRANSFER_RECEIVED);

            Log::info("Transferencia recibida", [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
                'status' => $transfer->status,
            ]);

            return $transfer->fresh('items');
        });
    }

    /**
     * Cancelar transferencia
     */
    public function cancelTransfer(WarehouseTransfer $transfer, string $reason, array $options = []): WarehouseTransfer
    {
        return DB::transaction(function () use ($transfer, $reason, $options) {
            if (!$transfer->canBeCancelled()) {
                throw new \InvalidArgumentException("La transferencia no puede ser cancelada. Estado actual: {$transfer->status}");
            }

            // Si ya estaba preparándose, restaurar stock
            if ($transfer->status === WarehouseTransfer::STATUS_PREPARING) {
                foreach ($transfer->items as $item) {
                    // Liberar reservas si existieran
                    $this->releaseReservation($item->product, $transfer->sourceWarehouse, $item->quantity_requested);
                }
            }

            $transfer->status = WarehouseTransfer::STATUS_CANCELLED;
            $transfer->rejection_reason = $reason;
            $transfer->save();

            Log::info("Transferencia cancelada", [
                'transfer_id' => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
                'reason' => $reason,
            ]);

            return $transfer;
        });
    }

    // ===========================================
    // RESERVAS DE STOCK
    // ===========================================

    /**
     * Crear una reserva de stock
     */
    public function createReservation(
        Product $product,
        Warehouse $warehouse,
        float $quantity,
        string $reservableType,
        string $reservableId,
        array $options = []
    ): StockReservation {
        return DB::transaction(function () use ($product, $warehouse, $quantity, $reservableType, $reservableId, $options) {
            // Verificar stock disponible
            $stockLevel = StockLevel::lockForUpdate()
                ->where('product_id', $product->id)
                ->where('warehouse_id', $warehouse->id)
                ->first();

            $availableStock = $stockLevel?->available_quantity ?? 0;

            if ($availableStock < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => "Stock insuficiente para reserva. Disponible: {$availableStock}",
                ]);
            }

            $reservation = StockReservation::create([
                'tenant_id' => $product->tenant_id,
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'product_lot_id' => $options['lot_id'] ?? null,
                'quantity' => $quantity,
                'reservable_type' => $reservableType,
                'reservable_id' => $reservableId,
                'status' => StockReservation::STATUS_ACTIVE,
                'expires_at' => $options['expires_at'] ?? now()->addDays(7),
                'notes' => $options['notes'] ?? null,
            ]);

            // Actualizar stock reservado
            if ($stockLevel) {
                $stockLevel->reserved_quantity += $quantity;
                $stockLevel->save();
            }

            Log::info("Reserva de stock creada", [
                'reservation_id' => $reservation->id,
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
                'quantity' => $quantity,
            ]);

            return $reservation;
        });
    }

    /**
     * Liberar una reserva
     */
    public function releaseReservation(
        Product $product,
        Warehouse $warehouse,
        float $quantity,
        ?string $reservableType = null,
        ?string $reservableId = null
    ): void {
        $query = StockReservation::where('product_id', $product->id)
            ->where('warehouse_id', $warehouse->id)
            ->where('status', StockReservation::STATUS_ACTIVE);

        if ($reservableType && $reservableId) {
            $query->where('reservable_type', $reservableType)
                  ->where('reservable_id', $reservableId);
        }

        $reservations = $query->orderBy('created_at', 'asc')->get();

        $remainingToRelease = $quantity;

        foreach ($reservations as $reservation) {
            if ($remainingToRelease <= 0) break;

            $releaseAmount = min($remainingToRelease, $reservation->remaining_quantity);
            $reservation->release($releaseAmount);
            $remainingToRelease -= $releaseAmount;
        }

        // Actualizar stock level
        $stockLevel = StockLevel::where('product_id', $product->id)
            ->where('warehouse_id', $warehouse->id)
            ->first();

        if ($stockLevel) {
            $stockLevel->reserved_quantity = max(0, $stockLevel->reserved_quantity - $quantity);
            $stockLevel->save();
        }
    }

    // ===========================================
    // MÉTODOS AUXILIARES
    // ===========================================

    /**
     * Crear o actualizar un lote
     */
    private function createOrUpdateLot(
        Product $product,
        Warehouse $warehouse,
        float $quantity,
        float $unitCost,
        array $options
    ): ProductLot {
        $lotNumber = $options['lot_number'] ?? $this->generateLotNumber($product);

        $lot = ProductLot::create([
            'tenant_id' => $product->tenant_id,
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'lot_number' => $lotNumber,
            'initial_quantity' => $quantity,
            'remaining_quantity' => $quantity,
            'unit_cost' => $unitCost,
            'manufacturing_date' => $options['manufacturing_date'] ?? null,
            'expiry_date' => $options['expiry_date'] ?? null,
            'received_date' => $options['received_date'] ?? now(),
            'supplier_id' => $options['supplier_id'] ?? null,
            'purchase_order_number' => $options['purchase_order_number'] ?? null,
            'status' => 'active',
            'notes' => $options['notes'] ?? null,
        ]);

        // Verificar si el lote está próximo a vencer
        if ($lot->isExpiringSoon(30)) {
            InventoryEvent::createLotExpiringEvent($lot, $lot->daysUntilExpiry());
        }

        return $lot;
    }

    /**
     * Actualizar stock en tránsito
     */
    private function updateInTransitStock(Product $product, Warehouse $warehouse, float $quantity): void
    {
        $stockLevel = StockLevel::firstOrCreate(
            [
                'tenant_id' => $product->tenant_id,
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id,
            ],
            [
                'quantity' => 0,
                'reserved_quantity' => 0,
                'in_transit_quantity' => 0,
            ]
        );

        $stockLevel->in_transit_quantity = max(0, $stockLevel->in_transit_quantity + $quantity);
        $stockLevel->save();
    }

    /**
     * Verificar niveles de stock y crear eventos si es necesario
     */
    private function checkStockLevels(Product $product, Warehouse $warehouse, StockLevel $stockLevel): void
    {
        $currentStock = $stockLevel->quantity;
        $minStock = $product->stock_min ?? 0;
        $reorderPoint = $product->reorder_point ?? 0;

        if ($currentStock <= 0) {
            InventoryEvent::createStockLowEvent($product, $warehouse, $currentStock, $minStock);
        } elseif ($currentStock <= $minStock) {
            InventoryEvent::createStockLowEvent($product, $warehouse, $currentStock, $minStock);
        }
    }

    /**
     * Generar número de transferencia
     */
    private function generateTransferNumber(string $tenantId): string
    {
        $prefix = 'TRF';
        $year = now()->format('Y');
        $count = WarehouseTransfer::where('tenant_id', $tenantId)
            ->whereYear('created_at', now()->year)
            ->count() + 1;
        
        return "{$prefix}-{$year}-" . str_pad($count, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Generar número de lote
     */
    private function generateLotNumber(Product $product): string
    {
        $prefix = 'LOT';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        
        return "{$prefix}-{$date}-{$random}";
    }

    /**
     * Obtener estadísticas de inventario
     */
    public function getInventoryStats(string $tenantId): array
    {
        $totalProducts = Product::where('tenant_id', $tenantId)->count();
        
        $totalValue = StockLevel::where('tenant_id', $tenantId)
            ->join('products', 'stock_levels.product_id', '=', 'products.id')
            ->sum(DB::raw('stock_levels.quantity * products.unit_cost'));

        $lowStockCount = StockLevel::where('tenant_id', $tenantId)
            ->whereRaw('quantity <= reorder_point')
            ->where('quantity', '>', 0)
            ->count();

        $outOfStockCount = StockLevel::where('tenant_id', $tenantId)
            ->where('quantity', '<=', 0)
            ->count();

        $inTransitValue = StockLevel::where('tenant_id', $tenantId)
            ->where('in_transit_quantity', '>', 0)
            ->join('products', 'stock_levels.product_id', '=', 'products.id')
            ->sum(DB::raw('stock_levels.in_transit_quantity * products.unit_cost'));

        return [
            'total_products' => $totalProducts,
            'total_stock_value' => round($totalValue, 2),
            'low_stock_count' => $lowStockCount,
            'out_of_stock_count' => $outOfStockCount,
            'in_transit_value' => round($inTransitValue, 2),
            'total_warehouses' => Warehouse::where('tenant_id', $tenantId)->count(),
            'active_transfers' => WarehouseTransfer::where('tenant_id', $tenantId)
                ->whereIn('status', [WarehouseTransfer::STATUS_PENDING, WarehouseTransfer::STATUS_IN_TRANSIT])
                ->count(),
        ];
    }
}
