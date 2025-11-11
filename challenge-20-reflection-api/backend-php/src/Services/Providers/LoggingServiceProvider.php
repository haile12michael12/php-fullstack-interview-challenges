<?php

namespace App\Services\Providers;

use App\Container\Contracts\ContainerInterface;
use App\Services\Interfaces\LoggerInterface;
use App\Services\Implementations\FileLogger;

class LoggingServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->register(LoggerInterface::class, function () {
            return new FileLogger();
        });
        
        $container->register(FileLogger::class, function () {
            return new FileLogger();
        });
    }
}