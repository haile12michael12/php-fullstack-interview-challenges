<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set default timezone
date_default_timezone_set('UTC');

// Define constants
define('MAX_POLLING_TIME', 30); // seconds
define('DEFAULT_TIMEOUT', 10); // seconds