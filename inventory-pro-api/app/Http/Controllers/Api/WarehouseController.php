<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            
            Log::info('WarehouseController@index - Inicio', [
                'user_id' => $user?->id,
                'tenant_id' => $user?->tenant_id,
                'auth_check' => auth()->check()
            ]);
            
            if (!$user) {
                Log::warning('WarehouseController@index - Usuario no autenticado');
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }
            
            if (!$user->tenant_id) {
                Log::warning('WarehouseController@index - Usuario sin tenant', ['user_id' => $user->id]);
                return response()->json(['message' => 'Usuario sin empresa asignada'], 403);
            }
            
            Log::info('WarehouseController@index - Consultando almacenes', ['tenant_id' => $user->tenant_id]);
            
            $warehouses = Warehouse::where('is_active', true)
                ->where('tenant_id', $user->tenant_id)
                ->orderBy('is_primary', 'desc')
                ->orderBy('name')
                ->get();
            
            Log::info('WarehouseController@index - Almacenes encontrados', ['count' => $warehouses->count()]);
            
            // Add product count for each warehouse
            foreach ($warehouses as $warehouse) {
                try {
                    Log::info('WarehouseController@index - Consultando stock', ['warehouse_id' => $warehouse->id]);
                    
                    $warehouse->products_count = DB::table('stock_levels')
                        ->where('warehouse_id', $warehouse->id)
                        ->where('tenant_id', $user->tenant_id)
                        ->distinct('product_id')
                        ->count('product_id');
                    
                    Log::info('WarehouseController@index - Stock calculado', [
                        'warehouse_id' => $warehouse->id,
                        'products_count' => $warehouse->products_count
                    ]);
                } catch (\Exception $e) {
                    Log::error('WarehouseController@index - Error en stock_levels', [
                        'warehouse_id' => $warehouse->id,
                        'error' => $e->getMessage()
                    ]);
                    $warehouse->products_count = 0;
                }
            }
            
            Log::info('WarehouseController@index - Éxito', ['total_warehouses' => $warehouses->count()]);
            
            return response()->json($warehouses);
        } catch (\Exception $e) {
            Log::error('WarehouseController@index - Error general', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Error al cargar almacenes: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses,code',
            'address' => 'nullable|string',
            'is_primary' => 'boolean',
        ]);

        $user = auth()->user();
        
        if (!$user || !$user->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // If this is the first warehouse, make it primary
        $existingCount = Warehouse::where('tenant_id', $user->tenant_id)->count();
        $isPrimary = $existingCount === 0 ? true : ($validated['is_primary'] ?? false);

        // If setting this as primary, unset any existing primary
        if ($isPrimary) {
            Warehouse::where('tenant_id', $user->tenant_id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        $warehouse = Warehouse::create([
            'tenant_id' => $user->tenant_id,
            'name' => $validated['name'],
            'code' => $validated['code'],
            'address' => $validated['address'] ?? null,
            'is_primary' => $isPrimary,
            'is_active' => true,
        ]);

        return response()->json($warehouse, 201);
    }

    public function show(Warehouse $warehouse)
    {
        $user = auth()->user();
        
        if ($warehouse->tenant_id !== $user->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($warehouse);
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $user = auth()->user();
        
        if ($warehouse->tenant_id !== $user->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'code' => 'string|max:50|unique:warehouses,code,' . $warehouse->id,
            'address' => 'nullable|string',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // If setting this as primary, unset any existing primary
        if (isset($validated['is_primary']) && $validated['is_primary']) {
            Warehouse::where('tenant_id', $user->tenant_id)
                ->where('is_primary', true)
                ->where('id', '!=', $warehouse->id)
                ->update(['is_primary' => false]);
        }

        $warehouse->update($validated);

        return response()->json($warehouse);
    }

    public function destroy(Warehouse $warehouse)
    {
        $user = auth()->user();
        
        if ($warehouse->tenant_id !== $user->tenant_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Prevent deleting the primary warehouse
        if ($warehouse->is_primary) {
            return response()->json([
                'message' => 'No se puede eliminar el almacén principal. Designa otro como principal primero.'
            ], 422);
        }

        // Soft delete
        $warehouse->update(['is_active' => false]);

        return response()->json(['message' => 'Almacén eliminado correctamente']);
    }
}
