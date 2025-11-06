<?php

declare(strict_types=1);

// Front Controller for the Image Processing API

require_once __DIR__ . '/../src/bootstrap.php';

// Get the request URI and method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Simple router
try {
    if ($uri === '/' && $method === 'GET') {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Image Processing API is running',
            'version' => '1.0.0'
        ]);
        exit;
    } elseif ($uri === '/upload' && $method === 'POST') {
        // Handle image upload
        $imageService = new \App\Service\ImageService(
            new \App\Image\ImageProcessor(),
            new \App\Image\ImageFilter(),
            new \App\Image\ImageOptimizer()
        );
        $controller = new \App\Controller\ImageController($imageService);
        $controller->upload();
    } elseif (preg_match('/^\/process\/(.+)$/', $uri, $matches) && $method === 'POST') {
        // Handle image processing
        $filename = $matches[1];
        $imageService = new \App\Service\ImageService(
            new \App\Image\ImageProcessor(),
            new \App\Image\ImageFilter(),
            new \App\Image\ImageOptimizer()
        );
        $controller = new \App\Controller\ImageController($imageService);
        $controller->process($filename);
    } elseif ($uri === '/batch' && $method === 'POST') {
        // Handle batch processing
        $batchProcessor = new \App\Service\BatchProcessor();
        $controller = new \App\Controller\BatchController($batchProcessor);
        $controller->processBatch();
    } elseif (preg_match('/^\/batch\/(.+)$/', $uri, $matches) && $method === 'GET') {
        // Get batch status
        $batchId = $matches[1];
        $batchProcessor = new \App\Service\BatchProcessor();
        $controller = new \App\Controller\BatchController($batchProcessor);
        $controller->getBatchStatus($batchId);
    } else {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => 'Endpoint not found'
        ]);
    }
} catch (\Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Internal server error: ' . $e->getMessage()
    ]);
}