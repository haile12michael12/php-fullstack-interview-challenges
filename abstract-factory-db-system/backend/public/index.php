<?php

require_once __DIR__ . '/../bootstrap.php';

// Front controller - handles all incoming requests
$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remove query string from request URI
if (($pos = strpos($requestUri, '?')) !== false) {
    $requestUri = substr($requestUri, 0, $pos);
}

// Route the request
require_once __DIR__ . '/../src/Routes/api.php';