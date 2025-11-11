<?php

/**
 * Development server router
 * Serves static files and routes API requests to index.php
 */

$publicPath = __DIR__ . '/public';
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// If the file exists in the public directory, serve it directly
if ($uri !== '/' && file_exists($publicPath . $uri)) {
    return false;
}

// Otherwise, route to the application
require_once $publicPath . '/index.php';