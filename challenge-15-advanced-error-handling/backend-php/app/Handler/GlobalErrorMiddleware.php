<?php

namespace App\Handler;

use App\Utils\ResponseHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class GlobalErrorMiddleware implements MiddlewareInterface
{
    private ExceptionHandler $exceptionHandler;

    public function __construct()
    {
        $this->exceptionHandler = new ExceptionHandler();
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        try {
            return $handler->handle($request);
        } catch (Throwable $exception) {
            return $this->exceptionHandler->handle($exception);
        }
    }
}