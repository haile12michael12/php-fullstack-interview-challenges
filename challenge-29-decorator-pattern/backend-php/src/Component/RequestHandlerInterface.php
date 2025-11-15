<?php

namespace App\Component;

use App\Component\HttpRequest;
use App\Component\HttpResponse;

interface RequestHandlerInterface
{
    /**
     * Handle an HTTP request and return a response
     *
     * @param HttpRequest $request The HTTP request to handle
     * @return HttpResponse The HTTP response
     */
    public function handle(HttpRequest $request): HttpResponse;
}