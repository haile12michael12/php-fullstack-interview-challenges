<?php

use Challenge02\Http\Controllers\AuthController;
use Challenge02\Http\Controllers\UserController;
use Challenge02\Http\Middleware\AuthMiddleware;
use Challenge02\Http\Middleware\CsrfMiddleware;

// Get container
$container = require_once __DIR__ . '/container.php';

// Extract controllers from container
$authController = $container[AuthController::class];
$userController = $container[UserController::class];

// Extract middleware from container
$authMiddleware = new AuthMiddleware($container[\Challenge02\Contracts\Auth\AuthInterface::class]);
$csrfMiddleware = new CsrfMiddleware();

// Simple router
$path = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Route definitions
switch ($path) {
    case '/api/auth/register':
        if ($method === 'POST') {
            // Apply CSRF middleware
            if ($csrfMiddleware->handle()) {
                try {
                    $result = $authController->register();
                    header('Content-Type: application/json');
                    echo json_encode($result);
                } catch (Exception $e) {
                    http_response_code(400);
                    echo json_encode(['error' => $e->getMessage()]);
                }
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    case '/api/auth/login':
        if ($method === 'POST') {
            // Apply CSRF middleware
            if ($csrfMiddleware->handle()) {
                try {
                    $result = $authController->login();
                    header('Content-Type: application/json');
                    echo json_encode($result);
                } catch (Exception $e) {
                    http_response_code(401);
                    echo json_encode(['error' => $e->getMessage()]);
                }
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    case '/api/auth/logout':
        if ($method === 'POST') {
            // Apply auth middleware
            if ($authMiddleware->handle()) {
                try {
                    $result = $authController->logout();
                    header('Content-Type: application/json');
                    echo json_encode(['message' => 'Logged out successfully']);
                } catch (Exception $e) {
                    http_response_code(400);
                    echo json_encode(['error' => $e->getMessage()]);
                }
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    case '/api/auth/password/reset/request':
        if ($method === 'POST') {
            try {
                $authController->requestPasswordReset();
                header('Content-Type: application/json');
                echo json_encode(['message' => 'Password reset email sent']);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    case '/api/auth/password/reset':
        if ($method === 'POST') {
            try {
                $result = $authController->resetPassword();
                header('Content-Type: application/json');
                echo json_encode(['message' => 'Password reset successfully']);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    case '/api/user/profile':
        if ($method === 'GET') {
            // Apply auth middleware
            if ($authMiddleware->handle()) {
                try {
                    $result = $userController->profile();
                    header('Content-Type: application/json');
                    echo json_encode($result);
                } catch (Exception $e) {
                    http_response_code(404);
                    echo json_encode(['error' => $e->getMessage()]);
                }
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
        break;
}