<?php

// Test bootstrap file
require_once __DIR__ . '/../app/bootstrap.php';

// Load test configuration
$configFiles = [
    __DIR__ . '/../config/app.php',
    __DIR__ . '/../config/logging.php',
    __DIR__ . '/../config/database.php',
    __DIR__ . '/../config/services.php',
    __DIR__ . '/../config/recovery.php',
];

foreach ($configFiles as $file) {
    if (file_exists($file)) {
        \App\Utils\Config::load($file);
    }
}

// Set test environment
putenv('APP_ENV=testing');
putenv('APP_DEBUG=true');

// Ensure test storage directories exist
$testDirs = [
    __DIR__ . '/../storage/logs',
    __DIR__ . '/../storage/cache',
];

foreach ($testDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}