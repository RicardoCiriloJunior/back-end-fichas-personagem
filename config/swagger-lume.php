<?php

return [
    'api' => [
        'title' => 'API de Fichas de Personagem',
    ],

    'routes' => [
        'api' => '/api/documentation',
        'docs' => '/docs',
        'oauth2_callback' => '/api/oauth2-callback',
        'assets' => '/swagger-ui-assets',
        'middleware' => [
            'api' => [],
            'asset' => [],
            'docs' => [],
            'oauth2_callback' => [],
        ],
    ],

    'paths' => [
        'docs' => storage_path('api-docs'),
        'docs_json' => 'api-docs.json',
        'docs_yaml' => 'api-docs.yaml',
        'format_to_use_for_docs' => 'json',
        'annotations' => base_path('app'),
        'excludes' => [],
        'base' => env('L5_SWAGGER_BASE_PATH', null),
        'views' => base_path('resources/views/vendor/swagger-lume'),
    ],

    'security' => [],
    'generate_always' => env('SWAGGER_GENERATE_ALWAYS', false),
    'generate_yaml_copy' => env('SWAGGER_LUME_GENERATE_YAML_COPY', true),
    'swagger_version' => env('SWAGGER_VERSION', '3.0'),
    'proxy' => false,
    'additional_config_url' => null,
    'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),
    'validator_url' => null,
    'constants' => [],
    'force_https' => env(
        'SWAGGER_LUME_FORCE_HTTPS',
        env('APP_ENV', 'production') === 'production'
    ),
];
