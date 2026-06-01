<?php

namespace App\Services\Ingestors;

use App\Contracts\CatalogIngestorInterface;
use Illuminate\Support\Facades\Http;

class ShopifyIngestor implements CatalogIngestorInterface
{
    public function fetchProducts(): array
    {
        // This will be read from the .env file by whoever deploys the package.
        $shopUrl = config('services.shopify.url');
        $token = config('services.shopify.token');

        if (!$shopUrl || !$token) {
            return [];
        }

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $token,
        ])->get("https://{$shopUrl}/admin/api/2024-01/products.json");

        if ($response->failed()) {
            return [];
        }

        $shopifyProducts = $response->json('products');
        $standardizedProducts = [];

        foreach ($shopifyProducts as $product) {
            $standardizedProducts[] = [
                'sku' => $product['variants'][0]['sku'] ?? uniqid('SH-'),
                'name' => $product['title'],
                'description' => strip_tags($product['body_html'] ?? ''),
                'price' => (float) ($product['variants'][0]['price'] ?? 0),
                'in_stock' => ($product['variants'][0]['inventory_quantity'] ?? 0) > 0,
                'metadata' => [
                    'vendor' => $product['vendor'] ?? '',
                    'product_type' => $product['product_type'] ?? ''
                ]
            ];
        }

        return $standardizedProducts;
    }
}