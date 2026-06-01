<?php

namespace App\Contracts;

interface EmbeddingProviderInterface
{
    /**
     * Takes a text string and returns an array of floats.
     */
    public function generateEmbedding(string $text): array;
}