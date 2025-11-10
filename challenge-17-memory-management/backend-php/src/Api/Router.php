<?php

namespace App\Api;

/**
 * Simple Router - Routes API requests to appropriate controllers
 */
class Router
{
    /**
     * Route the request to appropriate handler
     */
    public static function handleRequest(string $path, string $method): void
    {
        // Set JSON header
        header('Content-Type: application/json');
        
        try {
            // Manually include the controller
            require_once __DIR__ . '/MemoryController.php';
            require_once __DIR__ . '/../Memory/Profiler.php';
            require_once __DIR__ . '/../Memory/Monitor.php';
            require_once __DIR__ . '/../Memory/Analyzer.php';
            require_once __DIR__ . '/../Leak/Detector.php';
            require_once __DIR__ . '/../Optimizer/DataStructureOptimizer.php';
            require_once __DIR__ . '/../Monitoring/ReportGenerator.php';
            require_once __DIR__ . '/../Utils/MemoryFormatter.php';
            
            $controller = new MemoryController();
            
            switch ($path) {
                case '/api/memory/profile':
                    if ($method === 'GET') {
                        echo json_encode($controller->profile());
                    } else {
                        self::sendError(405, 'Method not allowed');
                    }
                    break;
                    
                case '/api/memory/analyze':
                    if ($method === 'POST') {
                        echo json_encode($controller->analyze());
                    } else {
                        self::sendError(405, 'Method not allowed');
                    }
                    break;
                    
                case '/api/memory/leaks':
                    if ($method === 'GET') {
                        echo json_encode($controller->detectLeaks());
                    } else {
                        self::sendError(405, 'Method not allowed');
                    }
                    break;
                    
                case '/api/memory/optimize':
                    if ($method === 'POST') {
                        echo json_encode($controller->optimize());
                    } else {
                        self::sendError(405, 'Method not allowed');
                    }
                    break;
                    
                case '/api/memory/trends':
                    if ($method === 'GET') {
                        echo json_encode($controller->trends());
                    } else {
                        self::sendError(405, 'Method not allowed');
                    }
                    break;
                    
                default:
                    self::sendError(404, 'Endpoint not found');
                    break;
            }
        } catch (\Exception $e) {
            self::sendError(500, 'Internal server error: ' . $e->getMessage());
        }
    }
    
    /**
     * Send error response
     */
    private static function sendError(int $code, string $message): void
    {
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'message' => $message,
            'code' => $code
        ]);
    }
}