<?php

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use App\ORM\Factory;

return [
    'definitions' => [
        User::class => function () {
            return [
                'name' => 'User ' . rand(1, 1000),
                'email' => 'user' . rand(1, 1000) . '@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
            ];
        },
        
        Post::class => function () {
            return [
                'title' => 'Post Title ' . rand(1, 1000),
                'content' => 'This is the content of post #' . rand(1, 1000),
                'user_id' => rand(1, 10),
            ];
        },
        
        Comment::class => function () {
            return [
                'content' => 'This is comment #' . rand(1, 1000),
                'user_id' => rand(1, 10),
                'post_id' => rand(1, 50),
            ];
        },
        
        Category::class => function () {
            return [
                'name' => 'Category ' . rand(1, 100),
                'description' => 'Description for category #' . rand(1, 100),
            ];
        },
    ],
    
    'states' => [
        User::class => [
            'admin' => function () {
                return [
                    'name' => 'Admin User',
                    'email' => 'admin@example.com',
                ];
            },
        ],
    ],
];