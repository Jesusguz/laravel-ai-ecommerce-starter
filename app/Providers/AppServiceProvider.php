<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the interface to a specific implementation based on config
        $this->app->bind(EmbeddingProviderInterface::class, function ($app) {
            
            $provider = config('rag.embedding_provider');

            return match($provider) {
                'openai' => new OpenAIProvider(),
                'gemini' => new GeminiProvider(),
                default => throw new Exception("Unsupported AI Provider configured: {$provider}"),
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
