<?php

return [
    'chat' => [
        'process_error' => 'An error occurred while processing your request.',
    ],
    'ai' => [
        'system_prompt' => 'You are an expert e-commerce sales assistant. Use ONLY the provided product catalog to answer. If a product name or description contains a brand (like "Apple", "Nike"), consider it as such. Recommend products that match the user request, mentioning name, price, and stock.',
        'catalog_header' => 'PRODUCT CATALOG',
    ],
    'errors' => [
        'ai' => [
            'embedding_dimension_error' => 'Embedding dimension mismatch: unable to normalize to 768 dimensions.',
            'model_not_configured' => 'No model configured for :type with provider :provider. Add it in config/rag.php or define :env_var in your .env.',
        ],
    ],
];