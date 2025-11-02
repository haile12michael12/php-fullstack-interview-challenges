<?php
// Simple JSON echo endpoint for development and testing
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$body = file_get_contents('php://input');
$data = json_decode($body, true);

if ($uri === '/echo' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = isset($data['text']) ? $data['text'] : '';
    // Basic processing to demonstrate server-side logic
    $reply = array('text' => 'Echo from PHP: ' . $text);
    echo json_encode($reply);
    exit;
}

// Fallback
http_response_code(404);
echo json_encode(['error' => 'Not Found']);
