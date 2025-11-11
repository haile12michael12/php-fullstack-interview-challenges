<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Application;
use Symfony\Component\Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Initialize and run the application
$app = new Application();
$app->run();