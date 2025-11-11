<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;

class LsbController extends BaseController
{
    public function index()
    {
        return $this->jsonResponse([
            'message' => 'Late Static Binding Demo',
            'endpoints' => [
                'GET /api/lsb/users' => 'List all users',
                'GET /api/lsb/users/{id}' => 'Get user by ID',
                'POST /api/lsb/users' => 'Create a new user',
                'GET /api/lsb/posts' => 'List all posts',
                'GET /api/lsb/posts/{id}' => 'Get post by ID',
                'POST /api/lsb/posts' => 'Create a new post',
            ]
        ]);
    }

    public function getUsers()
    {
        try {
            $users = User::all();
            $userData = [];
            
            foreach ($users as $user) {
                $userData[] = $user->toArray();
            }
            
            return $this->jsonResponse(['users' => $userData]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch users: ' . $e->getMessage());
        }
    }

    public function getUser($id)
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return $this->errorResponse('User not found', 404);
            }
            
            return $this->jsonResponse(['user' => $user->toArray()]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch user: ' . $e->getMessage());
        }
    }

    public function createUser()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $user = User::create($input);
            
            return $this->jsonResponse(['user' => $user->toArray()], 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create user: ' . $e->getMessage());
        }
    }

    public function getPosts()
    {
        try {
            $posts = Post::all();
            $postData = [];
            
            foreach ($posts as $post) {
                $postData[] = $post->toArray();
            }
            
            return $this->jsonResponse(['posts' => $postData]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch posts: ' . $e->getMessage());
        }
    }

    public function getPost($id)
    {
        try {
            $post = Post::find($id);
            
            if (!$post) {
                return $this->errorResponse('Post not found', 404);
            }
            
            return $this->jsonResponse(['post' => $post->toArray()]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch post: ' . $e->getMessage());
        }
    }

    public function createPost()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $post = Post::create($input);
            
            return $this->jsonResponse(['post' => $post->toArray()], 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create post: ' . $e->getMessage());
        }
    }
}