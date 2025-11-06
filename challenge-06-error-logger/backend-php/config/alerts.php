<?php

return [
    'enabled' => true,
    'channels' => [
        'email' => [
            'enabled' => false,
            'to' => 'admin@example.com',
            'from' => 'logger@example.com',
        ],
        'webhook' => [
            'enabled' => false,
            'url' => 'https://hooks.slack.com/services/YOUR/SLACK/WEBHOOK',
        ],
        'sms' => [
            'enabled' => false,
            'to' => '+1234567890',
            'provider' => 'twilio',
        ],
    ],
    'thresholds' => [
        'error_count' => 10,
        'time_window' => 3600, // 1 hour
        'levels' => ['error', 'critical', 'alert', 'emergency'],
    ],
];