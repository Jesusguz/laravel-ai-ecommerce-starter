<?php

namespace App\Services\AI;

use App\Contracts\EmbeddingProviderInterface;
use OpenAI\Laravel\Facades\OpenAI;
use Exception;

class OpenAIProvider implements EmbeddingProviderInterface
{
    public function generateEmbedding(string $text): array
    {
        $response = OpenAI::embeddings()->create([
            'model' => 'text-embedding-3-small',
            'input' => $textToVectorize,
        ]);

        return $response->embeddings[0]->embedding;
    }
}