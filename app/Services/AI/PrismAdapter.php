<?php

namespace App\Services\AI;

use App\Contracts\CommerceAIEngineInterface;
use Prism\Prism\Facades\Prism;
use Prism\Prism\ValueObjects\Messages\UserMessage;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;

class PrismAdapter implements CommerceAIEngineInterface
{
    public function generateEmbedding(string $text): array
    {
        $model = $this->getModel('embedding');
        $provider = $this->resolveProvider($model);

        $response = Prism::embeddings()
            ->using($provider, $model)
            ->fromInput($text)
            ->generate();

        $vector = $response->embeddings[0]->embedding;

        // MRL slicing a 768 dimensiones
        if (count($vector) > 768) {
            $vector = array_slice($vector, 0, 768);
        }

        if (count($vector) !== 768) {
            throw new \RuntimeException(
                __('api.errors.ai.embedding_dimension_error')
            );
        }

        return $vector;
    }

    public function chat(array $messages, array $context = []): string
    {
        $model = $this->getModel('chat');
        $provider = $this->resolveProvider($model);

        $systemPrompt = __('api.ai.system_prompt');

        if (!empty($context)) {
            $systemPrompt .= "\n--- " . __('api.ai.catalog_header') . " ---\n";
            $systemPrompt .= json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $systemPrompt .= "\n-------------------------------\n";
        }

        $prismMessages = [];
        foreach ($messages as $msg) {
            if ($msg['role'] === 'user') {
                $prismMessages[] = new UserMessage($msg['content']);
            } elseif ($msg['role'] === 'assistant') {
                $prismMessages[] = new AssistantMessage($msg['content']);
            }
        }

        $response = Prism::text()
            ->using($provider, $model)
            ->withSystemPrompt($systemPrompt)
            ->withMessages($prismMessages)
            ->generate();

        return $response->text;
    }

    /**
     * Obtains the final model according to priority: explicit variable > provider default.
     */
    protected function getModel(string $type): string
    {
        $explicit = config("rag.{$type}_model");
        if ($explicit) {
            return $explicit;
        }

        $provider = config('rag.ai_provider', 'openai');
        $defaultModels = config("rag.providers.{$provider}");

        if (!$defaultModels || empty($defaultModels["{$type}_model"])) {
            throw new \RuntimeException(
                __('api.errors.ai.model_not_configured', [
                    'type' => $type,
                    'provider' => $provider,
                    'env_var' => 'RAG_' . strtoupper($type) . '_MODEL'
                ])
            );
        }

        return $defaultModels["{$type}_model"];
    }

    /**
     * Determine the Prism driver (gemini, openai, etc.) from the model name.
     */
    protected function resolveProvider(string $model): string
    {
        return match (true) {
            str_starts_with($model, 'gemini')   => 'gemini',
            str_starts_with($model, 'gpt')      => 'openai',
            str_starts_with($model, 'claude')   => 'anthropic',
            str_starts_with($model, 'llama')    => 'ollama',
            str_starts_with($model, 'mistral')  => 'mistral',
            str_starts_with($model, 'nomic')    => 'ollama',
            default                             => 'openai',
        };
    }
}