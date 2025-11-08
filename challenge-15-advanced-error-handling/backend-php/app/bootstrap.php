<?php

use App\Handler\ErrorHandler;
use App\Handler\ShutdownHandler;
use App\Utils\Config;

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
Config::loadEnv(__DIR__ . '/../.env');

// Set timezone
date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'UTC');

// Register error handler
$errorHandler = new ErrorHandler();

// Register shutdown handler
$shutdownHandler = new ShutdownHandler();
$shutdownHandler->register();

// Ensure storage directories exist
$storageDirs = [
    __DIR__ . '/../storage/logs',
    __DIR__ . '/../storage/cache'
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}