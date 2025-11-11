<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;

class ModelController extends BaseController
{
    public function index()
    {
        return $this->jsonResponse([
            'message' => 'Model Inheritance Demo',
            'endpoints' => [
                'GET /api/models/users' => 'List all users with posts',
                'GET /api/models/posts' => 'List all posts with relationships',
                'GET /api/models/categories' => 'List all categories with posts',
            ]
        ]);
    }

    public function getUsersWithPosts()
    {
        try {
            $users = User::all();
            $userData = [];
            
            foreach ($users as $user) {
                $userArray = $user->toArray();
                $userArray['posts'] = [];
                
                // Load posts for each user
                $posts = $user->posts();
                foreach ($posts as $post) {
                    $userArray['posts'][] = $post->toArray();
                }
                
                $userData[] = $userArray;
            }
            
            return $this->jsonResponse(['users' => $userData]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch users: ' . $e->getMessage());
        }
    }

    public function getPostsWithRelationships()
    {
        try {
            $posts = Post::all();
            $postData = [];
            
            foreach ($posts as $post) {
                $postArray = $post->toArray();
                $postArray['user'] = $post->user() ? $post->user()->toArray() : null;
                $postArray['comments'] = [];
                
                // Load comments for each post
                $comments = $post->comments();
                foreach ($comments as $comment) {
                    $postArray['comments'][] = $comment->toArray();
                }
                
                $postData[] = $postArray;
            }
            
            return $this->jsonResponse(['posts' => $postData]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch posts: ' . $e->getMessage());
        }
    }

    public function getCategoriesWithPosts()
    {
        try {
            $categories = Category::all();
            $categoryData = [];
            
            foreach ($categories as $category) {
                $categoryArray = $category->toArray();
                $categoryArray['posts'] = [];
                
                // Load posts for each category
                $posts = $category->posts();
                foreach ($posts as $post) {
                    $categoryArray['posts'][] = $post->toArray();
                }
                
                $categoryData[] = $categoryArray;
            }
            
            return $this->jsonResponse(['categories' => $categoryData]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch categories: ' . $e->getMessage());
        }
    }
}