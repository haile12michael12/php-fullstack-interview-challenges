<?php

namespace Tests\Integration;

use App\Cache\RedisAdapter;
use App\Exception\CacheException;
use PHPUnit\Framework\TestCase;

class RedisIntegrationTest extends TestCase
{
    private RedisAdapter $redisAdapter;
    private \Redis $redis;

    protected function setUp(): void
    {
        if (!class_exists('Redis')) {
            $this->markTestSkipped('Redis extension not available');
        }

        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1', 6379);
        $this->redis->select(15); // Use test database
        
        $this->redisAdapter = new RedisAdapter($this->redis);
    }

    protected function tearDown(): void
    {
        if (isset($this->redis)) {
            $this->redis->flushDB();
        }
    }

    public function testSetAndGet(): void
    {
        $key = 'integration_test_key';
        $value = 'integration_test_value';
        
        $this->assertTrue($this->redisAdapter->set($key, $value));
        $this->assertEquals($value, $this->redisAdapter->get($key));
    }

    public function testSetWithTtl(): void
    {
        $key = 'ttl_test_key';
        $value = 'ttl_test_value';
        $ttl = 1; // 1 second
        
        $this->assertTrue($this->redisAdapter->set($key, $value, $ttl));
        $this->assertEquals($value, $this->redisAdapter->get($key));
        
        // Wait for expiration
        sleep($ttl + 1);
        
        $this->assertNull($this->redisAdapter->get($key));
    }

    public function testGetMultiple(): void
    {
        $values = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3'
        ];
        
        foreach ($values as $key => $value) {
            $this->redisAdapter->set($key, $value);
        }
        
        $result = $this->redisAdapter->getMultiple(array_keys($values));
        $this->assertEquals($values, $result);
    }

    public function testSetMultiple(): void
    {
        $values = [
            'multi_key1' => 'multi_value1',
            'multi_key2' => 'multi_value2',
            'multi_key3' => 'multi_value3'
        ];
        
        $this->assertTrue($this->redisAdapter->setMultiple($values));
        
        foreach ($values as $key => $value) {
            $this->assertEquals($value, $this->redisAdapter->get($key));
        }
    }
}