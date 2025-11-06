<?php

declare(strict_types=1);

// Front Controller for the Error Logger API

require_once __DIR__ . '/../vendor/autoload.php';

// Load configuration
$config = require __DIR__ . '/../config/app.php';

// Set timezone
date_default_timezone_set($config['timezone'] ?? 'UTC');

// Create logger
$logger = \App\Logger\LoggerFactory::createLogger();

// Simple router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($uri === '/' && $method === 'GET') {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Error Logger API is running',
            'version' => $config['version']
        ]);
    } elseif ($uri === '/logs' && $method === 'GET') {
        $storage = new \App\Storage\FileStorage($config['log_file']);
        $logService = new \App\Service\LogService($storage);
        $controller = new \App\Controller\LogController($logService);
        $controller->index();
    } elseif (preg_match('/^\/logs\/(.+)$/', $uri, $matches) && $method === 'GET') {
        $id = $matches[1];
        $storage = new \App\Storage\FileStorage($config['log_file']);
        $logService = new \App\Service\LogService($storage);
        $controller = new \App\Controller\LogController($logService);
        $controller->show($id);
    } elseif (preg_match('/^\/logs\/(.+)$/', $uri, $matches) && $method === 'DELETE') {
        $id = $matches[1];
        $storage = new \App\Storage\FileStorage($config['log_file']);
        $logService = new \App\Service\LogService($storage);
        $controller = new \App\Controller\LogController($logService);
        $controller->delete($id);
    } else {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Endpoint not found'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Internal server error: ' . $e->getMessage()
    ]);
    
    // Log the error
    $logger->error('API Error', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
}