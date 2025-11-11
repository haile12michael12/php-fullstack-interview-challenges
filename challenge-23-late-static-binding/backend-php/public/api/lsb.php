<?php

require_once __DIR__ . '/../../src/Bootstrap/app.php';

use App\Http\Controllers\LsbController;

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

$controller = new LsbController();

// Route based on request method and URI
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remove query string
$uri = strtok($uri, '?');

// Remove base path
$basePath = '/api/lsb';
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Extract ID from URI if present
$id = null;
if (preg_match('/\/(\d+)$/', $uri, $matches)) {
    $id = $matches[1];
    $uri = preg_replace('/\/\d+$/', '', $uri);
}

switch ($uri) {
    case '/':
    case '':
        if ($method === 'GET') {
            $controller->index();
        }
        break;
        
    case '/users':
        if ($method === 'GET') {
            $controller->getUsers();
        } elseif ($method === 'POST') {
            $controller->createUser();
        }
        break;
        
    case '/users/' . $id:
        if ($method === 'GET' && $id) {
            $controller->getUser($id);
        }
        break;
        
    case '/posts':
        if ($method === 'GET') {
            $controller->getPosts();
        } elseif ($method === 'POST') {
            $controller->createPost();
        }
        break;
        
    case '/posts/' . $id:
        if ($method === 'GET' && $id) {
            $controller->getPost($id);
        }
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
}

exit;