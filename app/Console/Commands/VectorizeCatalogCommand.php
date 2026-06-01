<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Contracts\EmbeddingProviderInterface;

class VectorizeCatalogCommand extends Command
{
    protected $signature = 'rag:vectorize';

    protected $description = 'Generate embeddings for all products using the configured AI provider';

    // We inject the Interface directly into the constructor!
    public function __construct(
        private EmbeddingProviderInterface $aiProvider
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->error("No products found in the database. Run 'php artisan rag:ingest' first.");
            return Command::FAILURE;
        }

        // Get the active provider name just for the console output
        $activeProvider = strtoupper(config('rag.embedding_provider'));
        $this->info("Generating embeddings for {$products->count()} products using {$activeProvider}...");

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            $textToVectorize = sprintf(
                "Product: %s. Description: %s. Price: $%s. In Stock: %s",
                $product->name,
                $product->description,
                $product->price,
                $product->in_stock ? 'Yes' : 'No'
            );

            try {
                // The command doesn't care if it's Gemini, OpenAI or Claude.
                // It just trusts the Interface.
                $vector = $this->aiProvider->generateEmbedding($textToVectorize);

                // TO-DO: Save $vector to Pinecone
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Failed to vectorize SKU {$product->sku}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Vectorization complete! The AI brain is ready.');

        return Command::SUCCESS;
    }
}