<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Services\AI\OpenAIProvider;
use App\Services\AI\GeminiProvider;

class VectorizeCatalogCommand extends Command
{
    // Added an option to choose the AI provider
    protected $signature = 'rag:vectorize {--ai=gemini : The AI provider (openai or gemini)}';

    protected $description = 'Generate embeddings for all products and prepare them for Vector DB';

    public function handle()
    {
        $aiChoice = $this->option('ai');
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->error("No products found in the database. Run 'php artisan rag:ingest' first.");
            return Command::FAILURE;
        }

        // Resolve the correct AI Provider
        $aiProvider = match($aiChoice) {
            'openai' => new OpenAIProvider(),
            'gemini' => new GeminiProvider(),
            default => null,
        };

        if (!$aiProvider) {
            $this->error("Unsupported AI provider. Use --ai=openai or --ai=gemini");
            return Command::FAILURE;
        }

        $this->info("Generating embeddings for {$products->count()} products using " . strtoupper($aiChoice) . "...");

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            $textToVectorize = sprintf(
                "Product: %s. Description: %s. Price: $%s. In Stock: %s. Details: %s",
                $product->name,
                $product->description,
                $product->price,
                $product->in_stock ? 'Yes' : 'No',
                json_encode($product->metadata)
            );

            try {
                // Here we call the Interface method, regardless of the provider!
                $vector = $aiProvider->generateEmbedding($textToVectorize);
                
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