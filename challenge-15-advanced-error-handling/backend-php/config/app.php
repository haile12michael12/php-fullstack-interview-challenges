<?php

return [
    'name' => 'Advanced Error Handling Challenge',
    'version' => '1.0.0',
    'env' => $_ENV['APP_ENV'] ?? 'development',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'UTC',
    'locale' => $_ENV['APP_LOCALE'] ?? 'en',
    'fallback_locale' => $_ENV['APP_FALLBACK_LOCALE'] ?? 'en',
    'key' => $_ENV['APP_KEY'] ?? '',
    'cipher' => 'AES-256-CBC',
    
    'logging' => [
        'default' => $_ENV['LOG_CHANNEL'] ?? 'stack',
        'level' => $_ENV['LOG_LEVEL'] ?? 'debug',
        'channels' => [
            'stack' => [
                'driver' => 'stack',
                'channels' => ['single'],
                'ignore_exceptions' => false,
            ],
            'single' => [
                'driver' => 'single',
                'path' => __DIR__ . '/../storage/logs/app.log',
                'level' => 'debug',
            ],
            'daily' => [
                'driver' => 'daily',
                'path' => __DIR__ . '/../storage/logs/app.log',
                'level' => 'debug',
                'days' => 14,
            ],
        ],
    ],
    
    'error_handling' => [
        'report_errors' => filter_var($_ENV['ERROR_REPORT'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'display_errors' => filter_var($_ENV['DISPLAY_ERRORS'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'log_errors' => filter_var($_ENV['LOG_ERRORS'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'error_log' => __DIR__ . '/../storage/logs/error.log',
    ],
];