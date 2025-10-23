<?php

namespace App\Controller;

use SharedBackend\Http\Request;
use App\Http\Response;

class UserController
{
    /**
     * Get all users
     */
    public function getAll(Request $request): Response
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
    public function getById(Request $request): Response
    {
        $id = $request->getQuery('id', $request->getParsedBody()['id'] ?? null);
        
        if (!$id) {
            return new Response([
                'error' => 'Validation failed',
                'message' => 'User ID is required'
            ], 400);
        }
        
        // In a real implementation, we would fetch user from database
        // For now, we'll return mock data
        $user = [
            'id' => $id,
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
    public function create(Request $request): Response
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
    
    /**
     * Update user
     */
    public function update(Request $request): Response
    {
        $id = $request->getQuery('id', $request->getParsedBody()['id'] ?? null);
        $data = $request->getParsedBody();
        
        if (!$id) {
            return new Response([
                'error' => 'Validation failed',
                'message' => 'User ID is required'
            ], 400);
        }
        
        // In a real implementation, we would update user in database
        // For now, we'll create mock user
        $user = [
            'id' => $id,
            'name' => $data['name'] ?? 'John Doe',
            'email' => $data['email'] ?? 'john@example.com'
        ];
        
        return new Response([
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }
    
    /**
     * Delete user
     */
    public function delete(Request $request): Response
    {
        $id = $request->getQuery('id', $request->getParsedBody()['id'] ?? null);
        
        if (!$id) {
            return new Response([
                'error' => 'Validation failed',
                'message' => 'User ID is required'
            ], 400);
        }
        
        // In a real implementation, we would delete user from database
        // For now, we'll just return success
        return new Response([
            'message' => 'User deleted successfully'
        ]);
    }
}