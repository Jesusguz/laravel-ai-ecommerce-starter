<?php

namespace App\Services\EcomAdapters;

use App\Contracts\EcomAdapterInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class WooCommerceAdapter implements EcomAdapterInterface
{
    /**
     * Fetches products from WooCommerce REST API v3.
     * Requires WC_STORE_URL, WC_CONSUMER_KEY, and WC_CONSUMER_SECRET in .env
     */
    public function fetchProducts(): array
    {
        $storeUrl = env('WC_STORE_URL');
        $consumerKey = env('WC_CONSUMER_KEY');
        $consumerSecret = env('WC_CONSUMER_SECRET');

        if (!$storeUrl || !$consumerKey || !$consumerSecret) {
            throw new Exception("WooCommerce configuration missing in environment variables.");
        }

        $endpoint = rtrim($storeUrl, '/') . '/wp-json/wc/v3/products?status=publish&per_page=100';

        $response = Http::withBasicAuth($consumerKey, $consumerSecret)->get($endpoint);

        if ($response->failed()) {
            throw new Exception("WooCommerce API Error: " . $response->body());
        }

        $products = [];
        $wooData = $response->json() ?? [];

        foreach ($wooData as $item) {
            $products[] = [
                'sku'         => $item['sku'] ?: (string) $item['id'],
                'name'        => $item['name'] ?? 'Unnamed Product',
                'description' => strip_tags($item['description'] ?? ''),
                'price'       => (float) ($item['price'] ?? 0),
                'in_stock'    => ($item['stock_status'] ?? 'instock') === 'instock',
                'metadata'    => [
                    'categories' => array_column($item['categories'] ?? [], 'name'),
                    'source'     => 'woocommerce'
                ]
            ];
        }

        return $products;
    }
}