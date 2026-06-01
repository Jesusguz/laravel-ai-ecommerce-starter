<?php

namespace App\Services\Ingestors;

use App\Contracts\CatalogIngestorInterface;

class CsvIngestor implements CatalogIngestorInterface
{
    public function fetchProducts(): array
    {
        $path = storage_path('app/catalog.csv');
        $products = [];

        if (!file_exists($path)) {
            // throw a friendly exception
            return []; 
        }

        if (($handle = fopen($path, "r")) !== FALSE) {
            // We ignore the first line
            $headers = fgetcsv($handle, 1000, ","); 
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $products[] = [
                    'sku' => $data[0] ?? uniqid(),
                    'name' => $data[1] ?? 'Sin Nombre',
                    'description' => $data[2] ?? '',
                    'price' => (float) ($data[3] ?? 0),
                    'in_stock' => ($data[4] ?? 'yes') === 'yes',
                    'metadata' => ['category' => $data[5] ?? 'General']
                ];
            }
            fclose($handle);
        }

        return $products;
    }
}