<?php

namespace App\Services\EcomAdapters;

use App\Contracts\EcomAdapterInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class ErpAdapter implements EcomAdapterInterface
{
    /**
     * Generic adapter template for custom Enterprise Resource Planning (ERP) systems.
     * Requires ERP_API_URL and ERP_API_KEY in .env
     */
    public function fetchProducts(): array
    {
        $erpUrl = env('ERP_API_URL');
        $erpKey = env('ERP_API_KEY');

        if (!$erpUrl) {
            throw new Exception("ERP API URL is not configured.");
        }

        // Make HTTP request to the proprietary ERP endpoint
        $response = Http::withToken($erpKey)->get($erpUrl . '/catalog/export');

        if ($response->failed()) {
            throw new Exception("Custom ERP API Error: " . $response->body());
        }

        $products = [];
        $erpData = $response->json('data') ?? [];

        foreach ($erpData as $item) {
            // Map the proprietary ERP fields to the standard internal format
            $products[] = [
                'sku'         => $item['item_code'] ?? uniqid('erp_'),
                'name'        => $item['item_name'] ?? 'Unknown Item',
                'description' => $item['details'] ?? '',
                'price'       => (float) ($item['retail_price'] ?? 0),
                'in_stock'    => ($item['warehouse_qty'] ?? 0) > 0,
                'metadata'    => [
                    'brand'  => $item['brand'] ?? 'Generic',
                    'source' => 'custom_erp'
                ]
            ];
        }

        return $products;
    }
}