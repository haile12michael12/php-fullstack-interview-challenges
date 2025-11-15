<?php

namespace Services;

use Factories\DBFactoryProvider;
use Core\Config;
use Core\Logger;

class ConnectionManager
{
    private static $instance = null;
    private $connections = [];
    private $config;

    private function __construct()
    {
        $this->config = Config::getInstance();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    public function getConnection(string $type, array $config = [])
    {
        $key = $type . '_' . md5(serialize($config));
        
        if (!isset($this->connections[$key]) || !$this->connections[$key]->isConnected()) {
            Logger::getInstance()->info("Creating new {$type} connection");
            $factory = DBFactoryProvider::getFactory($type, $config);
            $this->connections[$key] = $factory->createConnection();
            $this->connections[$key]->connect();
        }
        
        return $this->connections[$key];
    }

    public function closeAllConnections()
    {
        foreach ($this->connections as $connection) {
            if ($connection->isConnected()) {
                $connection->disconnect();
            }
        }
        
        $this->connections = [];
        Logger::getInstance()->info("All connections closed");
    }
}