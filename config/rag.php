<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default AI Embedding Provider
    |--------------------------------------------------------------------------
    |
    | Supported: "openai", "gemini"
    |
    */
    'embedding_provider' => env('RAG_EMBEDDING_PROVIDER', 'gemini'),
];