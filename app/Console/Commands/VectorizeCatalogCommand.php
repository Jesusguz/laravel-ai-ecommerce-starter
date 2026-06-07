<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Contracts\CommerceAIEngineInterface;
use App\Services\VectorDatabase\PineconeService;

class VectorizeCatalogCommand extends Command
{
    // The signature no longer asks for the AI provider. It is injected automatically.
    protected $signature = 'rag:vectorize';

    protected $description = 'Generate embeddings using the configured AI engine and persist them to Pinecone Vector DB';

    /**
     * Dependency Injection of the AI Engine (Prism) and Vector DB (Pinecone).
     */
    public function __construct(
        private CommerceAIEngineInterface $aiEngine,
        private PineconeService $pinecone
    ) {
        parent::__construct();
    }

    public function handle()
    {
        // 1. Fetch all products regardless of their origin (Shopify, WooCommerce, CSV)
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->error("No products found in the local database. Execute 'php artisan rag:ingest' first.");
            return Command::FAILURE;
        }

        $activeProvider = strtoupper(config('rag.embedding_provider'));
        $this->info("Generating embeddings via {$activeProvider} (Prism Engine) and persisting to PINECONE...");

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            // 2. Format the product data for optimal semantic retrieval
            $textToVectorize = sprintf(
                "Product: %s. Description: %s. Price: $%s. In Stock: %s",
                $product->name,
                $product->description,
                $product->price,
                $product->in_stock ? 'Yes' : 'No'
            );

            try {
                // 3. Delegate generation to the Hexagonal AI Port (PrismAdapter)
                $vector = $this->aiEngine->generateEmbedding($textToVectorize);
                
                // 4. Upsert vector array and metadata payload to Pinecone index
                $this->pinecone->upsert(
                id: $product->sku,
                vector: $vector,
                metadata: [
                    'name'        => $product->name,
                    'description' => $product->description,
                    'price'       => (float) $product->price,
                    'in_stock'    => (bool) $product->in_stock,
                ]
            );
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Vectorization failed for SKU {$product->sku}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Vectorization successfully completed. AI brain and vector memory are synchronized.');

        return Command::SUCCESS;
    }
}