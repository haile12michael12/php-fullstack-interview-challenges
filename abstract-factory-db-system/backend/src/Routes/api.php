<?php

use Controllers\AuthController;
use Controllers\ConnectionController;
use Controllers\QueryController;

// Simple router
switch ($requestUri) {
    case '/api/auth/login':
        if ($method === 'POST') {
            $authController = new AuthController();
            $authController->login();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    case '/api/connection/test':
        if ($method === 'POST') {
            $connectionController = new ConnectionController();
            $connectionController->testConnection();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    case '/api/query/execute':
        if ($method === 'POST') {
            $queryController = new QueryController();
            $queryController->executeQuery();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
        break;
}