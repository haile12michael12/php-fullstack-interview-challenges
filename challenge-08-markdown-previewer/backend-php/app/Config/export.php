<?php

return [
    'default_format' => 'html',
    'allowed_formats' => ['html', 'pdf'],
    'html' => [
        'template' => __DIR__ . '/../../templates/export.html',
        'styles' => []
    ],
    'pdf' => [
        'orientation' => 'portrait',
        'format' => 'A4',
        'margin' => [
            'top' => 10,
            'right' => 10,
            'bottom' => 10,
            'left' => 10
        ]
    ]
];