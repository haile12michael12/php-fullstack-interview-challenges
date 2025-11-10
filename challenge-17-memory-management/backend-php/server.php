<?php

/**
 * Simple PHP development server for the Memory Management Challenge
 */

// Check if PHP development server
if (php_sapi_name() !== 'cli-server') {
    die('This script should only be run with the built-in PHP development server');
}

// Route static files
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Serve static files directly
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js|ico|json)$/', $path)) {
    $file = __DIR__ . '/public' . $path;
    if (file_exists($file)) {
        return false; // Let PHP serve the file
    }
}

// Route all other requests to our API
require_once __DIR__ . '/public/index.php';