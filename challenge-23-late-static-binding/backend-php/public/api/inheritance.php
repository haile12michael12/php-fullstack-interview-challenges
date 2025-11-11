<?php

require_once __DIR__ . '/../../src/Bootstrap/app.php';

use App\Http\Controllers\InheritanceController;

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$controller = new InheritanceController();

// Route based on request method and URI
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remove query string
$uri = strtok($uri, '?');

// Remove base path
$basePath = '/api/inheritance';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

switch ($uri) {
    case '/':
    case '':
        if ($method === 'GET') {
            $controller->index();
        }
        break;
        
    case '/concepts':
        if ($method === 'GET') {
            $controller->getConcepts();
        }
        break;
        
    case '/examples':
        if ($method === 'GET') {
            $controller->getExamples();
        }
        break;
        
    case '/factory':
        if ($method === 'POST') {
            $controller->createWithFactory();
        }
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
}

exit;