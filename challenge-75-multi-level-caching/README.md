# Challenge 75: Multi-Level Caching

## Description
In this challenge, you'll implement a multi-level caching system that combines different caching layers to optimize performance and reduce latency. Multi-level caching leverages the strengths of various cache storage types to provide efficient data access.

## Learning Objectives
- Understand caching hierarchies and strategies
- Implement multi-level cache architectures
- Handle cache invalidation across layers
- Optimize cache hit ratios
- Manage cache warming strategies
- Implement cache eviction policies

## Requirements
Create a multi-level caching system with the following features:

1. **Cache Layers**:
   - L1: In-memory cache (application level)
   - L2: Distributed cache (Redis/Memcached)
   - L3: Database query cache
   - L4: HTTP cache (CDN/Reverse proxy)
   - Custom cache layers

2. **Cache Management**:
   - Cache key generation and naming
   - Cache expiration and TTL
   - Cache invalidation strategies
   - Cache warming mechanisms
   - Cache preloading
   - Cache statistics and monitoring

3. **Data Consistency**:
   - Write-through caching
   - Write-behind caching
   - Read-through caching
   - Cache-aside pattern
   - Eventual consistency
   - Cache synchronization

4. **Performance Optimization**:
   - Cache hit/miss ratio tracking
   - Latency monitoring
   - Memory usage optimization
   - Compression strategies
   - Serialization formats
   - Cache partitioning

## Features to Implement
- [ ] Multi-level cache architecture
- [ ] Cache key generation and naming
- [ ] Cache expiration and TTL management
- [ ] Cache invalidation across layers
- [ ] Cache warming mechanisms
- [ ] Write-through caching
- [ ] Read-through caching
- [ ] Cache-aside pattern
- [ ] Cache statistics and monitoring
- [ ] Memory usage optimization
- [ ] Cache partitioning

## Project Structure
```
backend-php/
├── src/
│   ├── Cache/
│   │   ├── MultiLevelCache.php
│   │   ├── Layers/
│   │   │   ├── MemoryCache.php
│   │   │   ├── RedisCache.php
│   │   │   ├── DatabaseCache.php
│   │   │   └── HttpCache.php
│   │   ├── Strategies/
│   │   │   ├── WriteThrough.php
│   │   │   ├── WriteBehind.php
│   │   │   ├── ReadThrough.php
│   │   │   └── CacheAside.php
│   │   ├── KeyGenerator.php
│   │   ├── CacheWarmer.php
│   │   ├── CacheInvalidator.php
│   │   └── Stats/
│   │       ├── CacheStats.php
│   │       └── HitRatioCalculator.php
│   ├── Http/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   └── HttpClient.php
│   └── Services/
├── public/
│   └── index.php
├── storage/
│   └── cache/
├── config/
│   └── cache.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── cache.js
│   ├── components/
│   ├── App.jsx
│   └── main.jsx
├── public/
└── package.json
```

## Setup Instructions
1. Navigate to the `backend-php` directory
2. Run `composer install` to install dependencies
3. Configure your web server to point to the `public` directory
4. Start the development server with `php -S localhost:8000 -t public`
5. Navigate to the `frontend-react` directory
6. Run `npm install` to install dependencies
7. Start the development server with `npm run dev`

## Cache Layer Architecture

### L1: In-Memory Cache
Fastest access, limited size, application-specific:
```php
class MemoryCache implements CacheInterface
{
    private $storage = [];
    
    public function get($key)
    {
        return $this->storage[$key] ?? null;
    }
    
    public function set($key, $value, $ttl = 3600)
    {
        $this->storage[$key] = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
    }
}
```

### L2: Distributed Cache (Redis)
Shared across instances, larger capacity:
```php
class RedisCache implements CacheInterface
{
    private $redis;
    
    public function get($key)
    {
        return $this->redis->get($key);
    }
    
    public function set($key, $value, $ttl = 3600)
    {
        return $this->redis->setex($key, $ttl, $value);
    }
}
```

### L3: Database Query Cache
Cache expensive database queries:
```php
class DatabaseCache implements CacheInterface
{
    public function get($key)
    {
        $result = DB::table('query_cache')
            ->where('key', $key)
            ->where('expires_at', '>', now())
            ->first();
            
        return $result ? unserialize($result->value) : null;
    }
}
```

## Multi-Level Cache Implementation
```php
class MultiLevelCache
{
    private $layers = [];
    
    public function __construct(array $layers)
    {
        $this->layers = $layers;
    }
    
    public function get($key)
    {
        foreach ($this->layers as $layer) {
            $value = $layer->get($key);
            if ($value !== null) {
                // Promote to higher layers
                $this->promote($key, $value);
                return $value;
            }
        }
        
        return null;
    }
    
    public function set($key, $value, $ttl = 3600)
    {
        // Write to all layers
        foreach ($this->layers as $layer) {
            $layer->set($key, $value, $ttl);
        }
    }
    
    private function promote($key, $value)
    {
        // Move data to higher (faster) cache layers
        foreach ($this->layers as $layer) {
            if (!$layer->has($key)) {
                $layer->set($key, $value);
            }
        }
    }
}
```

## Configuration Example
```php
[
    'layers' => [
        'l1' => [
            'driver' => 'memory',
            'size' => 1000,
            'ttl' => 300  // 5 minutes
        ],
        'l2' => [
            'driver' => 'redis',
            'host' => '127.0.0.1',
            'port' => 6379,
            'ttl' => 3600  // 1 hour
        ],
        'l3' => [
            'driver' => 'database',
            'table' => 'query_cache',
            'ttl' => 86400  // 24 hours
        ]
    ],
    'strategy' => 'write_through',
    'key_prefix' => 'app_cache_',
    'compression' => true,
    'monitoring' => true
]
```

## Cache Key Generation
```php
class KeyGenerator
{
    public static function generate($namespace, $key, $tags = [])
    {
        $tagString = !empty($tags) ? implode(':', $tags) . ':' : '';
        return "cache:{$namespace}:{$tagString}" . md5(serialize($key));
    }
    
    public static function generateForUser($userId, $resource, $params = [])
    {
        return self::generate('user', [$userId, $resource, $params], ['user']);
    }
}
```

## Cache Warming Strategy
```php
class CacheWarmer
{
    public function warmUp()
    {
        // Preload frequently accessed data
        $this->preloadUserProfiles();
        $this->preloadProductCatalog();
        $this->preloadConfiguration();
    }
    
    private function preloadUserProfiles()
    {
        $users = User::active()->limit(1000)->get();
        foreach ($users as $user) {
            Cache::set("user:profile:{$user->id}", $user->toArray(), 3600);
        }
    }
}
```

## API Endpoints
```
# Cache Management
GET    /cache/stats              # Get cache statistics
GET    /cache/keys               # List cache keys
DELETE /cache/keys/{pattern}     # Delete cache keys by pattern
POST   /cache/warm               # Trigger cache warming
GET    /cache/layers             # Get cache layer information
POST   /cache/invalidate         # Invalidate cache by tag
```

## Cache Statistics Response
```json
{
  "layers": {
    "l1_memory": {
      "hits": 15420,
      "misses": 342,
      "hit_ratio": 0.978,
      "entries": 842,
      "memory_usage": "2.4MB"
    },
    "l2_redis": {
      "hits": 2341,
      "misses": 152,
      "hit_ratio": 0.939,
      "entries": 12540,
      "memory_usage": "45.2MB"
    }
  },
  "total_hit_ratio": 0.962,
  "requests_per_second": 142.5,
  "average_response_time": "12ms"
}
```

## Evaluation Criteria
- [ ] Multi-level cache architecture functions correctly
- [ ] Cache key generation and naming are consistent
- [ ] Cache expiration and TTL management work
- [ ] Cache invalidation propagates across layers
- [ ] Cache warming mechanisms preload data
- [ ] Write-through caching maintains consistency
- [ ] Read-through caching improves performance
- [ ] Cache-aside pattern handles misses
- [ ] Cache statistics and monitoring are accurate
- [ ] Memory usage is optimized
- [ ] Cache partitioning works effectively
- [ ] Code is well-organized and documented
- [ ] Tests cover caching functionality

## Resources
- [Cache-Aside Pattern](https://docs.microsoft.com/en-us/azure/architecture/patterns/cache-aside)
- [Write-Through and Write-Behind Caching](https://docs.oracle.com/cd/E15357_01/coh.360/e15723/cache_rtwtwbra.htm)
- [Redis Caching Strategies](https://redis.io/topics/lru-cache)
- [Multi-Level Caching](https://docs.aws.amazon.com/AmazonElastiCache/latest/red-ug/best-practices-multi-level-caching.html)