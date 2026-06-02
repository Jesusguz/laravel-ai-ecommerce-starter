<?php

namespace App\Contracts;

interface CommerceAIEngineInterface
{
    /**
     * Generates mathematical vector representations (embeddings) from input text.
     *
     * @param string $text The raw text to be vectorized.
     * @return array<float> The resulting high-dimensional vector.
     */
    public function generateEmbedding(string $text): array;

    /**
     * Executes RAG-based chat completions using the provided catalog context.
     *
     * @param array $messages The conversation history.
     * @param array $context Additional business or catalog context injected via RAG.
     * @return string The LLM generated response.
     */
    public function chat(array $messages, array $context = []): string;
}