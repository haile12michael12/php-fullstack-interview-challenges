<?php

return [
    'default' => 'file',
    'channels' => [
        'file' => [
            'driver' => 'file',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => 'debug',
            'formatter' => 'json',
        ],
        'database' => [
            'driver' => 'database',
            'table' => 'log_entries',
            'level' => 'info',
            'formatter' => 'json',
        ],
        'external' => [
            'driver' => 'external',
            'url' => 'https://api.example.com/logs',
            'api_key' => 'your-api-key',
            'level' => 'error',
            'formatter' => 'json',
        ],
    ],
    'formatters' => [
        'json' => \App\Logger\Formatter\JsonFormatter::class,
        'text' => \App\Logger\Formatter\TextFormatter::class,
        'html' => \App\Logger\Formatter\HtmlFormatter::class,
    ],
];