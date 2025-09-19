<?php

declare(strict_types=1);

namespace SharedBackend\Http\Middleware;

use SharedBackend\Http\Request;
use SharedBackend\Http\Response;

interface MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response;
}
