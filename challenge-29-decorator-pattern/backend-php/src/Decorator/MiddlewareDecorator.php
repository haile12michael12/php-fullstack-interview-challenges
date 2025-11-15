<?php

namespace App\Decorator;

use App\Component\RequestHandlerInterface;
use App\Component\HttpRequest;
use App\Component\HttpResponse;

abstract class MiddlewareDecorator implements RequestHandlerInterface
{
    protected RequestHandlerInterface $nextHandler;

    public function __construct(RequestHandlerInterface $nextHandler)
    {
        $this->nextHandler = $nextHandler;
    }

    /**
     * Handle an HTTP request and return a response
     *
     * @param HttpRequest $request The HTTP request to handle
     * @return HttpResponse The HTTP response
     */
    abstract public function handle(HttpRequest $request): HttpResponse;
}