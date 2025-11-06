<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set default timezone
date_default_timezone_set('UTC');

// Define constants
define('MAX_FILE_SIZE', 5242880); // 5MB
define('UPLOAD_PATH', __DIR__ . '/../../uploads');
define('QUARANTINE_PATH', __DIR__ . '/../../quarantine');
define('LOG_FILE', __DIR__ . '/../../app.log');

// Create necessary directories
if (!is_dir(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}

if (!is_dir(QUARANTINE_PATH)) {
    mkdir(QUARANTINE_PATH, 0755, true);
}