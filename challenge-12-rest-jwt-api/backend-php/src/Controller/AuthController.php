<?php

namespace App\Controller;

use SharedBackend\Http\Request;
use App\Http\Response;
use SharedBackend\Auth\AuthManager;

class AuthController
{
    private AuthManager $authManager;
    
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }
    
    /**
     * Register a new user
     */
    public function register(Request $request): Response
    {
        $data = $request->getParsedBody();
        
        // Validate required fields
        if (!isset($data['name'], $data['email'], $data['password'])) {
            return new Response([
                'error' => 'Validation failed',
                'message' => 'Name, email, and password are required'
            ], 400);
        }
        
        try {
            // In a real implementation, we would check if user exists
            // For now, we'll just create the user
            
            // Hash the password
            $hashedPassword = $this->authManager->hashPassword($data['password']);
            
            // In a real implementation, we would save the user to database
            // For now, we'll create a mock user
            $user = [
                'id' => uniqid(),
                'name' => $data['name'],
                'email' => $data['email']
            ];
            
            // In a real implementation, we would generate tokens using the auth manager
            // For now, we'll create mock tokens
            $accessToken = base64_encode(random_bytes(32));
            $refreshToken = base64_encode(random_bytes(32));
            
            return new Response([
                'message' => 'User registered successfully',
                'user' => $user,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken
            ], 201);
            
        } catch (\Exception $e) {
            return new Response([
                'error' => 'Registration failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Login user
     */
    public function login(Request $request): Response
    {
        $data = $request->getParsedBody();
        
        // Validate required fields
        if (!isset($data['email'], $data['password'])) {
            return new Response([
                'error' => 'Validation failed',
                'message' => 'Email and password are required'
            ], 400);
        }
        
        try {
            // In a real implementation, we would authenticate the user
            // For now, we'll create a mock authentication
            
            // Mock user data
            $user = [
                'id' => uniqid(),
                'name' => 'John Doe',
                'email' => $data['email']
            ];
            
            // In a real implementation, we would generate tokens using the auth manager
            // For now, we'll create mock tokens
            $accessToken = base64_encode(random_bytes(32));
            $refreshToken = base64_encode(random_bytes(32));
            
            return new Response([
                'message' => 'Login successful',
                'user' => $user,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken
            ]);
            
        } catch (\Exception $e) {
            return new Response([
                'error' => 'Login failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Refresh access token
     */
    public function refresh(Request $request): Response
    {
        $data = $request->getParsedBody();
        
        // Validate refresh token
        if (!isset($data['refresh_token'])) {
            return new Response([
                'error' => 'Validation failed',
                'message' => 'Refresh token is required'
            ], 400);
        }
        
        try {
            // In a real implementation, we would refresh the tokens using the auth manager
            // For now, we'll create mock tokens
            $accessToken = base64_encode(random_bytes(32));
            $refreshToken = base64_encode(random_bytes(32));
            
            return new Response([
                'message' => 'Token refreshed successfully',
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken
            ]);
            
        } catch (\Exception $e) {
            return new Response([
                'error' => 'Token refresh failed',
                'message' => $e->getMessage()
            ], 401);
        }
    }
    
    /**
     * Logout user
     */
    public function logout(Request $request): Response
    {
        // In a real implementation, you would invalidate the refresh token
        // For now, we'll just return a success response
        return new Response([
            'message' => 'Logout successful'
        ]);
    }
}