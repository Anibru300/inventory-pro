<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Obtener todas las alertas activas
     */
    public function index(Request $request)
    {
        $alerts = [];
        $tenantId = auth()->user()->tenant_id;

        // Productos sin stock
        $outOfStock = Product::with(['category', 'stockLevels.warehouse'])
            ->where('tenant_id', $tenantId)
            ->outOfStock()
            ->get()
            ->map(function ($product) {
                return [
                    'id' => 'oos_' . $product->id,
                    'type' => 'out_of_stock',
                    'severity' => 'critical',
                    'title' => 'Producto agotado',
                    'message' => "{$product->name} no tiene stock disponible",
                    'product' => $product,
                    'created_at' => now(),
                ];
            });

        // Productos con stock bajo
        $lowStock = Product::with(['category', 'stockLevels.warehouse'])
            ->where('tenant_id', $tenantId)
            ->lowStock()
            ->get()
            ->map(function ($product) {
                $currentStock = $product->total_stock;
                return [
                    'id' => 'low_' . $product->id,
                    'type' => 'low_stock',
                    'severity' => 'warning',
                    'title' => 'Stock bajo',
                    'message' => "{$product->name} tiene {$currentStock} unidades (mínimo: {$product->stock_min})",
                    'product' => $product,
                    'current_stock' => $currentStock,
                    'min_stock' => $product->stock_min,
                    'created_at' => now(),
                ];
            });

        $alerts = $outOfStock->merge($lowStock);

        // Agregar alertas de productos por caducar (si usas lotes)
        // Esto requiere tener el modelo ProductLot configurado

        return response()->json([
            'alerts' => $alerts,
            'counts' => [
                'total' => $alerts->count(),
                'critical' => $outOfStock->count(),
                'warning' => $lowStock->count(),
            ],
        ]);
    }

    /**
     * Obtener resumen de alertas (para dashboard)
     */
    public function summary()
    {
        $tenantId = auth()->user()->tenant_id;

        $outOfStockCount = Product::where('tenant_id', $tenantId)->outOfStock()->count();
        $lowStockCount = Product::where('tenant_id', $tenantId)->lowStock()->count();

        return response()->json([
            'out_of_stock' => $outOfStockCount,
            'low_stock' => $lowStockCount,
            'total' => $outOfStockCount + $lowStockCount,
            'has_alerts' => ($outOfStockCount + $lowStockCount) > 0,
        ]);
    }

    /**
     * Obtener productos que necesitan reorden
     */
    public function reorderSuggestions()
    {
        $tenantId = auth()->user()->tenant_id;

        $products = Product::with(['category', 'stockLevels.warehouse', 'preferredSupplier'])
            ->where('tenant_id', $tenantId)
            ->whereHas('stockLevels', function ($q) {
                $q->whereColumn('quantity', '<=', 'products.reorder_point');
            })
            ->get()
            ->map(function ($product) {
                $currentStock = $product->total_stock;
                $suggestedOrder = ($product->stock_max ?? $product->stock_min * 3) - $currentStock;
                
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'current_stock' => $currentStock,
                    'reorder_point' => $product->reorder_point,
                    'suggested_order_quantity' => max($suggestedOrder, 0),
                    'preferred_supplier' => $product->preferredSupplier,
                    'unit_cost' => $product->unit_cost,
                    'estimated_cost' => $product->unit_cost * max($suggestedOrder, 0),
                ];
            });

        return response()->json($products);
    }
}
