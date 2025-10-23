<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Application;
use Symfony\Component\Dotenv\Dotenv;

// Load environment variables
$dotenv = new Dotenv();
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv->load(__DIR__ . '/../.env');
}

// Create and run the application
$app = new Application();
$app->run();