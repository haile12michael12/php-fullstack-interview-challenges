<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Simple router for the API
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remove query string
$uri = strtok($uri, '?');

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight requests
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Route handling
switch ($uri) {
    case '/':
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Abstract Factory Challenge API']);
        break;
        
    case '/api/factory':
        if ($method === 'GET') {
            $controller = new \App\Http\Controller\FactoryController();
            $controller->index();
        }
        break;
        
    case '/api/factory/mysql':
        if ($method === 'GET') {
            $controller = new \App\Http\Controller\FactoryController();
            $controller->createMySql();
        }
        break;
        
    case '/api/factory/postgresql':
        if ($method === 'GET') {
            $controller = new \App\Http\Controller\FactoryController();
            $controller->createPostgreSql();
        }
        break;
        
    case '/api/factory/sqlite':
        if ($method === 'GET') {
            $controller = new \App\Http\Controller\FactoryController();
            $controller->createSqlite();
        }
        break;
        
    case '/api/factory/pool':
        if ($method === 'GET') {
            $controller = new \App\Http\Controller\FactoryController();
            $controller->connectionPool();
        }
        break;
        
    case '/api/auth/login':
        if ($method === 'POST') {
            $controller = new \App\Http\Controller\AuthController();
            $controller->login();
        }
        break;
        
    case '/api/auth/logout':
        if ($method === 'POST') {
            $controller = new \App\Http\Controller\AuthController();
            $controller->logout();
        }
        break;
        
    case '/api/auth/register':
        if ($method === 'POST') {
            $controller = new \App\Http\Controller\AuthController();
            $controller->register();
        }
        break;
        
    default:
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not Found']);
        break;
}

exit;