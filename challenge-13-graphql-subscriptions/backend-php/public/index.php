<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GraphQL\GraphQL;
use GraphQL\Server\StandardServer;
use GraphQL\Server\Helper;
use App\GraphQL\GraphQLService;
use Symfony\Component\Dotenv\Dotenv;

// Load environment variables
$dotenv = new Dotenv();
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv->load(__DIR__ . '/../.env');
}

// Handle HTTP requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Serve GraphQL Playground for GET requests
    serveGraphqlPlayground();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle GraphQL requests
    handleGraphqlRequest();
} else {
    // Handle WebSocket connections for subscriptions
    handleWebSocketConnection();
}

function serveGraphqlPlayground() {
    // Serve the GraphQL Playground HTML
    header('Content-Type: text/html');
    echo <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>GraphQL Playground</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/graphql-playground-react/build/static/css/index.css" />
    <link rel="shortcut icon" href="https://cdn.jsdelivr.net/npm/graphql-playground-react/build/favicon.png" />
    <script src="https://cdn.jsdelivr.net/npm/graphql-playground-react/build/static/js/middleware.js"></script>
</head>
<body>
    <div id="root">
        <style>
            body {
                background-color: rgb(23, 42, 58);
                font-family: Open Sans, sans-serif;
                height: 90vh;
            }
            #root {
                height: 100%;
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .loading {
                font-size: 32px;
                font-weight: 200;
                color: rgba(255, 255, 255, .6);
                margin-left: 20px;
            }
            img {
                width: 78px;
                height: 78px;
            }
            .title {
                font-weight: 400;
            }
        </style>
        <img src="https://cdn.jsdelivr.net/npm/graphql-playground-react/build/logo.png" alt="">
        <div class="loading"> Loading
            <span class="title">GraphQL Playground</span>
        </div>
    </div>
    <script>
        window.addEventListener('load', function (event) {
            GraphQLPlayground.init(document.getElementById('root'), {
                endpoint: '/graphql'
            });
        });
    </script>
</body>
</html>
HTML;
}

function handleGraphqlRequest() {
    // Get request data
    $input = json_decode(file_get_contents('php://input'), true);
    
    $query = $input['query'] ?? '';
    $variables = $input['variables'] ?? null;
    
    // Create GraphQL service
    $graphqlService = new GraphQLService();
    
    // Execute query
    $result = $graphqlService->executeQuery($query, $variables);
    
    // Send response
    header('Content-Type: application/json');
    echo json_encode($result);
}

function handleWebSocketConnection() {
    // Handle WebSocket connections for subscriptions
    // This would typically be handled by a separate WebSocket server
    http_response_code(426);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Upgrade Required',
        'message' => 'WebSocket connections should be made to the WebSocket endpoint'
    ]);
}