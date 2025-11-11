<?php

return [
    // User API routes
    '/api/users' => [
        'GET' => 'App\Controller\ApiController@getUsers',
        'POST' => 'App\Controller\ApiController@createUser',
    ],
    '/api/users/{id}' => [
        'GET' => 'App\Controller\ApiController@getUser',
    ],
    
    // Generator API routes
    '/api/process/csv' => [
        'GET' => 'App\Controller\GeneratorController@processCsv',
    ],
    '/api/process/file' => [
        'GET' => 'App\Controller\GeneratorController@processFile',
    ],
    '/api/fibonacci/{limit}' => [
        'GET' => 'App\Controller\GeneratorController@fibonacci',
    ],
    '/api/primes/{limit}' => [
        'GET' => 'App\Controller\GeneratorController@primes',
    ],
    '/api/stream' => [
        'GET' => 'App\Controller\GeneratorController@stream',
    ],
];