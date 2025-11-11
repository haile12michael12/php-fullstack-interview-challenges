<?php

namespace App\ORM;

class EventSystem
{
    protected static $listeners = [];

    public static function listen($event, callable $callback)
    {
        if (!isset(static::$listeners[$event])) {
            static::$listeners[$event] = [];
        }
        
        static::$listeners[$event][] = $callback;
    }

    public static function dispatch($event, $data = null)
    {
        if (!isset(static::$listeners[$event])) {
            return;
        }
        
        foreach (static::$listeners[$event] as $listener) {
            call_user_func($listener, $data);
        }
    }

    public static function forget($event)
    {
        unset(static::$listeners[$event]);
    }

    public static function forgetAll()
    {
        static::$listeners = [];
    }

    public static function getListeners($event)
    {
        return static::$listeners[$event] ?? [];
    }
}