<?php

use App\Core\Router;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\CsrfMiddleware;

// Get the router instance from the container
$router = $container->get('router');

// Public routes
$router->get('/', 'HomeController@index');
$router->get('/about', 'HomeController@about');

// Authentication routes
$router->get('/login', 'UserController@login');
$router->post('/login', 'UserController@login', [CsrfMiddleware::class]);
$router->get('/register', 'UserController@register');
$router->post('/register', 'UserController@register', [CsrfMiddleware::class]);
$router->get('/logout', 'UserController@logout');

// Protected routes
$router->get('/profile', 'UserController@profile', [AuthMiddleware::class]);
$router->get('/chat', 'ChatController@index', [AuthMiddleware::class]);
$router->post('/chat/message', 'ChatController@sendMessage', [AuthMiddleware::class]);

// API routes
$router->get('/api/users', 'Api\UserController@index', [AuthMiddleware::class]);
$router->get('/api/chat/messages', 'Api\ChatController@getMessages', [AuthMiddleware::class]);
$router->post('/api/chat/message', 'Api\ChatController@sendMessage', [AuthMiddleware::class]);