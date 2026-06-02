<?php

namespace App\Contracts;

interface EcomAdapterInterface
{
    /**
     * Fetches products from the external E-commerce platform and normalizes them.
     *
     * @return array<int, array{
     *     sku: string,
     *     name: string,
     *     description: string,
     *     price: float,
     *     in_stock: bool,
     *     metadata: array
     * }>
     */
    public function fetchProducts(): array;
}