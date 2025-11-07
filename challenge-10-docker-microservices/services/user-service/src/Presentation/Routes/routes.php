<?php

use App\Presentation\Controller\UserController;
use DI\Container;

return function (Container $container) {
    return [
        ['GET', '/users/{id:\d+}', [UserController::class, 'getUser']],
        ['POST', '/users', [UserController::class, 'createUser']],
    ];
};