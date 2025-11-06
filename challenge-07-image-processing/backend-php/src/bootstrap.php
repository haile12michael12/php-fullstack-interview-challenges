<?php

declare(strict_types=1);

// Bootstrap file for the Image Processing application

// Define base paths
define('ROOT_PATH', __DIR__ . '/..');
define('SRC_PATH', __DIR__);
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('CONFIG_PATH', SRC_PATH . '/Config');

// Load configuration
$config = require CONFIG_PATH . '/app.php';
$paths = require CONFIG_PATH . '/paths.php';

// Set timezone
date_default_timezone_set($config['timezone'] ?? 'UTC');

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Create necessary directories
if (!is_dir($config['upload_directory'])) {
    mkdir($config['upload_directory'], 0755, true);
}

if (!is_dir($config['optimized_directory'])) {
    mkdir($config['optimized_directory'], 0755, true);
}

if (!is_dir($config['temp_directory'])) {
    mkdir($config['temp_directory'], 0755, true);
}

if (!is_dir($paths['logs'])) {
    mkdir($paths['logs'], 0755, true);
}