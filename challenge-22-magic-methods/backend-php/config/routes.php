<?php

return [
    // Magic Methods Routes
    'GET /api/magic' => [\App\Controller\MagicController::class, 'index'],
    'GET /api/magic/fluent' => [\App\Controller\MagicController::class, 'fluent'],
    'GET /api/magic/proxy' => [\App\Controller\MagicController::class, 'proxy'],
    'GET /api/magic/interceptor' => [\App\Controller\MagicController::class, 'interceptor'],
    
    // Entity Routes
    'GET /api/entities' => [\App\Controller\EntityController::class, 'index'],
    'GET /api/entities/users' => [\App\Controller\EntityController::class, 'listUsers'],
    'GET /api/entities/users/{id}' => [\App\Controller\EntityController::class, 'getUser'],
    'POST /api/entities/users' => [\App\Controller\EntityController::class, 'createUser'],
    'PUT /api/entities/users/{id}' => [\App\Controller\EntityController::class, 'updateUser'],
    'DELETE /api/entities/users/{id}' => [\App\Controller\EntityController::class, 'deleteUser'],
    
    // Query Routes
    'GET /api/query' => [\App\Controller\QueryController::class, 'index'],
    'GET /api/query/users' => [\App\Controller\QueryController::class, 'queryUsers'],
    'GET /api/query/posts' => [\App\Controller\QueryController::class, 'queryPosts'],
    'POST /api/query/custom' => [\App\Controller\QueryController::class, 'customQuery'],
];