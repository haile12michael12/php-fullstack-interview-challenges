<?php

namespace Challenge04\Core;

class Config
{
    private array $config = [];

    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->getDefaultConfig(), $config);
    }

    private function getDefaultConfig(): array
    {
        return [
            'upload' => [
                'max_file_size' => 5242880, // 5MB
                'allowed_types' => [
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                    'text/plain',
                    'application/pdf'
                ],
                'storage_path' => '/tmp/uploads',
                'quarantine_path' => '/tmp/quarantine'
            ],
            'security' => [
                'scan_enabled' => true,
                'quarantine_threats' => true,
                'delete_on_threat' => false
            ],
            'logging' => [
                'enabled' => true,
                'level' => 'INFO',
                'file' => '/tmp/file_upload.log'
            ]
        ];
    }

    public function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }

    public function set(string $key, $value): void
    {
        $keys = explode('.', $key);
        $config = &$this->config;
        
        foreach ($keys as $k) {
            if (!isset($config[$k]) || !is_array($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }
        
        $config = $value;
    }

    public function getAll(): array
    {
        return $this->config;
    }

    public function loadFromFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            $fileConfig = include $filePath;
            if (is_array($fileConfig)) {
                $this->config = array_merge($this->config, $fileConfig);
            }
        }
    }
}