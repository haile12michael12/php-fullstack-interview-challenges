<?php

use App\Utils\Config;
use App\Handler\GlobalErrorMiddleware;
use App\Middleware\RequestLoggingMiddleware;
use App\Middleware\ErrorReportingMiddleware;
use App\Logger\CorrelationIdMiddleware;
use App\Controller\HealthController;
use App\Controller\ErrorTestController;

require_once __DIR__ . '/../app/bootstrap.php';

// Load configuration
$configFiles = [
    __DIR__ . '/../config/app.php',
    __DIR__ . '/../config/logging.php',
    __DIR__ . '/../config/database.php',
    __DIR__ . '/../config/services.php',
    __DIR__ . '/../config/recovery.php',
];

foreach ($configFiles as $file) {
    if (file_exists($file)) {
        Config::load($file);
    }
}

// Simple router for demonstration
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Remove query string
if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}

// Route handling
try {
    // Apply middleware
    $middlewareStack = [
        new CorrelationIdMiddleware(),
        new ErrorReportingMiddleware(),
        new RequestLoggingMiddleware(),
        new GlobalErrorMiddleware()
    ];
    
    // Simple routing
    if ($uri === '/' || $uri === '/health') {
        $controller = new HealthController();
        $response = $controller->health();
    } elseif ($uri === '/test/validation') {
        $controller = new ErrorTestController();
        $response = $controller->testValidation();
    } elseif ($uri === '/test/database') {
        $controller = new ErrorTestController();
        $response = $controller->testDatabase();
    } elseif ($uri === '/test/authentication') {
        $controller = new ErrorTestController();
        $response = $controller->testAuthentication();
    } elseif ($uri === '/test/authorization') {
        $controller = new ErrorTestController();
        $response = $controller->testAuthorization();
    } elseif ($uri === '/test/external') {
        $controller = new ErrorTestController();
        $response = $controller->testExternalService();
    } elseif ($uri === '/test/external/fallback') {
        $controller = new ErrorTestController();
        $response = $controller->testExternalServiceWithFallback();
    } elseif ($uri === '/test/circuit-breaker') {
        $controller = new ErrorTestController();
        $response = $controller->getCircuitBreakerStatus();
    } elseif ($uri === '/test/generic') {
        $controller = new ErrorTestController();
        $response = $controller->testGeneric();
    } else {
        // 404 response
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => true,
            'message' => 'Endpoint not found'
        ]);
        exit;
    }
    
    // Send response
    http_response_code($response['status_code'] ?? 200);
    
    foreach ($response['headers'] ?? [] as $header => $value) {
        header("$header: $value");
    }
    
    echo $response['body'] ?? '';
    
} catch (Exception $e) {
    // Handle any uncaught exceptions
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => true,
        'message' => 'Internal server error',
        'correlation_id' => uniqid()
    ]);
}