<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Model\User;
use App\Model\Post;

class EntityController
{
    public function index(Request $request): Response
    {
        return new Response([
            'message' => 'Entity Management',
            'endpoints' => [
                'GET /api/entities/users' => 'List all users',
                'GET /api/entities/users/{id}' => 'Get user by ID',
                'POST /api/entities/users' => 'Create a new user',
                'PUT /api/entities/users/{id}' => 'Update user',
                'DELETE /api/entities/users/{id}' => 'Delete user',
            ]
        ]);
    }

    public function listUsers(Request $request): Response
    {
        try {
            $users = User::all();
            $userData = array_map(function ($user) {
                return $user->toArray();
            }, $users);
            
            return new Response([
                'status' => 'success',
                'data' => $userData
            ]);
        } catch (\Exception $e) {
            return new Response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getUser(Request $request, array $params): Response
    {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                return new Response([
                    'status' => 'error',
                    'message' => 'User ID is required'
                ], 400);
            }
            
            $user = User::find($id);
            if (!$user) {
                return new Response([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }
            
            return new Response([
                'status' => 'success',
                'data' => $user->toArray()
            ]);
        } catch (\Exception $e) {
            return new Response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createUser(Request $request): Response
    {
        try {
            $data = $request->getParsedBody();
            
            $user = new User();
            $user->fill($data);
            
            if ($user->save()) {
                return new Response([
                    'status' => 'success',
                    'data' => $user->toArray(),
                    'message' => 'User created successfully'
                ], 201);
            }
            
            return new Response([
                'status' => 'error',
                'message' => 'Failed to create user'
            ], 500);
        } catch (\Exception $e) {
            return new Response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateUser(Request $request, array $params): Response
    {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                return new Response([
                    'status' => 'error',
                    'message' => 'User ID is required'
                ], 400);
            }
            
            $user = User::find($id);
            if (!$user) {
                return new Response([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }
            
            $data = $request->getParsedBody();
            $user->fill($data);
            
            if ($user->save()) {
                return new Response([
                    'status' => 'success',
                    'data' => $user->toArray(),
                    'message' => 'User updated successfully'
                ]);
            }
            
            return new Response([
                'status' => 'error',
                'message' => 'Failed to update user'
            ], 500);
        } catch (\Exception $e) {
            return new Response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteUser(Request $request, array $params): Response
    {
        try {
            $id = $params['id'] ?? null;
            if (!$id) {
                return new Response([
                    'status' => 'error',
                    'message' => 'User ID is required'
                ], 400);
            }
            
            $user = User::find($id);
            if (!$user) {
                return new Response([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }
            
            if ($user->delete()) {
                return new Response([
                    'status' => 'success',
                    'message' => 'User deleted successfully'
                ]);
            }
            
            return new Response([
                'status' => 'error',
                'message' => 'Failed to delete user'
            ], 500);
        } catch (\Exception $e) {
            return new Response([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function __call(string $method, array $parameters)
    {
        // Handle dynamic action methods
        if (strpos($method, 'action') === 0) {
            $action = lcfirst(substr($method, 6));
            return $this->$action(...$parameters);
        }
        
        throw new \BadMethodCallException("Method {$method} does not exist.");
    }

    public function __get(string $property)
    {
        return $this->$property ?? null;
    }

    public function __set(string $property, $value): void
    {
        $this->$property = $value;
    }

    public function __isset(string $property): bool
    {
        return isset($this->$property);
    }

    public function __unset(string $property): void
    {
        unset($this->$property);
    }

    public function __toString(): string
    {
        return "EntityController";
    }
}