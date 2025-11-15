<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Component\HttpRequest;
use App\Component\HttpResponse;
use Symfony\Component\Dotenv\Dotenv;

// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = new Dotenv();
    $dotenv->load(__DIR__ . '/../.env');
}

// Get the request method and URI
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

// Remove query string from URI
if (strpos($uri, '?') !== false) {
    $uri = substr($uri, 0, strpos($uri, '?'));
}

// Create HTTP request object
$request = new HttpRequest(
    $method,
    $uri,
    getallheaders(),
    file_get_contents('php://input'),
    $_GET
);

// Load routes
$routes = require __DIR__ . '/../routes/api.php';

// Find matching route
$routeKey = "$method $uri";

if (isset($routes[$routeKey])) {
    // Execute the route handler
    $handler = $routes[$routeKey];
    $response = $handler($request);
    
    // Send the response
    http_response_code($response->getStatusCode());
    foreach ($response->getHeaders() as $name => $value) {
        header("$name: $value");
    }
    echo $response->getBody();
} else {
    // Handle 404
    $response = new HttpResponse(404, ['Content-Type' => 'application/json'], json_encode([
        'error' => 'Not Found',
        'message' => 'The requested endpoint was not found.'
    ]));
    
    http_response_code($response->getStatusCode());
    foreach ($response->getHeaders() as $name => $value) {
        header("$name: $value");
    }
    echo $response->getBody();
}