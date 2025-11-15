<?php

use App\Messenger\Message\PublishNewsMessage;
use App\Messenger\Handler\PublishNewsHandler;

return [
    'messenger' => [
        'transports' => [
            'sync' => 'sync://',
        ],
        'routing' => [
            PublishNewsMessage::class => 'sync',
        ],
        'handlers' => [
            PublishNewsMessage::class => [PublishNewsHandler::class],
        ],
    ],
];