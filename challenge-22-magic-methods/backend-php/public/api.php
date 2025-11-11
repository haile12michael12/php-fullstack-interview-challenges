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

// Define API routes
$app->get('/api/magic', [\App\Controller\MagicController::class, 'index']);
$app->get('/api/magic/fluent', [\App\Controller\MagicController::class, 'fluent']);
$app->get('/api/magic/proxy', [\App\Controller\MagicController::class, 'proxy']);
$app->get('/api/magic/interceptor', [\App\Controller\MagicController::class, 'interceptor']);

$app->get('/api/entities', [\App\Controller\EntityController::class, 'index']);
$app->get('/api/entities/users', [\App\Controller\EntityController::class, 'listUsers']);
$app->get('/api/entities/users/{id}', [\App\Controller\EntityController::class, 'getUser']);
$app->post('/api/entities/users', [\App\Controller\EntityController::class, 'createUser']);
$app->put('/api/entities/users/{id}', [\App\Controller\EntityController::class, 'updateUser']);
$app->delete('/api/entities/users/{id}', [\App\Controller\EntityController::class, 'deleteUser']);

$app->get('/api/query', [\App\Controller\QueryController::class, 'index']);
$app->get('/api/query/users', [\App\Controller\QueryController::class, 'queryUsers']);
$app->get('/api/query/posts', [\App\Controller\QueryController::class, 'queryPosts']);
$app->post('/api/query/custom', [\App\Controller\QueryController::class, 'customQuery']);

// Run the application
$app->run();