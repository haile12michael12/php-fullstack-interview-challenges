<?php

use App\Infrastructure\Http\Middleware\CorsMiddleware;
use App\Infrastructure\Http\Middleware\JsonBodyParserMiddleware;

// Apply middleware
$corsMiddleware = new CorsMiddleware();
$corsMiddleware->handle();

$jsonBodyParserMiddleware = new JsonBodyParserMiddleware();
$jsonBodyParserMiddleware->handle();

// Route definitions
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($uri === '/api/preview' && $method === 'POST') {
        $container = require __DIR__ . '/../../../bootstrap.php';
        $controller = $container->get(\App\Infrastructure\Http\Controllers\PreviewController::class);
        $controller->preview();
    } elseif ($uri === '/api/export' && $method === 'POST') {
        $container = require __DIR__ . '/../../../bootstrap.php';
        $controller = $container->get(\App\Infrastructure\Http\Controllers\ExportController::class);
        $controller->export();
    } else {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Endpoint not found']);
    }
} catch (\Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Internal server error: ' . $e->getMessage()]);
}