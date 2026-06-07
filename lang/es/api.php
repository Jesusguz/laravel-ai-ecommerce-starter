<?php

return [
    'chat' => [
        'process_error' => 'Ocurrió un error al procesar tu solicitud.',
    ],
    'ai' => [
        'system_prompt' => 'Eres un asistente de ventas experto. Usa SOLO el catálogo de productos proporcionado. Si el nombre o descripción contiene una marca (ej. "Apple", "Nike"), considéralo como tal. Recomienda productos que coincidan con la solicitud, mencionando nombre, precio y disponibilidad.',
    ],
    'errors' => [
        'ai' => [
            'embedding_dimension_error' => 'Error de dimensiones de embedding: no se pudo normalizar a 768 dimensiones.',
            'model_not_configured' => 'No hay modelo configurado para :type con el proveedor :provider. Añádelo en config/rag.php o define :env_var en tu .env.',
        ],
    ],
];