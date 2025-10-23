<?php

namespace App\Controller;

use SharedBackend\Http\Request;
use App\Http\Response;

class ProfileController
{
    /**
     * Get user profile
     */
    public function get(Request $request): Response
    {
        // In a real implementation, we would get user ID from token
        // For now, we'll return mock data
        $profile = [
            'id' => '1',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'created_at' => '2023-01-01T00:00:00Z',
            'updated_at' => '2023-01-01T00:00:00Z'
        ];
        
        return new Response([
            'message' => 'Profile retrieved successfully',
            'data' => $profile
        ]);
    }
    
    /**
     * Update user profile
     */
    public function update(Request $request): Response
    {
        $data = $request->getParsedBody();
        
        // In a real implementation, we would get user ID from token
        // For now, we'll return mock data
        $profile = [
            'id' => '1',
            'name' => $data['name'] ?? 'John Doe',
            'email' => $data['email'] ?? 'john@example.com',
            'created_at' => '2023-01-01T00:00:00Z',
            'updated_at' => date('c')
        ];
        
        return new Response([
            'message' => 'Profile updated successfully',
            'data' => $profile
        ]);
    }
}