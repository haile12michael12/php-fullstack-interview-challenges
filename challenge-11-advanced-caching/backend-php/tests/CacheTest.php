<?php

namespace Tests;

use App\Cache\CacheInterface;
use App\Cache\RedisAdapter;
use App\Cache\MemcachedAdapter;
use App\Cache\ApcuAdapter;
use App\Exception\CacheException;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    public function testRedisAdapter(): void
    {
        if (!class_exists('Redis')) {
            $this->markTestSkipped('Redis extension not available');
        }

        $redis = new \Redis();
        // Use a different database to avoid conflicts
        $redis->connect('127.0.0.1', 6379);
        $redis->select(15);

        $adapter = new RedisAdapter($redis);

        // Test set and get
        $key = 'test_key';
        $value = 'test_value';
        $this->assertTrue($adapter->set($key, $value));
        $this->assertEquals($value, $adapter->get($key));

        // Test has
        $this->assertTrue($adapter->has($key));
        $this->assertFalse($adapter->has('nonexistent_key'));

        // Test delete
        $this->assertTrue($adapter->delete($key));
        $this->assertNull($adapter->get($key));

        // Test clear
        $adapter->set('key1', 'value1');
        $adapter->set('key2', 'value2');
        $this->assertTrue($adapter->clear());
        $this->assertNull($adapter->get('key1'));
        $this->assertNull($adapter->get('key2'));
    }

    public function testMemcachedAdapter(): void
    {
        if (!class_exists('Memcached')) {
            $this->markTestSkipped('Memcached extension not available');
        }

        $memcached = new \Memcached();
        $memcached->addServer('127.0.0.1', 11211);

        $adapter = new MemcachedAdapter($memcached);

        // Test set and get
        $key = 'test_key';
        $value = 'test_value';
        $this->assertTrue($adapter->set($key, $value));
        $this->assertEquals($value, $adapter->get($key));

        // Test has
        $this->assertTrue($adapter->has($key));
        $this->assertFalse($adapter->has('nonexistent_key'));

        // Test delete
        $this->assertTrue($adapter->delete($key));
        $this->assertNull($adapter->get($key));
    }

    public function testApcuAdapter(): void
    {
        if (!function_exists('apcu_enabled') || !apcu_enabled()) {
            $this->markTestSkipped('APCu extension not available or not enabled');
        }

        $adapter = new ApcuAdapter();

        // Test set and get
        $key = 'test_key';
        $value = 'test_value';
        $this->assertTrue($adapter->set($key, $value));
        $this->assertEquals($value, $adapter->get($key));

        // Test has
        $this->assertTrue($adapter->has($key));
        $this->assertFalse($adapter->has('nonexistent_key'));

        // Test delete
        $this->assertTrue($adapter->delete($key));
        $this->assertNull($adapter->get($key));

        // Test clear
        $adapter->set('key1', 'value1');
        $adapter->set('key2', 'value2');
        $this->assertTrue($adapter->clear());
        $this->assertNull($adapter->get('key1'));
        $this->assertNull($adapter->get('key2'));
    }
}