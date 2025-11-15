<?php

namespace Controllers;

use Services\AuthService;
use Core\Logger;

class AuthController
{
    private $authService;

    public function __construct()
    {
        $this->authService = AuthService::getInstance();
    }

    public function login()
    {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['username']) || !isset($input['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required parameters: username, password']);
            return;
        }
        
        $username = $input['username'];
        $password = $input['password'];
        
        if ($this->authService->authenticate($username, $password)) {
            $token = $this->authService->generateToken($username);
            echo json_encode([
                'success' => true,
                'token' => $token,
                'message' => 'Authentication successful'
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);
        }
    }
}