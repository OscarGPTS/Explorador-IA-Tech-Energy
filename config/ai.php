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
        ],

        'opencode' => [
            'label' => 'OpenCode',
            'api_key' => env('OPENCODE_API_KEY'),
            'base_url' => env('OPENCODE_BASE_URL', 'https://opencode.ai/zen/go/v1'),
            'model' => env('OPENCODE_MODEL', 'deepseek-v4-flash'),
            'timeout' => env('OPENCODE_REQUEST_TIMEOUT', 60),
            'connect_timeout' => env('OPENCODE_CONNECT_TIMEOUT', 30),
        ],
    ],
];