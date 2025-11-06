<?php

require_once __DIR__ . '/../../vendor/autoload.php';

// Set error reporting
if ($_ENV['APP_DEBUG'] ?? false) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_errors', 0);
}

// Load routes
require_once __DIR__ . '/routes.php';