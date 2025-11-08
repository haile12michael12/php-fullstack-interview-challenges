<?php

return [
    'retry' => [
        'default_max_attempts' => $_ENV['RETRY_MAX_ATTEMPTS'] ?? 3,
        'default_delay' => $_ENV['RETRY_DELAY'] ?? 1000,
        'default_backoff_multiplier' => $_ENV['RETRY_BACKOFF_MULTIPLIER'] ?? 2.0,
        'default_jitter' => filter_var($_ENV['RETRY_JITTER'] ?? true, FILTER_VALIDATE_BOOLEAN),
    ],
    
    'circuit_breaker' => [
        'default_failure_threshold' => $_ENV['CIRCUIT_BREAKER_FAILURE_THRESHOLD'] ?? 5,
        'default_timeout' => $_ENV['CIRCUIT_BREAKER_TIMEOUT'] ?? 60,
        'default_half_open_attempts' => $_ENV['CIRCUIT_BREAKER_HALF_OPEN_ATTEMPTS'] ?? 1,
    ],
    
    'fallback' => [
        'default_cache_ttl' => $_ENV['FALLBACK_CACHE_TTL'] ?? 300,
        'default_cache_strategy' => $_ENV['FALLBACK_CACHE_STRATEGY'] ?? 'memory',
    ],
    
    'rate_limiting' => [
        'enabled' => filter_var($_ENV['RATE_LIMITING_ENABLED'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'max_requests' => $_ENV['RATE_LIMITING_MAX_REQUESTS'] ?? 1000,
        'time_window' => $_ENV['RATE_LIMITING_TIME_WINDOW'] ?? 3600,
    ],
];