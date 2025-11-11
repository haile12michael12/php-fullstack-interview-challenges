<?php

return [
    // Trait demo routes
    '/' => [
        'GET' => 'App\Http\Controller\TraitController@index',
    ],
    '/api/traits/logger' => [
        'POST' => 'App\Http\Controller\TraitController@logMessage',
    ],
    '/api/traits/calculate' => [
        'POST' => 'App\Http\Controller\TraitController@calculate',
    ],
    '/api/traits/validate' => [
        'POST' => 'App\Http\Controller\TraitController@validate',
    ],
    '/api/traits/cache' => [
        'POST' => 'App\Http\Controller\TraitController@cacheData',
    ],
    '/api/traits/cache/{key}' => [
        'GET' => 'App\Http\Controller\TraitController@getCachedData',
    ],
    '/api/traits/users' => [
        'POST' => 'App\Http\Controller\TraitController@createUser',
    ],
    '/api/traits/stats' => [
        'GET' => 'App\Http\Controller\TraitController@getStats',
    ],
];