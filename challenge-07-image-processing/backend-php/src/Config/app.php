<?php

return [
    'app_name' => 'Image Processing Service',
    'version' => '1.0.0',
    'debug' => true,
    'timezone' => 'UTC',
    'upload_directory' => __DIR__ . '/../../public/uploads/',
    'optimized_directory' => __DIR__ . '/../../public/optimized/',
    'temp_directory' => __DIR__ . '/../../public/temp/',
    'max_upload_size' => 5242880, // 5MB
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
    'default_quality' => 80,
    'presets_file' => __DIR__ . '/presets.php',
];