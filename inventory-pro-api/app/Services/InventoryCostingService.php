<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductLot;
use App\Models\StockMovement;
use App\Models\StockLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Servicio de cálculo de costos de inventario
 * Implementa métodos FIFO, LIFO y Promedio Ponderado
 * Según especificaciones del documento técnico ERP
 */
class InventoryCostingService
{
    /**
     * Métodos de valuación soportados
     */
    const METHOD_FIFO = 'FIFO';
    const METHOD_LIFO = 'LIFO';
    const METHOD_AVERAGE = 'AVERAGE';
    const METHOD_SPECIFIC = 'SPECIFIC';

    /**
     * Calcular costo de salida según el método del producto
     */
    public function calculateExitCost(Product $product, float $quantity, ?string $lotId = null): array
    {
        $method = $product->valuation_method ?? self::METHOD_FIFO;

        return match($method) {
            self::METHOD_FIFO => $this->calculateFIFOCost($product, $quantity, $lotId),
            self::METHOD_LIFO => $this->calculateLIFOCost($product, $quantity, $lotId),
            self::METHOD_AVERAGE => $this->calculateAverageCost($product, $quantity),
            self::METHOD_SPECIFIC => $this->calculateSpecificCost($product, $quantity, $lotId),
            default => $this->calculateFIFOCost($product, $quantity, $lotId),
        };
    }

    /**
     * Método FIFO (First In, First Out)
     * Primero en entrar, primero en salir
     */
    public function calculateFIFOCost(Product $product, float $quantity, ?string $specificLotId = null): array
    {
        $costDetails = [];
        $remainingQuantity = $quantity;
        $totalCost = 0;

        // Si se especifica un lote, usar ese
        if ($specificLotId) {
            $lot = ProductLot::where('id', $specificLotId)
                            ->where('product_id', $product->id)
                            ->where('remaining_quantity', '>', 0)
                            ->first();

            if ($lot) {
                $lotQuantity = min($remainingQuantity, $lot->remaining_quantity);
                $costDetails[] = [
                    'lot_id' => $lot->id,
                    'lot_number' => $lot->lot_number,
                    'quantity' => $lotQuantity,
                    'unit_cost' => $lot->unit_cost,
                    'total_cost' => $lotQuantity * $lot->unit_cost,
                ];
                $totalCost += $lotQuantity * $lot->unit_cost;
                $remainingQuantity -= $lotQuantity;
            }
        }

        // Si aún queda cantidad, tomar de los lotes más antiguos (FIFO)
        if ($remainingQuantity > 0) {
            $lots = ProductLot::where('product_id', $product->id)
                             ->where('remaining_quantity', '>', 0)
                             ->where('status', 'active')
                             ->orderBy('received_date', 'asc')
                             ->orderBy('created_at', 'asc')
                             ->get();

            foreach ($lots as $lot) {
                if ($remainingQuantity <= 0) break;
                if ($specificLotId && $lot->id === $specificLotId) continue;

                $lotQuantity = min($remainingQuantity, $lot->remaining_quantity);
                
                $costDetails[] = [
                    'lot_id' => $lot->id,
                    'lot_number' => $lot->lot_number,
                    'quantity' => $lotQuantity,
                    'unit_cost' => $lot->unit_cost,
                    'total_cost' => $lotQuantity * $lot->unit_cost,
                ];

                $totalCost += $lotQuantity * $lot->unit_cost;
                $remainingQuantity -= $lotQuantity;
            }
        }

        // Si aún queda cantidad, usar el costo promedio como fallback
        if ($remainingQuantity > 0) {
            $avgCost = $this->getCurrentAverageCost($product);
            $costDetails[] = [
                'lot_id' => null,
                'lot_number' => 'Sin lote',
                'quantity' => $remainingQuantity,
                'unit_cost' => $avgCost,
                'total_cost' => $remainingQuantity * $avgCost,
            ];
            $totalCost += $remainingQuantity * $avgCost;
        }

        $actualQuantity = $quantity - $remainingQuantity;
        $weightedAverageCost = $actualQuantity > 0 ? $totalCost / $actualQuantity : 0;

        return [
            'method' => self::METHOD_FIFO,
            'quantity_requested' => $quantity,
            'quantity_allocated' => $actualQuantity,
            'weighted_average_cost' => round($weightedAverageCost, 4),
            'total_cost' => round($totalCost, 2),
            'lots' => $costDetails,
        ];
    }

    /**
     * Método LIFO (Last In, First Out)
     * Último en entrar, primero en salir
     */
    public function calculateLIFOCost(Product $product, float $quantity, ?string $specificLotId = null): array
    {
        $costDetails = [];
        $remainingQuantity = $quantity;
        $totalCost = 0;

        // Si se especifica un lote, usar ese
        if ($specificLotId) {
            $lot = ProductLot::where('id', $specificLotId)
                            ->where('product_id', $product->id)
                            ->where('remaining_quantity', '>', 0)
                            ->first();

            if ($lot) {
                $lotQuantity = min($remainingQuantity, $lot->remaining_quantity);
                $costDetails[] = [
                    'lot_id' => $lot->id,
                    'lot_number' => $lot->lot_number,
                    'quantity' => $lotQuantity,
                    'unit_cost' => $lot->unit_cost,
                    'total_cost' => $lotQuantity * $lot->unit_cost,
                ];
                $totalCost += $lotQuantity * $lot->unit_cost;
                $remainingQuantity -= $lotQuantity;
            }
        }

        // Si aún queda cantidad, tomar de los lotes más recientes (LIFO)
        if ($remainingQuantity > 0) {
            $lots = ProductLot::where('product_id', $product->id)
                             ->where('remaining_quantity', '>', 0)
                             ->where('status', 'active')
                             ->orderBy('received_date', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->get();

            foreach ($lots as $lot) {
                if ($remainingQuantity <= 0) break;
                if ($specificLotId && $lot->id === $specificLotId) continue;

                $lotQuantity = min($remainingQuantity, $lot->remaining_quantity);
                
                $costDetails[] = [
                    'lot_id' => $lot->id,
                    'lot_number' => $lot->lot_number,
                    'quantity' => $lotQuantity,
                    'unit_cost' => $lot->unit_cost,
                    'total_cost' => $lotQuantity * $lot->unit_cost,
                ];

                $totalCost += $lotQuantity * $lot->unit_cost;
                $remainingQuantity -= $lotQuantity;
            }
        }

        // Si aún queda cantidad, usar el costo promedio como fallback
        if ($remainingQuantity > 0) {
            $avgCost = $this->getCurrentAverageCost($product);
            $costDetails[] = [
                'lot_id' => null,
                'lot_number' => 'Sin lote',
                'quantity' => $remainingQuantity,
                'unit_cost' => $avgCost,
                'total_cost' => $remainingQuantity * $avgCost,
            ];
            $totalCost += $remainingQuantity * $avgCost;
        }

        $actualQuantity = $quantity - $remainingQuantity;
        $weightedAverageCost = $actualQuantity > 0 ? $totalCost / $actualQuantity : 0;

        return [
            'method' => self::METHOD_LIFO,
            'quantity_requested' => $quantity,
            'quantity_allocated' => $actualQuantity,
            'weighted_average_cost' => round($weightedAverageCost, 4),
            'total_cost' => round($totalCost, 2),
            'lots' => $costDetails,
        ];
    }

    /**
     * Método de Promedio Ponderado
     */
    public function calculateAverageCost(Product $product, float $quantity): array
    {
        $averageCost = $this->getCurrentAverageCost($product);
        $totalCost = $quantity * $averageCost;

        return [
            'method' => self::METHOD_AVERAGE,
            'quantity_requested' => $quantity,
            'quantity_allocated' => $quantity,
            'weighted_average_cost' => round($averageCost, 4),
            'total_cost' => round($totalCost, 2),
            'lots' => [
                [
                    'lot_id' => null,
                    'lot_number' => 'Promedio Ponderado',
                    'quantity' => $quantity,
                    'unit_cost' => $averageCost,
                    'total_cost' => $totalCost,
                ]
            ],
        ];
    }

    /**
     * Método de Identificación Específica
     */
    public function calculateSpecificCost(Product $product, float $quantity, ?string $lotId = null): array
    {
        if (!$lotId) {
            throw new \InvalidArgumentException('El método de identificación específica requiere un lote');
        }

        $lot = ProductLot::where('id', $lotId)
                        ->where('product_id', $product->id)
                        ->first();

        if (!$lot) {
            throw new \InvalidArgumentException('Lote no encontrado');
        }

        if ($lot->remaining_quantity < $quantity) {
            throw new \InvalidArgumentException('Cantidad insuficiente en el lote especificado');
        }

        $totalCost = $quantity * $lot->unit_cost;

        return [
            'method' => self::METHOD_SPECIFIC,
            'quantity_requested' => $quantity,
            'quantity_allocated' => $quantity,
            'weighted_average_cost' => $lot->unit_cost,
            'total_cost' => $totalCost,
            'lots' => [
                [
                    'lot_id' => $lot->id,
                    'lot_number' => $lot->lot_number,
                    'quantity' => $quantity,
                    'unit_cost' => $lot->unit_cost,
                    'total_cost' => $totalCost,
                ]
            ],
        ];
    }

    /**
     * Obtener el costo promedio ponderado actual
     */
    public function getCurrentAverageCost(Product $product): float
    {
        // Calcular promedio ponderado basado en lotes activos
        $result = ProductLot::where('product_id', $product->id)
                           ->where('status', 'active')
                           ->where('remaining_quantity', '>', 0)
                           ->select(
                               DB::raw('SUM(remaining_quantity * unit_cost) as total_value'),
                               DB::raw('SUM(remaining_quantity) as total_quantity')
                           )
                           ->first();

        if ($result && $result->total_quantity > 0) {
            return $result->total_value / $result->total_quantity;
        }

        // Fallback al costo unitario del producto
        return $product->unit_cost ?? 0;
    }

    /**
     * Actualizar el costo promedio del producto
     */
    public function updateProductAverageCost(Product $product): void
    {
        $newAverageCost = $this->getCurrentAverageCost($product);
        
        if ($newAverageCost > 0 && $newAverageCost != $product->unit_cost) {
            $product->unit_cost = $newAverageCost;
            $product->save();
        }
    }

    /**
     * Recalcular y actualizar costos después de una entrada
     */
    public function recalculateAfterEntry(Product $product, float $quantity, float $unitCost): void
    {
        $method = $product->valuation_method ?? self::METHOD_FIFO;

        if ($method === self::METHOD_AVERAGE) {
            $this->updateProductAverageCost($product);
        }

        Log::info("Costos recalculados después de entrada", [
            'product_id' => $product->id,
            'method' => $method,
            'quantity' => $quantity,
            'unit_cost' => $unitCost,
        ]);
    }

    /**
     * Consumir lotes según el método de valuación
     */
    public function consumeLots(Product $product, float $quantity, ?string $lotId = null, ?string $referenceType = null, ?string $referenceId = null): array
    {
        $method = $product->valuation_method ?? self::METHOD_FIFO;
        
        // Calcular costos
        $costCalculation = $this->calculateExitCost($product, $quantity, $lotId);
        
        // Consumir de cada lote
        foreach ($costCalculation['lots'] as $lotData) {
            if ($lotData['lot_id']) {
                $lot = ProductLot::find($lotData['lot_id']);
                if ($lot) {
                    $lot->remaining_quantity -= $lotData['quantity'];
                    if ($lot->remaining_quantity <= 0) {
                        $lot->status = 'depleted';
                    }
                    $lot->save();
                }
            }
        }

        // Actualizar costo promedio si es necesario
        if ($method === self::METHOD_AVERAGE) {
            $this->updateProductAverageCost($product);
        }

        Log::info("Lotes consumidos", [
            'product_id' => $product->id,
            'method' => $method,
            'quantity' => $quantity,
            'lots' => collect($costCalculation['lots'])->map(fn($l) => [
                'lot_id' => $l['lot_id'],
                'quantity' => $l['quantity'],
                'unit_cost' => $l['unit_cost'],
            ]),
        ]);

        return $costCalculation;
    }

    /**
     * Obtener el valor total del inventario por método de valuación
     */
    public function getInventoryValuation(Product $product): array
    {
        $lots = ProductLot::where('product_id', $product->id)
                         ->where('status', 'active')
                         ->where('remaining_quantity', '>', 0)
                         ->get();

        $fifoValue = 0;
        $lifoValue = 0;
        $averageValue = 0;
        $specificValue = 0;

        $totalQuantity = $lots->sum('remaining_quantity');
        $averageCost = $this->getCurrentAverageCost($product);

        // Ordenar para FIFO y LIFO
        $fifoLots = $lots->sortBy('received_date');
        $lifoLots = $lots->sortByDesc('received_date');

        foreach ($lots as $lot) {
            $specificValue += $lot->remaining_quantity * $lot->unit_cost;
            $averageValue += $lot->remaining_quantity * $averageCost;
        }

        foreach ($fifoLots as $lot) {
            $fifoValue += $lot->remaining_quantity * $lot->unit_cost;
        }

        foreach ($lifoLots as $lot) {
            $lifoValue += $lot->remaining_quantity * $lot->unit_cost;
        }

        return [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'sku' => $product->sku,
            'current_method' => $product->valuation_method ?? self::METHOD_FIFO,
            'total_quantity' => $totalQuantity,
            'fifo_value' => round($fifoValue, 2),
            'lifo_value' => round($lifoValue, 2),
            'average_value' => round($averageValue, 2),
            'specific_value' => round($specificValue, 2),
            'average_cost' => round($averageCost, 4),
        ];
    }

    /**
     * Generar reporte de valuación Kardex
     */
    public function generateKardexReport(Product $product, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = StockMovement::where('product_id', $product->id)
                             ->orderBy('created_at', 'asc')
                             ->orderBy('id', 'asc');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $movements = $query->get();

        $kardexEntries = [];
        $runningBalance = 0;
        $runningTotalCost = 0;

        foreach ($movements as $movement) {
            $entry = [
                'date' => $movement->created_at->toDateTimeString(),
                'reference' => $movement->reference_number,
                'type' => $movement->movement_type,
                'description' => $movement->notes,
            ];

            if ($movement->isEntry()) {
                $entry['entries'] = [
                    'quantity' => $movement->quantity,
                    'unit_cost' => $movement->unit_cost,
                    'total' => $movement->quantity * $movement->unit_cost,
                ];
                $entry['exits'] = ['quantity' => 0, 'unit_cost' => 0, 'total' => 0];
                
                $runningBalance += $movement->quantity;
                $runningTotalCost += $movement->quantity * $movement->unit_cost;
            } else {
                $entry['entries'] = ['quantity' => 0, 'unit_cost' => 0, 'total' => 0];
                $entry['exits'] = [
                    'quantity' => $movement->quantity,
                    'unit_cost' => $movement->unit_cost,
                    'total' => $movement->quantity * $movement->unit_cost,
                ];
                
                $runningBalance -= $movement->quantity;
                $runningTotalCost -= $movement->quantity * $movement->unit_cost;
            }

            $entry['balance'] = [
                'quantity' => $runningBalance,
                'unit_cost' => $runningBalance > 0 ? $runningTotalCost / $runningBalance : 0,
                'total' => $runningTotalCost,
            ];

            $kardexEntries[] = $entry;
        }

        return [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'valuation_method' => $product->valuation_method ?? self::METHOD_FIFO,
            ],
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'initial_balance' => $movements->first()?->running_balance - ($movements->first()?->isEntry() ? $movements->first()?->quantity : -$movements->first()?->quantity) ?? 0,
            'final_balance' => $runningBalance,
            'entries' => $kardexEntries,
        ];
    }
}
