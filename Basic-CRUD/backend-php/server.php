<?php

declare(strict_types=1);

use Spiral\RoadRunner\GRPC\Server;
use Spiral\RoadRunner\Worker;
use Challenge01\EchoService;
use SharedBackend\Core\Container;
use SharedBackend\Core\Config;
use SharedBackend\Core\Logger;
use SharedBackend\Core\Cache;
use SharedBackend\Core\EventDispatcher;

// Include Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Include shared backend
require_once __DIR__ . '/../shared-backend/vendor/autoload.php';

// Include generated proto files (you'll need to generate these)
// require_once __DIR__ . '/proto/Echo/EchoServiceInterface.php';
// require_once __DIR__ . '/proto/Echo/EchoRequest.php';
// require_once __DIR__ . '/proto/Echo/EchoResponse.php';
// require_once __DIR__ . '/proto/Echo/StatsRequest.php';
// require_once __DIR__ . '/proto/Echo/StatsResponse.php';
// require_once __DIR__ . '/proto/Echo/HealthRequest.php';
// require_once __DIR__ . '/proto/Echo/HealthResponse.php';

/**
 * Advanced gRPC Echo Server with RoadRunner
 */
class EchoServer
{
    private Container $container;
    private Config $config;
    private Logger $logger;
    private Server $grpcServer;
    private Worker $worker;

    public function __construct()
    {
        $this->initializeContainer();
        $this->initializeServices();
        $this->initializeGrpcServer();
    }

    public function start(): void
    {
        $this->logger->info('Starting gRPC Echo Server', [
            'version' => '1.0.0',
            'environment' => $this->config->get('app.env', 'production'),
            'debug' => $this->config->get('app.debug', false)
        ]);

        try {
            // Register the EchoService
            $echoService = $this->container->get(EchoService::class);
            $this->grpcServer->registerService('echo.EchoService', $echoService);

            $this->logger->info('gRPC services registered successfully');

            // Start the server
            $this->grpcServer->serve();
        } catch (\Exception $e) {
            $this->logger->error('Failed to start gRPC server', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function stop(): void
    {
        $this->logger->info('Stopping gRPC Echo Server');
        $this->worker->stop();
    }

    private function initializeContainer(): void
    {
        $this->container = new Container();
        $this->config = new Config(__DIR__);
        $this->container->instance(Config::class, $this->config);
    }

    private function initializeServices(): void
    {
        // Initialize Logger
        $this->logger = new Logger($this->config);
        $this->container->instance(Logger::class, $this->logger);

        // Initialize Cache
        $cache = new Cache($this->config, $this->logger);
        $this->container->instance(Cache::class, $cache);

        // Initialize Event Dispatcher
        $eventDispatcher = new EventDispatcher($this->logger);
        $this->container->instance(EventDispatcher::class, $eventDispatcher);

        // Register EchoService
        $this->container->singleton(EchoService::class, function (Container $container) {
            return new EchoService(
                $container->get(Logger::class),
                $container->get(Cache::class),
                $container->get(EventDispatcher::class)
            );
        });

        // Add event listeners
        $eventDispatcher->addListener(
            'Challenge01\Events\EchoProcessedEvent',
            [$this, 'onEchoProcessed']
        );
    }

    private function initializeGrpcServer(): void
    {
        $this->worker = Worker::create();
        $this->grpcServer = new Server($this->worker, [
            'debug' => $this->config->get('app.debug', false),
        ]);
    }

    public function onEchoProcessed($event): void
    {
        $this->logger->debug('Echo processed event received', [
            'message' => $event->getMessage(),
            'processed_message' => $event->getProcessedMessage(),
            'tags' => $event->getTags()
        ]);
    }
}

// Handle command line arguments
$env = 'production';
$debug = false;

foreach ($argv as $arg) {
    if (str_starts_with($arg, '--env=')) {
        $env = substr($arg, 6);
    } elseif ($arg === '--debug') {
        $debug = true;
    }
}

// Set environment variables
putenv("APP_ENV={$env}");
putenv("APP_DEBUG=" . ($debug ? 'true' : 'false'));

try {
    $server = new EchoServer();
    
    // Handle graceful shutdown
    pcntl_signal(SIGTERM, function () use ($server) {
        $server->stop();
        exit(0);
    });
    
    pcntl_signal(SIGINT, function () use ($server) {
        $server->stop();
        exit(0);
    });

    $server->start();
} catch (\Exception $e) {
    echo "Failed to start server: " . $e->getMessage() . "\n";
    exit(1);
}
