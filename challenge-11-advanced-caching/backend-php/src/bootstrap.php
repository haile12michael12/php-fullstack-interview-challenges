<?php

use App\Cache\CacheManager;
use App\Cache\RedisAdapter;
use App\Cache\MemcachedAdapter;
use App\Cache\ApcuAdapter;
use App\Service\UserService;
use App\Service\ProductService;
use App\Service\CacheWarmService;
use App\Service\MonitoringService;
use App\Helpers\ResponseHelper;
use App\Helpers\Logger;
use App\Controller\CacheController;
use App\Controller\UserController;
use App\Controller\ProductController;
use App\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';

// Initialize cache adapters
$cacheManager = new CacheManager();

// Redis adapter
if (class_exists('Redis')) {
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $redisAdapter = new RedisAdapter($redis);
    $cacheManager->addAdapter('redis', $redisAdapter, 2);
}

// Memcached adapter
if (class_exists('Memcached')) {
    $memcached = new Memcached();
    $memcached->addServer('127.0.0.1', 11211);
    $memcachedAdapter = new MemcachedAdapter($memcached);
    $cacheManager->addAdapter('memcached', $memcachedAdapter, 1);
}

// APCu adapter
if (function_exists('apcu_enabled') && apcu_enabled()) {
    $apcuAdapter = new ApcuAdapter();
    $cacheManager->addAdapter('apcu', $apcuAdapter, 3); // Highest priority
}

// Initialize services
$userService = new UserService($cacheManager);
$productService = new ProductService($cacheManager);
$monitoringService = new MonitoringService($cacheManager);
$cacheWarmService = new CacheWarmService($cacheManager, $userService, $productService);

// Initialize helpers
$responseHelper = new ResponseHelper();
$logger = new Logger();

// Initialize controllers
$cacheController = new CacheController($cacheWarmService, $monitoringService, $responseHelper);
$userController = new UserController($userService, $responseHelper);
$productController = new ProductController($productService, $responseHelper);

// Initialize router
$router = new Router();

// Define routes
$router->get('/api/cache/stats', [$cacheController, 'getStats']);
$router->post('/api/cache/warm', [$cacheController, 'warmCache']);
$router->delete('/api/cache/flush', [$cacheController, 'flushCache']);
$router->post('/api/cache/invalidate', [$cacheController, 'invalidateCache']);

$router->get('/api/users/{id}', [$userController, 'getUser']);
$router->put('/api/users/{id}', [$userController, 'updateUser']);

$router->get('/api/products/{id}', [$productController, 'getProduct']);
$router->get('/api/products/category/{category}', [$productController, 'getProductsByCategory']);
$router->put('/api/products/{id}', [$productController, 'updateProduct']);

return $router;