<?php

return [
    'servers' => [
        [
            'host' => $_ENV['MEMCACHED_HOST'] ?? '127.0.0.1',
            'port' => (int)($_ENV['MEMCACHED_PORT'] ?? 11211),
            'weight' => (int)($_ENV['MEMCACHED_WEIGHT'] ?? 0),
        ],
    ],
    'options' => [
        Memcached::OPT_COMPRESSION => true,
        Memcached::OPT_SERIALIZER => Memcached::SERIALIZER_PHP,
    ],
];