<?php

require_once __DIR__ . '/../src/bootstrap.php';

use Challenge03\LongPolling\Server;
use Challenge03\Exception\TimeoutException;

// Create server instance
$server = new Server();

// Get request method
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

// Simple router
switch ($path) {
    case '/api/poll':
        if ($method === 'GET') {
            handlePollRequest($server);
        } else {
            sendErrorResponse(405, 'Method not allowed');
        }
        break;
        
    case '/api/notify':
        if ($method === 'POST') {
            handleNotificationRequest($server);
        } else {
            sendErrorResponse(405, 'Method not allowed');
        }
        break;
        
    case '/api/status':
        if ($method === 'GET') {
            handleStatusRequest($server);
        } else {
            sendErrorResponse(405, 'Method not allowed');
        }
        break;
        
    case '/api/subscribe':
        if ($method === 'POST') {
            handleSubscribeRequest($server);
        } else {
            sendErrorResponse(405, 'Method not allowed');
        }
        break;
        
    default:
        sendErrorResponse(404, 'Endpoint not found');
        break;
}

function handlePollRequest(Server $server): void
{
    $clientId = $_GET['client_id'] ?? uniqid();
    $categories = isset($_GET['categories']) ? explode(',', $_GET['categories']) : [];
    
    try {
        $response = $server->handlePollRequest($clientId, $categories);
        sendSuccessResponse($response);
    } catch (TimeoutException $e) {
        sendErrorResponse(408, $e->getMessage());
    } catch (Exception $e) {
        sendErrorResponse(500, $e->getMessage());
    }
}

function handleNotificationRequest(Server $server): void
{
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        sendErrorResponse(400, 'Invalid JSON data');
        return;
    }
    
    $result = $server->handleNotification($input);
    
    if ($result) {
        sendSuccessResponse(['status' => 'success', 'message' => 'Notification sent']);
    } else {
        sendErrorResponse(500, 'Failed to send notification');
    }
}

function handleStatusRequest(Server $server): void
{
    $status = $server->getServerStatus();
    sendSuccessResponse($status);
}

function handleSubscribeRequest(Server $server): void
{
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['client_id']) || !isset($input['categories'])) {
        sendErrorResponse(400, 'Missing required fields: client_id, categories');
        return;
    }
    
    $result = $server->subscribeClient($input['client_id'], $input['categories']);
    
    if ($result) {
        sendSuccessResponse(['status' => 'success', 'message' => 'Subscription updated']);
    } else {
        sendErrorResponse(500, 'Failed to update subscription');
    }
}

function sendSuccessResponse(array $data): void
{
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode($data);
}

function sendErrorResponse(int $code, string $message): void
{
    header('Content-Type: application/json');
    http_response_code($code);
    echo json_encode(['error' => $message]);
}