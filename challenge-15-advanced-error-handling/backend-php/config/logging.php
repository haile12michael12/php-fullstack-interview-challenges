<?php

return [
    'default' => $_ENV['LOG_CHANNEL'] ?? 'stack',
    
    'deprecations' => [
        'channel' => $_ENV['LOG_DEPRECATIONS_CHANNEL'] ?? 'null',
        'trace' => false,
    ],
    
    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],
        
        'single' => [
            'driver' => 'single',
            'path' => __DIR__ . '/../storage/logs/app.log',
            'level' => $_ENV['LOG_LEVEL'] ?? 'debug',
        ],
        
        'daily' => [
            'driver' => 'daily',
            'path' => __DIR__ . '/../storage/logs/app.log',
            'level' => $_ENV['LOG_LEVEL'] ?? 'debug',
            'days' => 14,
        ],
        
        'error' => [
            'driver' => 'daily',
            'path' => __DIR__ . '/../storage/logs/error.log',
            'level' => 'error',
            'days' => 30,
        ],
        
        'exception' => [
            'driver' => 'daily',
            'path' => __DIR__ . '/../storage/logs/exception.log',
            'level' => 'error',
            'days' => 30,
        ],
        
        'emergency' => [
            'driver' => 'daily',
            'path' => __DIR__ . '/../storage/logs/emergency.log',
            'level' => 'emergency',
            'days' => 30,
        ],
        
        'null' => [
            'driver' => 'monolog',
            'handler' => 'Monolog\Handler\NullHandler',
        ],
    ],
];