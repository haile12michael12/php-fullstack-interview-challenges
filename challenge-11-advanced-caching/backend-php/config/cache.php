<?php

return [
    'default_ttl' => (int)($_ENV['CACHE_TTL'] ?? 3600),
    'layers' => [
        'apcu' => [
            'enabled' => extension_loaded('apcu') && apcu_enabled(),
            'priority' => 3,
        ],
        'redis' => [
            'enabled' => extension_loaded('redis'),
            'priority' => 2,
        ],
        'memcached' => [
            'enabled' => extension_loaded('memcached'),
            'priority' => 1,
        ],
    ],
    'namespaces' => [
        'users' => 'user_',
        'products' => 'product_',
        'categories' => 'category_',
        'sessions' => 'session_',
    ],
    'prefix' => $_ENV['CACHE_PREFIX'] ?? 'app_',
];