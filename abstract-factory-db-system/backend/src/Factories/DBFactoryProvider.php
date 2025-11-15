<?php

namespace Factories;

class DBFactoryProvider
{
    public static function getFactory(string $type, array $config = [])
    {
        switch (strtolower($type)) {
            case 'mysql':
                return new MySQLFactory($config);
            case 'postgres':
            case 'postgresql':
                return new PostgresFactory($config);
            case 'sqlite':
                return new SQLiteFactory($config);
            default:
                throw new \InvalidArgumentException("Unsupported database type: {$type}");
        }
    }
}