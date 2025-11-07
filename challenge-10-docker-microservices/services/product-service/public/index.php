<?php

use App\Presentation\Routes\Router;

require_once __DIR__ . '/../vendor/autoload.php';

// Initialize the application
$container = require_once __DIR__ . '/../src/bootstrap.php';

// Get the router from the container
$router = $container->get(Router::class);

// Handle the request
$router->dispatch();