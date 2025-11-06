<?php

use App\Core\Bootstrap;

// Start session
session_start();

// Autoload dependencies
require_once __DIR__ . '/../vendor/autoload.php';

// Set timezone
date_default_timezone_set('UTC');

// Error reporting
if (isset($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_errors', 0);
}

// Create bootstrap instance
$bootstrap = new Bootstrap();

// Run the application
$bootstrap->run();