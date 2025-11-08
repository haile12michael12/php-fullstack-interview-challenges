<?php

// Built-in PHP server routing script
// This script handles routing for the built-in PHP server

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Front controller pattern
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    // Serve static files directly
    return false;
}

// Route all other requests to index.php
require_once __DIR__ . '/public/index.php';