<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\CommerceAIEngineInterface;
use App\Services\AI\PrismAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the core AI engine interface to the Prism Adapter implementation
        // This enforces Hexagonal Architecture by decoupling the application from the underlying LLM package
        $this->app->bind(CommerceAIEngineInterface::class, PrismAdapter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}