<?php

namespace App\Services\Providers;

use App\Container\Contracts\ContainerInterface;
use App\Services\Interfaces\MailerInterface;
use App\Services\Implementations\SmtpMailer;

class MailServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->register(MailerInterface::class, function () {
            // In a real application, these would come from configuration
            return new SmtpMailer('smtp.example.com', 587, 'username', 'password');
        });
        
        $container->register(SmtpMailer::class, function () {
            // In a real application, these would come from configuration
            return new SmtpMailer('smtp.example.com', 587, 'username', 'password');
        });
    }
}