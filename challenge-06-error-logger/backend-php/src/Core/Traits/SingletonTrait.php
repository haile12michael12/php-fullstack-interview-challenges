<?php

namespace App\Core\Traits;

trait SingletonTrait
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }
}