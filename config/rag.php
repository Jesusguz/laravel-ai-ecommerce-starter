<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Provider Selection
    |--------------------------------------------------------------------------
    |
    |
    */
    'ai_provider' => env('RAG_AI_PROVIDER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | Provider Model Defaults
    |--------------------------------------------------------------------------
    */
    'providers' => [
        'gemini' => [
            'chat_model' => 'gemini-2.5-flash',
            'embedding_model' => 'gemini-embedding-001',
        ],
        'openai' => [
            'chat_model' => 'gpt-4o-mini',
            'embedding_model' => 'text-embedding-3-small',
        ],
        'anthropic' => [
            'chat_model' => 'claude-3-haiku-20240307',
            'embedding_model' => null,
        ],
        'ollama' => [
            'chat_model' => 'llama3',
            'embedding_model' => 'nomic-embed-text',
        ],
        'mistral' => [
            'chat_model' => 'mistral-small-latest',
            'embedding_model' => 'mistral-embed',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Explicit Model Overrides (Advanced Mode)
    |--------------------------------------------------------------------------
    |
    */
    'chat_model' => env('RAG_CHAT_MODEL'),
    'embedding_model' => env('RAG_EMBEDDING_MODEL'),
];