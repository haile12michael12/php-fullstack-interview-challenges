<?php

return [
    'app_name' => 'Markdown Previewer',
    'version' => '1.0.0',
    'debug' => true,
    'timezone' => 'UTC',
    'storage_path' => __DIR__ . '/../../storage',
    'log_path' => __DIR__ . '/../../logs/app.log',
    'allowed_export_formats' => ['html', 'pdf'],
    'default_export_format' => 'html',
];