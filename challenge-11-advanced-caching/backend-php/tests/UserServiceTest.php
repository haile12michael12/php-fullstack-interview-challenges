<?php

namespace Tests;

use App\Cache\CacheManager;
use App\Cache\ApcuAdapter;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private CacheManager $cacheManager;

    protected function setUp(): void
    {
        $this->cacheManager = new CacheManager();
        
        // Add APCu adapter if available
        if (function_exists('apcu_enabled') && apcu_enabled()) {
            $apcuAdapter = new ApcuAdapter();
            $this->cacheManager->addAdapter('apcu', $apcuAdapter, 3);
        }
        
        $this->userService = new UserService($this->cacheManager);
    }

    public function testGetUserById(): void
    {
        $userId = 1;
        $user = $this->userService->getUserById($userId);
        
        $this->assertIsArray($user);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('name', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertEquals($userId, $user['id']);
    }

    public function testGetNonExistentUser(): void
    {
        $userId = -1;
        $user = $this->userService->getUserById($userId);
        
        $this->assertNull($user);
    }

    public function testUpdateUser(): void
    {
        $userId = 1;
        $userData = [
            'name' => 'Updated User',
            'email' => 'updated@example.com'
        ];
        
        $result = $this->userService->updateUser($userId, $userData);
        $this->assertTrue($result);
    }

    public function testUpdateNonExistentUser(): void
    {
        $userId = -1;
        $userData = [
            'name' => 'Updated User',
            'email' => 'updated@example.com'
        ];
        
        $result = $this->userService->updateUser($userId, $userData);
        $this->assertFalse($result);
    }
}