<?php

namespace App\Services\EcomAdapters;

use App\Contracts\EcomAdapterInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class ShopifyAdapter implements EcomAdapterInterface
{
    /**
     * Fetches products from Shopify Admin API and normalizes the payload.
     * Requires SHOPIFY_STORE_URL and SHOPIFY_ACCESS_TOKEN in .env
     */
    public function fetchProducts(): array
    {
        $storeUrl = env('SHOPIFY_STORE_URL');
        $accessToken = env('SHOPIFY_ACCESS_TOKEN');

        if (!$storeUrl || !$accessToken) {
            throw new Exception("Shopify configuration missing in environment variables.");
        }

        // Shopify REST API endpoint for fetching active products
        $endpoint = rtrim($storeUrl, '/') . '/admin/api/2024-01/products.json?status=active';

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
            'Content-Type' => 'application/json',
        ])->get($endpoint);

        if ($response->failed()) {
            throw new Exception("Shopify API Error: " . $response->body());
        }

        $products = [];
        $shopifyData = $response->json('products') ?? [];

        foreach ($shopifyData as $item) {
            // Usually, Shopify holds the SKU and Price inside the first variant
            $firstVariant = $item['variants'][0] ?? null;
            
            if (!$firstVariant) {
                continue;
            }

            $products[] = [
                'sku'         => $firstVariant['sku'] ?? (string) $item['id'],
                'name'        => $item['title'] ?? 'Unnamed Product',
                'description' => strip_tags($item['body_html'] ?? ''),
                'price'       => (float) ($firstVariant['price'] ?? 0),
                'in_stock'    => ($firstVariant['inventory_quantity'] ?? 0) > 0,
                'metadata'    => [
                    'vendor'   => $item['vendor'] ?? '',
                    'category' => $item['product_type'] ?? '',
                    'source'   => 'shopify'
                ]
            ];
        }

        return $products;
    }
}