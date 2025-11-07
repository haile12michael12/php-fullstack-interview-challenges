<?php

namespace App\Controller;

use App\Service\UserService;
use App\Helpers\ResponseHelper;

class UserController
{
    private UserService $userService;
    private ResponseHelper $responseHelper;
    
    public function __construct(
        UserService $userService,
        ResponseHelper $responseHelper
    ) {
        $this->userService = $userService;
        $this->responseHelper = $responseHelper;
    }

    public function getUser(array $params): string
    {
        $id = (int)($params['id'] ?? 0);
        
        if ($id <= 0) {
            return $this->responseHelper->json([
                'error' => 'Invalid user ID'
            ], 400);
        }
        
        try {
            $user = $this->userService->getUserById($id);
            
            if ($user === null) {
                return $this->responseHelper->json([
                    'error' => 'User not found'
                ], 404);
            }
            
            return $this->responseHelper->json($user);
        } catch (\Exception $e) {
            return $this->responseHelper->json([
                'error' => 'Failed to retrieve user',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateUser(array $params): string
    {
        $id = (int)($params['id'] ?? 0);
        
        if ($id <= 0) {
            return $this->responseHelper->json([
                'error' => 'Invalid user ID'
            ], 400);
        }
        
        $requestData = json_decode(file_get_contents('php://input'), true);
        
        if ($requestData === null) {
            return $this->responseHelper->json([
                'error' => 'Invalid JSON data'
            ], 400);
        }
        
        try {
            $success = $this->userService->updateUser($id, $requestData);
            
            if ($success) {
                return $this->responseHelper->json([
                    'message' => 'User updated successfully'
                ]);
            } else {
                return $this->responseHelper->json([
                    'error' => 'Failed to update user'
                ], 500);
            }
        } catch (\Exception $e) {
            return $this->responseHelper->json([
                'error' => 'Failed to update user',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}