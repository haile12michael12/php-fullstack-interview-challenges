<?php

namespace App\Http\Controller;

class AuthController
{
    public function login()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Simple validation
        if (empty($input['username']) || empty($input['password'])) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Username and password are required'
            ], 400);
        }
        
        // In a real implementation, you would validate credentials against a database
        // For this demo, we'll accept any non-empty credentials
        if ($input['username'] && $input['password']) {
            $token = $this->generateToken($input['username']);
            
            return $this->jsonResponse([
                'status' => 'success',
                'message' => 'Login successful',
                'token' => $token
            ]);
        }
        
        return $this->jsonResponse([
            'status' => 'error',
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function logout()
    {
        // In a real implementation, you would invalidate the token
        return $this->jsonResponse([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }

    public function register()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Simple validation
        if (empty($input['username']) || empty($input['password']) || empty($input['email'])) {
            return $this->jsonResponse([
                'status' => 'error',
                'message' => 'Username, password, and email are required'
            ], 400);
        }
        
        // In a real implementation, you would create a user in the database
        // For this demo, we'll just return success
        return $this->jsonResponse([
            'status' => 'success',
            'message' => 'User registered successfully',
            'user' => [
                'username' => $input['username'],
                'email' => $input['email']
            ]
        ], 201);
    }

    protected function generateToken($username)
    {
        // In a real implementation, you would use a proper JWT library
        // For this demo, we'll create a simple token
        return base64_encode(json_encode([
            'username' => $username,
            'exp' => time() + 3600 // 1 hour
        ]));
    }

    protected function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}