<?php

return [
    'default_provider' => env('AI_DEFAULT_PROVIDER', 'opencode'),

    'providers' => [
        'openai' => [
            'label' => 'OpenAI',
            'api_key' => env('OPENAI_API_KEY'),
            'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
            'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
            'timeout' => env('OPENAI_REQUEST_TIMEOUT', 60),
            'connect_timeout' => env('OPENAI_CONNECT_TIMEOUT', 30),

            // Modelos seleccionables desde /agent-config.
            // Formato: 'id-que-espera-la-api' => 'Etiqueta visible'.
            'models' => [
                'gpt-4o-mini' => 'GPT-4o mini',
                'gpt-4o' => 'GPT-4o',
            ],
        ],

        'opencode' => [
            'label' => 'OpenCode',
            'api_key' => env('OPENCODE_API_KEY'),
            'base_url' => env('OPENCODE_BASE_URL', 'https://opencode.ai/zen/go/v1'),
            'model' => env('OPENCODE_MODEL', 'deepseek-v4-flash'),
            'timeout' => env('OPENCODE_REQUEST_TIMEOUT', 60),
            'connect_timeout' => env('OPENCODE_CONNECT_TIMEOUT', 30),

            // Modelos disponibles en la suscripción de OpenCode Go.
            // Formato: 'id-que-espera-la-api' => 'Etiqueta visible'.
            // IMPORTANTE: si algún modelo devuelve error 400/404 del proveedor,
            // ajusta aquí el ID (la clave) para que coincida exactamente con el de tu suscripción.
            'models' => [
                'glm-5.1' => 'GLM-5.1',
                'glm-5' => 'GLM-5',
                'kimi-k2.7-code' => 'Kimi K2.7 Code',
                'kimi-k2.6' => 'Kimi K2.6',
                'mimo-v2.5-pro' => 'MiMo-V2.5-Pro',
                'mimo-v2.5' => 'MiMo-V2.5',
                'qwen3.7-max' => 'Qwen3.7 Max',
                'qwen3.7-plus' => 'Qwen3.7 Plus',
                'qwen3.6-plus' => 'Qwen3.6 Plus',
                'minimax-m2.7' => 'MiniMax M2.7',
                'minimax-m3' => 'MiniMax M3',
                'deepseek-v4-pro' => 'DeepSeek V4 Pro',
                'deepseek-v4-flash' => 'DeepSeek V4 Flash',
            ],
        ],
    ],
];