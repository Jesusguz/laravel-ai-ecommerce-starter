<?php

namespace App\Services\AI;

use App\Contracts\CommerceAIEngineInterface;
use Prism\Prism\Facades\Prism;

class PrismAdapter implements CommerceAIEngineInterface
{
    public function generateEmbedding(string $text): array
    {
        $provider = config('rag.embedding_provider', 'gemini');
        
        // model
        $model = $provider === 'gemini' ? 'gemini-embedding-001' : 'text-embedding-3-small';

        $response = Prism::embeddings()
            ->using($provider, $model)
            ->fromInput($text)
            ->generate();

        return $response->embeddings[0]->embedding;
    }

    public function chat(array $messages, array $context = []): string
    {
        $provider = config('rag.chat_provider', 'gemini');
        
        // Using the latest stable model based on the newest API documentation
        $model = $provider === 'gemini' ? 'gemini-3.5-flash' : 'gpt-4o-mini';

        $response = Prism::text()
            ->using($provider, $model)
            ->withSystemPrompt("You are an expert E-commerce sales assistant. Base your answers strictly on the provided product context.")
            ->withMessages($messages)
            ->generate();

        return $response->text;
    }
}