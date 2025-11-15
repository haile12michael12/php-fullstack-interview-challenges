<?php

// Bootstrap script for setting up the application environment

// Load environment variables
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos($line, '#') === 0) {
            continue;
        }
        
        // Parse key-value pairs
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Set up error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set up timezone
date_default_timezone_set('UTC');

// Autoload
require_once __DIR__ . '/../vendor/autoload.php';

echo "Application environment bootstrapped successfully.\n";