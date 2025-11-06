<?php

return [
    'thumbnail' => [
        [
            'type' => 'resize',
            'width' => 150,
            'height' => 150
        ],
        [
            'type' => 'optimize',
            'options' => [
                'quality' => 70
            ]
        ]
    ],
    'medium' => [
        [
            'type' => 'resize',
            'width' => 800,
            'height' => 600
        ],
        [
            'type' => 'optimize',
            'options' => [
                'quality' => 80
            ]
        ]
    ],
    'large' => [
        [
            'type' => 'resize',
            'width' => 1200,
            'height' => 900
        ],
        [
            'type' => 'optimize',
            'options' => [
                'quality' => 85
            ]
        ]
    ]
];