<?php

namespace App\Infrastructure\Security;

class ContentSecurityPolicy
{
    public static function apply(): void
    {
        $csp = "default-src 'self'; ";
        $csp .= "script-src 'self' 'unsafe-inline'; ";
        $csp .= "style-src 'self' 'unsafe-inline'; ";
        $csp .= "img-src 'self' data:; ";
        $csp .= "font-src 'self'; ";
        $csp .= "connect-src 'self'; ";
        $csp .= "frame-ancestors 'none';";

        header("Content-Security-Policy: " . $csp);
    }
}