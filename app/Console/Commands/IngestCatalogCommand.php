<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Contracts\EcomAdapterInterface;
use App\Services\EcomAdapters\ShopifyAdapter;
use App\Services\EcomAdapters\WooCommerceAdapter;
use App\Services\EcomAdapters\CsvAdapter;
use App\Services\EcomAdapters\AmazonAdapter;
use App\Services\EcomAdapters\ErpAdapter;

class IngestCatalogCommand extends Command
{
    // The signature allows the user to specify the origin platform via CLI
    protected $signature = 'rag:ingest {--platform=csv : The source platform (shopify, woocommerce, csv, amazon, erp)}';

    protected $description = 'Ingest and normalize catalog data from an external E-commerce platform into the local database';

    public function handle()
    {
        $platform = strtolower($this->option('platform'));

        $this->info("Initializing ingestion pipeline for platform: " . strtoupper($platform));

        // Runtime resolution of the requested E-commerce Adapter
        $adapter = match($platform) {
            'shopify' => new ShopifyAdapter(),
            'woocommerce' => new WooCommerceAdapter(),
            'csv' => new CsvAdapter(),
            'amazon' => new AmazonAdapter(),
            'erp' => new ErpAdapter(),
            default => null,
        };

        if (!$adapter instanceof EcomAdapterInterface) {
            $this->error("Unsupported platform or invalid adapter implementation.");
            $this->line("Available options: --platform=shopify | woocommerce | csv | amazon | erp");
            return Command::FAILURE;
        }

        $this->info("Fetching products from origin...");
        
        // The adapter executes the API calls and returns a standardized array
        $products = $adapter->fetchProducts();

        if (empty($products)) {
            $this->warn("No products retrieved from the source.");
            return Command::FAILURE;
        }

        $this->info("Found " . count($products) . " products. Persisting to local normalized database...");

    
        $bar = $this->output->createProgressBar(count($products));
        $bar->start();

        foreach ($products as $productData) {
        
            Product::updateOrCreate(
                ['sku' => $productData['sku']], 
                $productData
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Catalog successfully ingested and normalized! Ready for vectorization.');

        return Command::SUCCESS;
    }
}