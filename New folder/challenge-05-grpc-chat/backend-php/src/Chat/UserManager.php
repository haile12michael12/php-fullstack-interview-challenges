<?php

namespace App\Chat;

use Chat\UserInfo;

/**
 * UserManager - Handles user presence and status
 */
class UserManager
{
    private $users = [];
    private $storageFile;
    
    public function __construct(string $storageFile = null)
    {
        $this->storageFile = $storageFile ?? __DIR__ . '/../../data/users.json';
        $this->loadUsers();
    }
    
    /**
     * Add or update a user
     */
    public function addUser(string $userId, string $username): void
    {
        $this->users[$userId] = [
            'user_id' => $userId,
            'username' => $username,
            'last_active' => time()
        ];
        
        $this->saveUsers();
    }
    
    /**
     * Remove a user
     */
    public function removeUser(string $userId): void
    {
        if (isset($this->users[$userId])) {
            unset($this->users[$userId]);
            $this->saveUsers();
        }
    }
    
    /**
     * Update user's last active time
     */
    public function updateActivity(string $userId): void
    {
        if (isset($this->users[$userId])) {
            $this->users[$userId]['last_active'] = time();
            $this->saveUsers();
        }
    }
    
    /**
     * Get all active users
     */
    public function getActiveUsers(int $inactiveThreshold = 300): array
    {
        $now = time();
        $activeUsers = [];
        
        foreach ($this->users as $userId => $userData) {
            if ($now - $userData['last_active'] <= $inactiveThreshold) {
                $activeUsers[$userId] = $userData;
            }
        }
        
        return $activeUsers;
    }
    
    /**
     * Get user by ID
     */
    public function getUser(string $userId): ?array
    {
        return $this->users[$userId] ?? null;
    }
    
    /**
     * Convert user data to UserInfo object
     */
    public function arrayToUserInfo(array $userData): UserInfo
    {
        $userInfo = new UserInfo();
        $userInfo->setUserId($userData['user_id']);
        $userInfo->setUsername($userData['username']);
        $userInfo->setLastActive($userData['last_active']);
        
        return $userInfo;
    }
    
    /**
     * Load users from storage
     */
    private function loadUsers(): void
    {
        if (file_exists($this->storageFile)) {
            $data = file_get_contents($this->storageFile);
            $this->users = json_decode($data, true) ?? [];
        } else {
            // Ensure directory exists
            $dir = dirname($this->storageFile);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $this->users = [];
        }
    }
    
    /**
     * Save users to storage
     */
    private function saveUsers(): void
    {
        file_put_contents($this->storageFile, json_encode($this->users));
    }
}