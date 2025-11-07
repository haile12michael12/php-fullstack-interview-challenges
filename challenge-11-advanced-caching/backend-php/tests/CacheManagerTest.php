<?php

namespace Tests;

use App\Cache\CacheManager;
use App\Cache\ApcuAdapter;
use App\Cache\RedisAdapter;
use App\Cache\MemcachedAdapter;
use App\Exception\CacheException;
use PHPUnit\Framework\TestCase;

class CacheManagerTest extends TestCase
{
    private CacheManager $cacheManager;

    protected function setUp(): void
    {
        $this->cacheManager = new CacheManager();
        
        // Add adapters if extensions are available
        if (function_exists('apcu_enabled') && apcu_enabled()) {
            $apcuAdapter = new ApcuAdapter();
            $this->cacheManager->addAdapter('apcu', $apcuAdapter, 3);
        }
        
        if (class_exists('Redis')) {
            $redis = new \Redis();
            $redis->connect('127.0.0.1', 6379);
            $redis->select(15); // Use test database
            $redisAdapter = new RedisAdapter($redis);
            $this->cacheManager->addAdapter('redis', $redisAdapter, 2);
        }
        
        if (class_exists('Memcached')) {
            $memcached = new \Memcached();
            $memcached->addServer('127.0.0.1', 11211);
            $memcachedAdapter = new MemcachedAdapter($memcached);
            $this->cacheManager->addAdapter('memcached', $memcachedAdapter, 1);
        }
    }

    public function testSetAndGet(): void
    {
        $key = 'test_key';
        $value = 'test_value';
        
        $this->assertTrue($this->cacheManager->set($key, $value));
        $this->assertEquals($value, $this->cacheManager->get($key));
    }

    public function testDelete(): void
    {
        $key = 'test_key';
        $value = 'test_value';
        
        $this->cacheManager->set($key, $value);
        $this->assertTrue($this->cacheManager->delete($key));
        $this->assertNull($this->cacheManager->get($key));
    }

    public function testHas(): void
    {
        $key = 'test_key';
        $value = 'test_value';
        
        $this->assertFalse($this->cacheManager->has($key));
        $this->cacheManager->set($key, $value);
        $this->assertTrue($this->cacheManager->has($key));
    }

    public function testClear(): void
    {
        $this->cacheManager->set('key1', 'value1');
        $this->cacheManager->set('key2', 'value2');
        
        $this->assertTrue($this->cacheManager->clear());
        $this->assertNull($this->cacheManager->get('key1'));
        $this->assertNull($this->cacheManager->get('key2'));
    }

    public function testLayeredCaching(): void
    {
        $key = 'layered_test_key';
        $value = 'layered_test_value';
        
        // Set in all layers
        $this->assertTrue($this->cacheManager->set($key, $value));
        
        // Get from cache (should return from highest priority layer)
        $this->assertEquals($value, $this->cacheManager->get($key));
    }
}