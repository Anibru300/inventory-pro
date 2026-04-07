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
            'unit' => 'nullable|string|max:50',
            'cost' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'initial_stock' => 'nullable|numeric|min:0',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'valuation_method' => 'nullable|in:FIFO,AVERAGE,LIFO',
            'is_active' => 'boolean',
        ]);

        $initialStock = $validated['initial_stock'] ?? 0;
        $warehouseId = $validated['warehouse_id'] ?? null;
        
        // Mapear campos del frontend a nombres de BD
        $productData = [
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'barcode' => $validated['barcode'] ?? null,
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'unit_of_measure' => $validated['unit'] ?? 'piece',
            'unit_cost' => $validated['cost'],
            'selling_price' => $validated['price'],
            'stock_min' => $validated['min_stock'] ?? 0,
            'stock_max' => $validated['max_stock'] ?? null,
            'reorder_point' => $validated['min_stock'] ?? 0,
            'valuation_method' => $validated['valuation_method'] ?? 'FIFO',
            'is_active' => $validated['is_active'] ?? true,
        ];

        $product = Product::create($productData);

        // Create stock level if warehouse specified
        if ($warehouseId && $initialStock > 0) {
            StockLevel::create([
                'tenant_id' => $product->tenant_id,
                'product_id' => $product->id,
                'warehouse_id' => $warehouseId,
                'quantity' => $initialStock,
                'available_quantity' => $initialStock,
                'reserved_quantity' => 0,
                'reorder_point' => $productData['stock_min'],
                'max_stock' => $productData['stock_max'],
            ]);
        }

        return response()->json($product->load(['category', 'stockLevels.warehouse']), 201);
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

        // Mapear campos del frontend a nombres de BD
        $productData = [];
        if (isset($validated['name'])) $productData['name'] = $validated['name'];
        if (isset($validated['sku'])) $productData['sku'] = $validated['sku'];
        if (isset($validated['barcode'])) $productData['barcode'] = $validated['barcode'];
        if (isset($validated['description'])) $productData['description'] = $validated['description'];
        if (isset($validated['category_id'])) $productData['category_id'] = $validated['category_id'];
        if (isset($validated['unit'])) $productData['unit_of_measure'] = $validated['unit'];
        if (isset($validated['cost'])) $productData['unit_cost'] = $validated['cost'];
        if (isset($validated['price'])) $productData['selling_price'] = $validated['price'];
        if (isset($validated['min_stock'])) {
            $productData['stock_min'] = $validated['min_stock'];
            $productData['reorder_point'] = $validated['min_stock'];
        }
        if (isset($validated['max_stock'])) $productData['stock_max'] = $validated['max_stock'];
        if (isset($validated['valuation_method'])) $productData['valuation_method'] = $validated['valuation_method'];
        if (isset($validated['is_active'])) $productData['is_active'] = $validated['is_active'];

        $product->update($productData);

        return response()->json($product->load(['category', 'stockLevels.warehouse']));
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