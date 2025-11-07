<?php

namespace App\Service;

use App\Cache\CacheManager;
use App\Exception\CacheException;

class UserService
{
    private CacheManager $cacheManager;
    
    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    public function getUserById(int $id): ?array
    {
        $cacheKey = "user_$id";
        
        try {
            // Try to get from cache first
            $user = $this->cacheManager->get($cacheKey);
            
            if ($user !== null) {
                return $user;
            }
        } catch (CacheException $e) {
            // Log the error but continue to fetch from database
            error_log("Cache error: " . $e->getMessage());
        }

        // Simulate database fetch
        $user = $this->fetchUserFromDatabase($id);
        
        if ($user !== null) {
            // Cache the result
            try {
                $this->cacheManager->set($cacheKey, $user, 3600); // Cache for 1 hour
            } catch (CacheException $e) {
                // Log the error but don't fail the request
                error_log("Failed to cache user: " . $e->getMessage());
            }
        }
        
        return $user;
    }

    public function updateUser(int $id, array $data): bool
    {
        // Simulate database update
        $success = $this->updateUserInDatabase($id, $data);
        
        if ($success) {
            // Invalidate cache
            $cacheKey = "user_$id";
            try {
                $this->cacheManager->delete($cacheKey);
            } catch (CacheException $e) {
                // Log the error but don't fail the request
                error_log("Failed to invalidate user cache: " . $e->getMessage());
            }
        }
        
        return $success;
    }

    private function fetchUserFromDatabase(int $id): ?array
    {
        // Simulate database fetch
        // In a real application, this would query a database
        if ($id <= 0) {
            return null;
        }
        
        return [
            'id' => $id,
            'name' => "User $id",
            'email' => "user$id@example.com",
            'created_at' => date('Y-m-d H:i:s')
        ];
    }

    private function updateUserInDatabase(int $id, array $data): bool
    {
        // Simulate database update
        // In a real application, this would update a database record
        return $id > 0;
    }
}