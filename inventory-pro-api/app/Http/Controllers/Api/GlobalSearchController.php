<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 5);
        
        if (strlen($query) < 2) {
            return response()->json([
                'products' => [],
                'categories' => [],
                'warehouses' => [],
                'total' => 0,
            ]);
        }

        $tenantId = auth()->user()->tenant_id;

        // Buscar productos
        $products = Product::with(['category'])
            ->where('tenant_id', $tenantId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('barcode', 'like', "%{$query}%");
            })
            ->active()
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'type' => 'product',
                    'title' => $product->name,
                    'subtitle' => $product->sku,
                    'category' => $product->category?->name,
                    'image' => $product->primary_image,
                    'url' => "/products/{$product->id}/edit",
                    'stock_status' => $product->stock_status,
                ];
            });

        // Buscar categorías
        $categories = Category::where('tenant_id', $tenantId)
            ->where('name', 'like', "%{$query}%")
            ->limit($limit)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'type' => 'category',
                    'title' => $category->name,
                    'subtitle' => 'Categoría',
                    'url' => "/categories",
                ];
            });

        // Buscar almacenes
        $warehouses = Warehouse::where('tenant_id', $tenantId)
            ->where('name', 'like', "%{$query}%")
            ->limit($limit)
            ->get()
            ->map(function ($warehouse) {
                return [
                    'id' => $warehouse->id,
                    'type' => 'warehouse',
                    'title' => $warehouse->name,
                    'subtitle' => 'Almacén',
                    'url' => "/warehouses",
                ];
            });

        return response()->json([
            'products' => $products,
            'categories' => $categories,
            'warehouses' => $warehouses,
            'total' => $products->count() + $categories->count() + $warehouses->count(),
        ]);
    }

    /**
     * Comandos rápidos para la búsqueda global
     */
    public function commands()
    {
        $commands = [
            [
                'id' => 'new_product',
                'title' => 'Nuevo Producto',
                'icon' => 'plus',
                'shortcut' => 'P',
                'url' => '/products/new',
            ],
            [
                'id' => 'new_movement',
                'title' => 'Nuevo Movimiento',
                'icon' => 'arrows',
                'shortcut' => 'M',
                'url' => '/movements/new',
            ],
            [
                'id' => 'new_transfer',
                'title' => 'Nueva Transferencia',
                'icon' => 'transfer',
                'shortcut' => 'T',
                'url' => '/transfers/new',
            ],
            [
                'id' => 'reports',
                'title' => 'Ver Reportes',
                'icon' => 'chart',
                'shortcut' => 'R',
                'url' => '/reports',
            ],
            [
                'id' => 'low_stock',
                'title' => 'Productos Stock Bajo',
                'icon' => 'alert',
                'shortcut' => 'L',
                'url' => '/products?stock_status=low_stock',
            ],
        ];

        return response()->json($commands);
    }
}
