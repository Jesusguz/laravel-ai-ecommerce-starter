<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Ingestors\CsvIngestor;
use App\Services\Ingestors\ShopifyIngestor;
use App\Models\Product;

class IngestCatalogCommand extends Command
{
    protected $signature = 'rag:ingest {--source=csv : The data source (csv or shopify)}';

    protected $description = 'Ingest product catalog from CSV or Shopify into the local database';

    public function handle()
    {
        $source = $this->option('source');
        
        $this->info("Starting data ingestion from: " . strtoupper($source));

        $ingestor = match($source) {
            'csv' => new CsvIngestor(),
            'shopify' => new ShopifyIngestor(),
            default => null,
        };

        if (!$ingestor) {
            $this->error("Unsupported source. Use --source=csv or --source=shopify.");
            return Command::FAILURE;
        }

        $products = $ingestor->fetchProducts();

        if (empty($products)) {
            $this->warn("No products found or failed to read the source.");
            return Command::FAILURE;
        }

        $this->info("Found " . count($products) . " products. Persisting to database...");

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
        $this->info('Catalog successfully ingested! Ready for vectorization.');

        return Command::SUCCESS;
    }
}