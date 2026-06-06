<?php

return [
    'chat' => [
        'process_error' => 'An error occurred while processing your request.',
    ],
    'ai' => [
        'system_prompt' => 'You are an expert e-commerce sales assistant. Answer based strictly on the provided product catalog.',
        'catalog_header' => 'PRODUCT CATALOG',
    ],
    'errors' => [
        'ai' => [
            'embedding_dimension_error' => 'Embedding dimension mismatch: unable to normalize to 768 dimensions.',
            'model_not_configured' => 'No model configured for :type with provider :provider. Add it in config/rag.php or define :env_var in your .env.',
        ],
    ],
];