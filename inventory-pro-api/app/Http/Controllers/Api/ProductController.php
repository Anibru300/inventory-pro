<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'stockLevels.warehouse']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Stock status filter
        if ($request->has('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        $products = $query->latest()->paginate($request->per_page ?? 25);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products',
            'barcode' => 'nullable|string|max:100|unique:products',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'unit' => 'required|string|max:50',
            'cost' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'initial_stock' => 'nullable|numeric|min:0',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'valuation_method' => 'required|in:FIFO,AVERAGE,LIFO',
            'is_active' => 'boolean',
        ]);

        $initialStock = $validated['initial_stock'] ?? 0;
        $warehouseId = $validated['warehouse_id'] ?? null;
        
        unset($validated['initial_stock']);
        unset($validated['warehouse_id']);

        // Set default stock values
        $validated['quantity'] = $initialStock;
        $validated['available_quantity'] = $initialStock;
        $validated['stock_status'] = $initialStock > 0 ? 'in_stock' : 'out_of_stock';

        $product = Product::create($validated);

        // Create stock level if warehouse specified
        if ($warehouseId && $initialStock > 0) {
            StockLevel::create([
                'tenant_id' => $product->tenant_id,
                'product_id' => $product->id,
                'warehouse_id' => $warehouseId,
                'quantity' => $initialStock,
                'reserved_quantity' => 0,
                'reorder_point' => $validated['min_stock'] ?? 0,
                'max_stock' => $validated['max_stock'] ?? 0,
            ]);
        }

        return response()->json($product->load('stockLevels'), 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load([
            'category',
            'stockLevels.warehouse',
            'stockMovements' => function ($q) {
                $q->latest()->limit(20);
            }
        ]));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'sku' => 'sometimes|string|max:100|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $product->id,
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'unit' => 'sometimes|string|max:50',
            'cost' => 'sometimes|numeric|min:0',
            'price' => 'sometimes|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'valuation_method' => 'sometimes|in:FIFO,AVERAGE,LIFO',
            'is_active' => 'boolean',
        ]);

        $product->update($validated);

        return response()->json($product->load('category', 'stockLevels'));
    }

    public function destroy(Product $product)
    {
        // Check if product has stock movements
        if ($product->stockMovements()->count() > 0) {
            // Soft delete instead of hard delete
            $product->delete();
            return response()->json(['message' => 'Product archived']);
        }

        $product->forceDelete();
        return response()->json(['message' => 'Product deleted']);
    }

    public function lowStock()
    {
        $products = Product::with('category')
            ->where('stock_status', 'low_stock')
            ->get();

        return response()->json($products);
    }
}