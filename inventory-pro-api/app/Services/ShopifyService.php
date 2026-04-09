<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ShopifyService
{
    private string $shopDomain;
    private string $accessToken;
    private string $baseUrl;

    public function __construct(array $credentials)
    {
        $this->shopDomain = $credentials['shop_domain'];
        $this->accessToken = $credentials['access_token'];
        $this->baseUrl = "https://{$this->shopDomain}.myshopify.com/admin/api/2024-01";
    }

    public function testConnection(): array
    {
        try {
            $response = Http::withHeaders([
                'X-Shopify-Access-Token' => $this->accessToken,
            ])->get("{$this->baseUrl}/shop.json");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'shop' => $response->json('shop.name'),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('errors') ?? 'Error desconocido',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getProducts(array $params = []): array
    {
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->accessToken,
        ])->get("{$this->baseUrl}/products.json", $params);

        return $response->json('products', []);
    }

    public function getProduct($productId): ?array
    {
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->accessToken,
        ])->get("{$this->baseUrl}/products/{$productId}.json");

        return $response->json('product');
    }

    public function createProduct(array $data): array
    {
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->accessToken,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/products.json", [
            'product' => $data,
        ]);

        return $response->json('product', []);
    }

    public function updateProduct($productId, array $data): array
    {
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->accessToken,
            'Content-Type' => 'application/json',
        ])->put("{$this->baseUrl}/products/{$productId}.json", [
            'product' => $data,
        ]);

        return $response->json('product', []);
    }

    public function updateInventory($inventoryItemId, $locationId, int $quantity): bool
    {
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->accessToken,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/inventory_levels/set.json", [
            'location_id' => $locationId,
            'inventory_item_id' => $inventoryItemId,
            'available' => $quantity,
        ]);

        return $response->successful();
    }

    public function getInventory(): array
    {
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->accessToken,
        ])->get("{$this->baseUrl}/inventory_levels.json");

        return $response->json('inventory_levels', []);
    }

    public function getOrders(array $params = []): array
    {
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $this->accessToken,
        ])->get("{$this->baseUrl}/orders.json", $params);

        return $response->json('orders', []);
    }
}
