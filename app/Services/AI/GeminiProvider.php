<?php

namespace App\Services\AI;

use App\Contracts\EmbeddingProviderInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class GeminiProvider implements EmbeddingProviderInterface
{
    public function generateEmbedding(string $text): array
    {
        $apiKey = config('services.gemini.key');

        if (!$apiKey) {
            throw new Exception("Gemini API key is missing in .env");
        }

        // Endpoint for Gemini Text Embeddings
        $url = "https://generativelanguage.googleapis.com/v1beta/models/text-embedding-004:embedContent?key={$apiKey}";

        $response = Http::post($url, [
            'model' => 'models/text-embedding-004',
            'content' => [
                'parts' => [
                    ['text' => $text]
                ]
            ]
        ]);

        if ($response->failed()) {
            throw new Exception("Gemini API Error: " . $response->body());
        }

        // Gemini returns the array of numbers inside 'embedding.values'
        return $response->json('embedding.values');
    }
}