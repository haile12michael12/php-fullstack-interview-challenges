<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'Advanced Caching Challenge',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => (bool)($_ENV['APP_DEBUG'] ?? false),
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'UTC',
    'locale' => $_ENV['APP_LOCALE'] ?? 'en',
    'log_level' => $_ENV['APP_LOG_LEVEL'] ?? 'INFO',
    'cache_warm_on_startup' => (bool)($_ENV['CACHE_WARM_ON_STARTUP'] ?? false),
    'cache_warm_items' => [
        'users' => [1, 2, 3, 4, 5],
        'products' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        'categories' => ['electronics', 'books', 'clothing'],
    ],
];