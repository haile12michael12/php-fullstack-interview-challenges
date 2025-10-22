<?php

require __DIR__ . '/vendor/autoload.php';

use App\Chat\ChatServiceImpl;
use Spiral\GRPC\Server;
use Spiral\RoadRunner\Worker;

// Create new RoadRunner worker from global environment
$worker = Worker::create();

// Create a new gRPC server
$server = new Server($worker);

// Register our Chat service implementation
$server->registerService(Chat\ChatServiceInterface::class, new ChatServiceImpl());

// Start the server
$server->serve();