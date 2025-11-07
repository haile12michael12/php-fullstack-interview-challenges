<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

// Get router from bootstrap and dispatch request
$router = require_once __DIR__ . '/../src/bootstrap.php';
$router->dispatch();