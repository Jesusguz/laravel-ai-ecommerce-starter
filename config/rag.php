<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default AI Providers (Powered by Prism)
    |--------------------------------------------------------------------------
    |
    | Define the underlying LLM provider for Prism. 
    | Native support: "openai", "anthropic", "gemini", "ollama".
    |
    */
    'embedding_provider' => env('RAG_EMBEDDING_PROVIDER', 'openai'),
    
    'chat_provider' => env('RAG_CHAT_PROVIDER', 'openai'),
];