<?php

namespace App\Actions\Chat;

use App\Contracts\CommerceAIEngineInterface;
use App\Services\VectorDatabase\PineconeService;
use Illuminate\Support\Facades\Log;

class GenerateRagResponse
{
    public function __construct(
        private readonly CommerceAIEngineInterface $aiEngine,
        private readonly PineconeService $pinecone
    ) {}

    /**
     * Executes the standard non-streaming RAG flow.
     */
    public function execute(string $userMessage): string
    {
        $context = $this->buildContext($userMessage);

        $messages = [
            ['role' => 'user', 'content' => $userMessage]
        ];
Log::debug('Context sent to AI:', $context);
        return $this->aiEngine->chat($messages, $context);
    }

    /**
     * Generates embeddings and retrieves the context from the vector database.
     * Reusable for both standard chat and streaming responses.
     */
   public function buildContext(string $userMessage): array
    {
        $vector = $this->aiEngine->generateEmbedding($userMessage);
        $matches = $this->pinecone->query($vector, 3);

        Log::debug('Pinecone Matches:', $matches);

        return $this->extractValidContext($matches);
    }

    /**
     * Filters Pinecone matches based on a similarity score threshold.
     */
    private function extractValidContext(array $matches): array
    {
        $context = [];

        foreach ($matches as $match) {
            if (!empty($match['metadata'])) {
                $context[] = $match['metadata'];
            }
        }

        return $context;
    }

    /**
     * Provides access to the AI engine for streaming.
     */
    public function getAiEngine(): CommerceAIEngineInterface
    {
        return $this->aiEngine;
    }
}