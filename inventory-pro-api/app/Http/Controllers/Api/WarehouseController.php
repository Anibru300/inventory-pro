<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if (!$user || !$user->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        $warehouses = Warehouse::active()
            ->where('tenant_id', $user->tenant_id)
            ->orderBy('is_primary', 'desc')
            ->orderBy('name')
            ->get();
        
        // Add product count for each warehouse
        foreach ($warehouses as $warehouse) {
            $warehouse->products_count = \DB::table('stock_levels')
                ->where('warehouse_id', $warehouse->id)
                ->where('tenant_id', $user->tenant_id)
                ->distinct('product_id')
                ->count('product_id');
        }
        
        return response()->json($warehouses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses,code',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        // If this is the first warehouse, make it primary
        if (!Warehouse::exists()) {
            $validated['is_primary'] = true;
        }

        $warehouse = Warehouse::create($validated);

        return response()->json($warehouse, 201);
    }

    public function show(Warehouse $warehouse)
    {
        return response()->json($warehouse->load('stockLevels.product'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:warehouses,code,' . $warehouse->id,
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $warehouse->update($validated);

        return response()->json($warehouse);
    }

    public function destroy(Warehouse $warehouse)
    {
        if ($warehouse->is_primary) {
            return response()->json([
                'message' => 'Cannot delete primary warehouse'
            ], 422);
        }

        $warehouse->delete();

        return response()->json(['message' => 'Warehouse deleted']);
    }
}