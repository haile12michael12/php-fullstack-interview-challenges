<?php

return [
    // Value Objects API endpoints
    '/value-objects/types' => [
        'GET' => 'App\Controller\ValueObjectsController@getValueObjectTypes'
    ],
    '/value-objects/email' => [
        'GET' => 'App\Controller\ValueObjectsController@emailDemo'
    ],
    '/value-objects/money' => [
        'GET' => 'App\Controller\ValueObjectsController@moneyDemo'
    ],
    '/value-objects/address' => [
        'GET' => 'App\Controller\ValueObjectsController@addressDemo'
    ],
    '/value-objects/phone' => [
        'GET' => 'App\Controller\ValueObjectsController@phoneNumberDemo'
    ],
    '/value-objects/datetime' => [
        'GET' => 'App\Controller\ValueObjectsController@dateTimeRangeDemo'
    ],
];