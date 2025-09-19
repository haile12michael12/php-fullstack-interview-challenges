<?php

declare(strict_types=1);

namespace SharedBackend\Application;

use SharedBackend\Core\Container;
use SharedBackend\Core\Config;
use SharedBackend\Core\Logger;
use SharedBackend\Core\Database;
use SharedBackend\Core\Cache;
use SharedBackend\Core\EventDispatcher;
use SharedBackend\Http\Middleware\MiddlewareInterface;
use SharedBackend\Http\Request;
use SharedBackend\Http\Response;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Advanced application framework with dependency injection and middleware support
 */
class Application
{
    private Container $container;
    private Router $router;
    private array $middleware = [];
    private array $globalMiddleware = [];

    public function __construct(string $basePath = null)
    {
        $this->container = new Container();
        $this->initializeServices($basePath);
        $this->initializeRouter();
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    public function get(string $id): mixed
    {
        return $this->container->get($id);
    }

    public function bind(string $id, $concrete = null, bool $singleton = false): void
    {
        $this->container->bind($id, $concrete, $singleton);
    }

    public function singleton(string $id, $concrete = null): void
    {
        $this->container->singleton($id, $concrete);
    }

    public function getConfig(): Config
    {
        return $this->get(Config::class);
    }

    public function getLogger(): Logger
    {
        return $this->get(Logger::class);
    }

    public function getDatabase(): Database
    {
        return $this->get(Database::class);
    }

    public function getCache(): Cache
    {
        return $this->get(Cache::class);
    }

    public function getEventDispatcher(): EventDispatcher
    {
        return $this->get(EventDispatcher::class);
    }

    public function middleware(MiddlewareInterface $middleware): self
    {
        $this->middleware[] = $middleware;
        return $this;
    }

    public function globalMiddleware(MiddlewareInterface $middleware): self
    {
        $this->globalMiddleware[] = $middleware;
        return $this;
    }

    public function get(string $path, callable $handler): self
    {
        return $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): self
    {
        return $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, callable $handler): self
    {
        return $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, callable $handler): self
    {
        return $this->addRoute('DELETE', $path, $handler);
    }

    public function patch(string $path, callable $handler): self
    {
        return $this->addRoute('PATCH', $path, $handler);
    }

    public function options(string $path, callable $handler): self
    {
        return $this->addRoute('OPTIONS', $path, $handler);
    }

    public function group(string $prefix, callable $callback): self
    {
        $router = $this->router->group($prefix, $callback);
        return $this;
    }

    public function run(ServerRequestInterface $request = null): ResponseInterface
    {
        try {
            $request = $request ?? $this->createRequestFromGlobals();
            $response = $this->router->dispatch($request);
            
            $this->getLogger()->info('Request processed', [
                'method' => $request->getMethod(),
                'uri' => (string)$request->getUri(),
                'status' => $response->getStatusCode()
            ]);

            return $response;
        } catch (\Exception $e) {
            $this->getLogger()->error('Application error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->handleException($e);
        }
    }

    private function initializeServices(string $basePath = null): void
    {
        $this->container->instance(Config::class, new Config($basePath));
        
        // Register services with dependencies
        $this->container->singleton(Logger::class, function (Container $container) {
            return new Logger($container->get(Config::class));
        });

        $this->container->singleton(Database::class, function (Container $container) {
            return new Database(
                $container->get(Config::class),
                $container->get(Logger::class)
            );
        });

        $this->container->singleton(Cache::class, function (Container $container) {
            return new Cache(
                $container->get(Config::class),
                $container->get(Logger::class)
            );
        });

        $this->container->singleton(EventDispatcher::class, function (Container $container) {
            return new EventDispatcher($container->get(Logger::class));
        });
    }

    private function initializeRouter(): void
    {
        $strategy = new ApplicationStrategy();
        $this->router = new Router();
        $this->router->setStrategy($strategy);

        // Add global middleware
        foreach ($this->globalMiddleware as $middleware) {
            $this->router->middleware($middleware);
        }
    }

    private function addRoute(string $method, string $path, callable $handler): self
    {
        $this->router->map($method, $path, function (ServerRequestInterface $request, array $args) use ($handler) {
            $appRequest = new Request($request);
            $appResponse = new Response(
                new \GuzzleHttp\Psr7\Response(),
                new \GuzzleHttp\Psr7\StreamFactory()
            );

            // Apply route-specific middleware
            $middlewareStack = $this->middleware;
            $middlewareStack[] = function (Request $req, callable $next) use ($handler, $appResponse) {
                return $handler($req, $appResponse);
            };

            $pipeline = $this->createMiddlewarePipeline($middlewareStack);
            return $pipeline($appRequest);
        });

        return $this;
    }

    private function createMiddlewarePipeline(array $middleware): callable
    {
        return array_reduce(
            array_reverse($middleware),
            function (callable $next, MiddlewareInterface $middleware) {
                return function (Request $request) use ($middleware, $next) {
                    return $middleware->handle($request, $next);
                };
            },
            function (Request $request) {
                return new Response(
                    new \GuzzleHttp\Psr7\Response(),
                    new \GuzzleHttp\Psr7\StreamFactory()
                )->error('No handler found', 404);
            }
        );
    }

    private function createRequestFromGlobals(): ServerRequestInterface
    {
        return \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
    }

    private function handleException(\Exception $e): ResponseInterface
    {
        $response = new \GuzzleHttp\Psr7\Response();
        $appResponse = new Response($response, new \GuzzleHttp\Psr7\StreamFactory());

        if ($e instanceof \SharedBackend\Core\Exceptions\NotFoundException) {
            return $appResponse->notFound($e->getMessage())->getOriginalResponse();
        }

        if ($e instanceof \SharedBackend\Core\Exceptions\ValidationException) {
            return $appResponse->validationError(['general' => [$e->getMessage()]])->getOriginalResponse();
        }

        $message = $this->getConfig()->isDevelopment() ? $e->getMessage() : 'Internal Server Error';
        return $appResponse->serverError($message)->getOriginalResponse();
    }
}
