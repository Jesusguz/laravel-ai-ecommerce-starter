<?php

namespace App\Services\EcomAdapters;

use App\Contracts\EcomAdapterInterface;
use Exception;

class CsvAdapter implements EcomAdapterInterface
{
    /**
     * Reads a local CSV file and normalizes its rows into the standardized catalog format.
     */
    public function fetchProducts(): array
    {
        // Use storage_path helper to get the absolute system path, bypassing virtual disks
        $filePath = storage_path('app/catalog.csv');

        // Validate file existence using native PHP
        if (!file_exists($filePath)) {
            throw new Exception("CSV file not found. Please ensure 'catalog.csv' exists exactly at: " . $filePath);
        }

        // Parse CSV file into an array
        $csvData = array_map('str_getcsv', file($filePath));
        
        // Extract headers (first row) to use as array keys
        $headers = array_shift($csvData);

        $products = [];

        foreach ($csvData as $row) {
            // Skip malformed rows where column count doesn't match header count
            if (count($headers) !== count($row)) {
                continue; 
            }
            
            // Combine headers with row values to create an associative array
            $item = array_combine($headers, $row);

            // Map CSV columns to the standardized EcomAdapterInterface format
            $products[] = [
                'sku'         => $item['sku'] ?? uniqid('csv_'),
                'name'        => $item['name'] ?? 'Unnamed Product',
                'description' => $item['description'] ?? '',
                'price'       => (float) ($item['price'] ?? 0),
                'in_stock'    => isset($item['in_stock']) ? filter_var($item['in_stock'], FILTER_VALIDATE_BOOLEAN) : true,
                'metadata'    => [
                    'category' => $item['category'] ?? 'General',
                    'source'   => 'csv_import'
                ]
            ];
        }

        return $products;
    }
}