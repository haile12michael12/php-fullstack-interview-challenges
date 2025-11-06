<?php

return [
    'default' => 'file',
    'disks' => [
        'file' => [
            'driver' => 'file',
            'path' => __DIR__ . '/../logs',
        ],
        'database' => [
            'driver' => 'database',
            'connection' => 'mysql',
        ],
        'external' => [
            'driver' => 'external',
            'url' => 'https://api.example.com/storage',
            'api_key' => 'your-api-key',
        ],
    ],
    'rotation' => [
        'enabled' => true,
        'max_file_size' => 10485760, // 10MB
        'max_age_days' => 30,
        'compress' => true,
    ],
];