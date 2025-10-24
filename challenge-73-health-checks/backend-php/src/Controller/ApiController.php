<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;

class ApiController
{
    /**
     * Get all users
     */
    public function getUsers(Request $request): Response
    {
        // In a real implementation, we would fetch users from database
        // For now, we'll return mock data
        $users = [
            [
                'id' => '1',
                'name' => 'John Doe',
                'email' => 'john@example.com'
            ],
            [
                'id' => '2',
                'name' => 'Jane Smith',
                'email' => 'jane@example.com'
            ]
        ];
        
        return new Response([
            'message' => 'Users retrieved successfully',
            'data' => $users
        ]);
    }
    
    /**
     * Get user by ID
     */
    public function getUser(Request $request): Response
    {
        // In a real implementation, we would fetch user from database
        // For now, we'll return mock data
        $user = [
            'id' => '1',
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ];
        
        return new Response([
            'message' => 'User retrieved successfully',
            'data' => $user
        ]);
    }
    
    /**
     * Create a new user
     */
    public function createUser(Request $request): Response
    {
        $data = $request->getParsedBody();
        
        // Validate required fields
        if (!isset($data['name'], $data['email'])) {
            return new Response([
                'error' => 'Validation failed',
                'message' => 'Name and email are required'
            ], 400);
        }
        
        // In a real implementation, we would save user to database
        // For now, we'll create mock user
        $user = [
            'id' => uniqid(),
            'name' => $data['name'],
            'email' => $data['email']
        ];
        
        return new Response([
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }
}