<?php

namespace App\Actions\Chat;

use App\Contracts\CommerceAIEngineInterface;
use App\Services\VectorDatabase\PineconeService;
use Illuminate\Support\Facades\Log; // <-- Importamos Log

class GenerateRagResponse
{
    public function __construct(
        private readonly CommerceAIEngineInterface $aiEngine,
        private readonly PineconeService $pinecone
    ) {}

    public function execute(string $userMessage): string
    {
        $vector = $this->aiEngine->generateEmbedding($userMessage);
        $matches = $this->pinecone->query($vector, 3);

        // Guardamos en el log de Laravel lo que devuelve Pinecone para inspeccionarlo
        Log::debug('Pinecone Matches:', $matches);

        $context = $this->extractValidContext($matches);

        $messages = [
            ['role' => 'user', 'content' => $userMessage]
        ];

        return $this->aiEngine->chat($messages, $context);
    }

    private function extractValidContext(array $matches): array
    {
        $context = [];
        
        foreach ($matches as $match) {
            // Bajamos el umbral a 0.50 temporalmente para asegurar que pase la información
            if (isset($match['score']) && $match['score'] >= 0.50 && !empty($match['metadata'])) {
                $context[] = $match['metadata'];
            }
        }

        return $context;
    }
}