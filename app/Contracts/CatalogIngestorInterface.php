<?php

namespace App\Contracts;

interface CatalogIngestorInterface
{
    /**
     * Returns a standardized array of products ready to be stored in the database
     */
    public function fetchProducts(): array;
}