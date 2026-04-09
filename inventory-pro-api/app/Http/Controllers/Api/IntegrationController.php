<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use App\Services\ShopifyService;
use App\Services\WooCommerceService;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = $request->user()->tenant_id;
        
        $integrations = Integration::byTenant($tenantId)
            ->with(['logs' => function ($q) {
                $q->limit(5);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($integrations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:shopify,woocommerce,mercadolibre',
            'name' => 'required|string|max:255',
            'credentials' => 'required|array',
            'credentials.shop_domain' => 'required_if:type,shopify|string',
            'credentials.access_token' => 'required_if:type,shopify|string',
            'credentials.store_url' => 'required_if:type,woocommerce|string',
            'credentials.consumer_key' => 'required_if:type,woocommerce|string',
            'credentials.consumer_secret' => 'required_if:type,woocommerce|string',
            'settings' => 'nullable|array',
            'sync_products' => 'boolean',
            'sync_stock' => 'boolean',
            'sync_orders' => 'boolean',
        ]);

        // Validate credentials by testing connection
        $connectionTest = $this->testConnection($validated['type'], $validated['credentials']);
        
        if (!$connectionTest['success']) {
            return response()->json([
                'message' => 'No se pudo conectar con la tienda: ' . $connectionTest['error'],
            ], 422);
        }

        $integration = Integration::create([
            'tenant_id' => $request->user()->tenant_id,
            'type' => $validated['type'],
            'name' => $validated['name'],
            'status' => Integration::STATUS_ACTIVE,
            'credentials' => $validated['credentials'],
            'settings' => $validated['settings'] ?? [],
            'sync_products' => $validated['sync_products'] ?? true,
            'sync_stock' => $validated['sync_stock'] ?? true,
            'sync_orders' => $validated['sync_orders'] ?? false,
        ]);

        $integration->log('connection_test', 'success', 'Conexión establecida correctamente');

        return response()->json([
            'message' => 'Integración creada exitosamente',
            'integration' => $integration,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $tenantId = $request->user()->tenant_id;
        
        $integration = Integration::byTenant($tenantId)
            ->with(['logs' => function ($q) {
                $q->limit(20);
            }])
            ->findOrFail($id);

        // Hide sensitive credentials
        $integration->makeHidden(['credentials']);

        return response()->json($integration);
    }

    public function update(Request $request, $id)
    {
        $tenantId = $request->user()->tenant_id;
        $integration = Integration::byTenant($tenantId)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:active,paused,error',
            'credentials' => 'sometimes|array',
            'settings' => 'nullable|array',
            'sync_products' => 'boolean',
            'sync_stock' => 'boolean',
            'sync_orders' => 'boolean',
        ]);

        $integration->update($validated);

        return response()->json([
            'message' => 'Integración actualizada',
            'integration' => $integration,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $tenantId = $request->user()->tenant_id;
        $integration = Integration::byTenant($tenantId)->findOrFail($id);

        $integration->delete();

        return response()->json([
            'message' => 'Integración eliminada',
        ]);
    }

    public function sync(Request $request, $id)
    {
        $tenantId = $request->user()->tenant_id;
        $integration = Integration::byTenant($tenantId)->findOrFail($id);

        $type = $request->input('type', 'all'); // all, products, stock, orders

        try {
            switch ($integration->type) {
                case Integration::TYPE_SHOPIFY:
                    $result = $this->syncShopify($integration, $type);
                    break;
                case Integration::TYPE_WOOCOMMERCE:
                    $result = $this->syncWooCommerce($integration, $type);
                    break;
                default:
                    throw new \Exception('Tipo de integración no soportado');
            }

            $integration->update([
                'last_sync_at' => now(),
                'last_sync_error' => null,
            ]);

            $integration->log('sync_' . $type, 'success', null, $result, $result['processed'] ?? 0);

            return response()->json([
                'message' => 'Sincronización completada',
                'result' => $result,
            ]);

        } catch (\Exception $e) {
            $integration->update([
                'status' => Integration::STATUS_ERROR,
                'last_sync_error' => $e->getMessage(),
            ]);

            $integration->log('sync_' . $type, 'error', $e->getMessage());

            return response()->json([
                'message' => 'Error en sincronización: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function test(Request $request, $id)
    {
        $tenantId = $request->user()->tenant_id;
        $integration = Integration::byTenant($tenantId)->findOrFail($id);

        $test = $this->testConnection($integration->type, $integration->credentials);

        return response()->json($test);
    }

    private function testConnection(string $type, array $credentials): array
    {
        try {
            switch ($type) {
                case Integration::TYPE_SHOPIFY:
                    $service = new ShopifyService($credentials);
                    return $service->testConnection();
                    
                case Integration::TYPE_WOOCOMMERCE:
                    $service = new WooCommerceService($credentials);
                    return $service->testConnection();
                    
                default:
                    return ['success' => false, 'error' => 'Tipo no soportado'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function syncShopify(Integration $integration, string $type): array
    {
        $service = new ShopifyService($integration->credentials);
        $result = ['processed' => 0, 'errors' => []];

        if (in_array($type, ['all', 'products'])) {
            $products = $service->getProducts();
            // Process products sync
            $result['processed'] += count($products);
        }

        if (in_array($type, ['all', 'stock'])) {
            $inventory = $service->getInventory();
            // Update stock levels
            $result['processed'] += count($inventory);
        }

        return $result;
    }

    private function syncWooCommerce(Integration $integration, string $type): array
    {
        $service = new WooCommerceService($integration->credentials);
        $result = ['processed' => 0, 'errors' => []];

        if (in_array($type, ['all', 'products'])) {
            $products = $service->getProducts();
            $result['processed'] += count($products);
        }

        return $result;
    }
}
