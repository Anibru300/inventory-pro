<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WooCommerceService
{
    private string $storeUrl;
    private string $consumerKey;
    private string $consumerSecret;
    private string $apiUrl;

    public function __construct(array $credentials)
    {
        $this->storeUrl = rtrim($credentials['store_url'], '/');
        $this->consumerKey = $credentials['consumer_key'];
        $this->consumerSecret = $credentials['consumer_secret'];
        $this->apiUrl = "{$this->storeUrl}/wp-json/wc/v3";
    }

    public function testConnection(): array
    {
        try {
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get("{$this->apiUrl}/system_status");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'store' => $response->json('environment.store_name'),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('message') ?? 'Error de conexión',
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
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get("{$this->apiUrl}/products", array_merge(['per_page' => 100], $params));

        return $response->json() ?? [];
    }

    public function getProduct($productId): ?array
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get("{$this->apiUrl}/products/{$productId}");

        return $response->json();
    }

    public function createProduct(array $data): array
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->post("{$this->apiUrl}/products", $data);

        return $response->json() ?? [];
    }

    public function updateProduct($productId, array $data): array
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->put("{$this->apiUrl}/products/{$productId}", $data);

        return $response->json() ?? [];
    }

    public function updateStock($productId, int $quantity): bool
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->put("{$this->apiUrl}/products/{$productId}", [
                'stock_quantity' => $quantity,
                'manage_stock' => true,
            ]);

        return $response->successful();
    }

    public function getOrders(array $params = []): array
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get("{$this->apiUrl}/orders", array_merge(['per_page' => 100], $params));

        return $response->json() ?? [];
    }

    public function getVariations($productId): array
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get("{$this->apiUrl}/products/{$productId}/variations");

        return $response->json() ?? [];
    }
}
