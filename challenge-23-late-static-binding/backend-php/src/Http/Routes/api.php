<?php

use App\Http\Controllers\LsbController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\InheritanceController;

// LSB Routes
$router->get('/api/lsb', [LsbController::class, 'index']);
$router->get('/api/lsb/users', [LsbController::class, 'getUsers']);
$router->get('/api/lsb/users/(\d+)', [LsbController::class, 'getUser']);
$router->post('/api/lsb/users', [LsbController::class, 'createUser']);
$router->get('/api/lsb/posts', [LsbController::class, 'getPosts']);
$router->get('/api/lsb/posts/(\d+)', [LsbController::class, 'getPost']);
$router->post('/api/lsb/posts', [LsbController::class, 'createPost']);

// Model Routes
$router->get('/api/models', [ModelController::class, 'index']);
$router->get('/api/models/users', [ModelController::class, 'getUsersWithPosts']);
$router->get('/api/models/posts', [ModelController::class, 'getPostsWithRelationships']);
$router->get('/api/models/categories', [ModelController::class, 'getCategoriesWithPosts']);

// Inheritance Routes
$router->get('/api/inheritance', [InheritanceController::class, 'index']);
$router->get('/api/inheritance/concepts', [InheritanceController::class, 'getConcepts']);
$router->get('/api/inheritance/examples', [InheritanceController::class, 'getExamples']);
$router->post('/api/inheritance/factory', [InheritanceController::class, 'createWithFactory']);