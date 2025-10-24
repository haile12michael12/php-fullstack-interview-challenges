# Challenge 76: Cache Invalidation

## Description
In this challenge, you'll implement comprehensive cache invalidation strategies to ensure data consistency across your caching layers. Proper cache invalidation is critical for maintaining accurate data while maximizing cache effectiveness.

## Learning Objectives
- Understand cache invalidation patterns and strategies
- Implement time-based expiration
- Create event-driven invalidation
- Handle cache tagging and purging
- Manage cache dependencies
- Implement cache versioning

## Requirements
Create a cache invalidation system with the following features:

1. **Invalidation Strategies**:
   - Time-based expiration (TTL)
   - Explicit invalidation
   - Event-driven invalidation
   - Pattern-based purging
   - Selective invalidation
   - Bulk invalidation

2. **Cache Tagging**:
   - Tag-based cache keys
   - Tag inheritance
   - Tag-based invalidation
   - Tag dependency tracking
   - Tag statistics
   - Tag cleanup

3. **Dependency Management**:
   - Cache key dependencies
   - Reverse dependency tracking
   - Cascading invalidation
   - Dependency graphs
   - Circular dependency handling
   - Dependency validation

4. **Advanced Features**:
   - Cache versioning
   - Graceful degradation
   - Lazy invalidation
   - Background cleanup
   - Invalidation queue
   - Metrics and monitoring

## Features to Implement
- [ ] Time-based expiration (TTL)
- [ ] Explicit invalidation
- [ ] Event-driven invalidation
- [ ] Pattern-based purging
- [ ] Cache tagging system
- [ ] Tag-based invalidation
- [ ] Cache key dependencies
- [ ] Cascading invalidation
- [ ] Cache versioning
- [ ] Graceful degradation
- [ ] Background cleanup
- [ ] Invalidation queue
- [ ] Metrics and monitoring

## Project Structure
```
backend-php/
├── src/
│   ├── Cache/
│   │   ├── Invalidation/
│   │   │   ├── InvalidationManager.php
│   │   │   ├── InvalidationStrategy.php
│   │   │   ├── TimeBasedInvalidation.php
│   │   │   ├── EventDrivenInvalidation.php
│   │   │   ├── PatternBasedInvalidation.php
│   │   │   └── SelectiveInvalidation.php
│   │   ├── Tags/
│   │   │   ├── TagManager.php
│   │   │   ├── TaggedCache.php
│   │   │   └── TagInvalidator.php
│   │   ├── Dependencies/
│   │   │   ├── DependencyTracker.php
│   │   │   ├── DependencyGraph.php
│   │   │   └── CascadingInvalidation.php
│   │   ├── Versioning/
│   │   │   ├── CacheVersion.php
│   │   │   └── VersionedCache.php
│   │   ├── Queue/
│   │   │   ├── InvalidationQueue.php
│   │   │   └── BackgroundInvalidator.php
│   │   └── Stats/
│   │       ├── InvalidationStats.php
│   │       └── HitMissTracker.php
│   ├── Events/
│   │   ├── CacheInvalidated.php
│   │   ├── CacheExpired.php
│   │   └── CachePurged.php
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
│   └── cache-invalidation.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── cache-invalidation.js
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

## Invalidation Strategies

### Time-Based Expiration
Automatic invalidation based on TTL:
```php
class TimeBasedInvalidation implements InvalidationStrategy
{
    public function shouldInvalidate($key, $metadata)
    {
        return time() > $metadata['expires_at'];
    }
    
    public function getTtl($key, $data)
    {
        // Different TTLs for different data types
        if (strpos($key, 'user:') === 0) {
            return 3600; // 1 hour for user data
        }
        if (strpos($key, 'product:') === 0) {
            return 86400; // 24 hours for product data
        }
        return 1800; // 30 minutes default
    }
}
```

### Event-Driven Invalidation
Invalidate cache based on application events:
```php
class EventDrivenInvalidation implements InvalidationStrategy
{
    public function registerEventListeners()
    {
        Event::listen('user.updated', function ($user) {
            $this->invalidateUserCache($user->id);
        });
        
        Event::listen('product.updated', function ($product) {
            $this->invalidateProductCache($product->id);
            // Also invalidate related category cache
            $this->invalidateCategoryCache($product->category_id);
        });
    }
    
    private function invalidateUserCache($userId)
    {
        $tags = ["user:$userId", "user-profile"];
        $this->tagManager->invalidateTags($tags);
    }
}
```

### Pattern-Based Purging
Invalidate cache keys matching patterns:
```php
class PatternBasedInvalidation
{
    public function purgeByPattern($pattern)
    {
        $keys = $this->cache->getKeysByPattern($pattern);
        foreach ($keys as $key) {
            $this->cache->delete($key);
        }
        return count($keys);
    }
    
    public function purgeUserCaches($userId = null)
    {
        if ($userId) {
            // Purge specific user's caches
            return $this->purgeByPattern("user:{$userId}:*");
        } else {
            // Purge all user caches
            return $this->purgeByPattern('user:*');
        }
    }
}
```

## Cache Tagging System
```php
class TagManager
{
    private $tagRegistry = [];
    
    public function tag($key, $tags)
    {
        foreach ($tags as $tag) {
            if (!isset($this->tagRegistry[$tag])) {
                $this->tagRegistry[$tag] = [];
            }
            $this->tagRegistry[$tag][] = $key;
        }
        
        // Store tags with the cache entry
        $this->cache->setMetadata($key, ['tags' => $tags]);
    }
    
    public function invalidateTags($tags)
    {
        foreach ($tags as $tag) {
            if (isset($this->tagRegistry[$tag])) {
                foreach ($this->tagRegistry[$tag] as $key) {
                    $this->cache->delete($key);
                }
                unset($this->tagRegistry[$tag]);
            }
        }
    }
    
    public function getKeysByTag($tag)
    {
        return $this->tagRegistry[$tag] ?? [];
    }
}
```

## Dependency Management
```php
class DependencyTracker
{
    private $dependencies = [];
    
    public function addDependency($key, $dependency)
    {
        if (!isset($this->dependencies[$dependency])) {
            $this->dependencies[$dependency] = [];
        }
        $this->dependencies[$dependency][] = $key;
    }
    
    public function invalidateDependency($dependency)
    {
        // Invalidate the dependency itself
        $this->cache->delete($dependency);
        
        // Cascade invalidation to dependent keys
        if (isset($this->dependencies[$dependency])) {
            foreach ($this->dependencies[$dependency] as $dependentKey) {
                $this->cache->delete($dependentKey);
                // Recursively invalidate dependents
                $this->invalidateDependency($dependentKey);
            }
            unset($this->dependencies[$dependency]);
        }
    }
}
```

## Configuration Example
```php
[
    'strategies' => [
        'time_based' => [
            'enabled' => true,
            'default_ttl' => 1800,
            'ttl_by_pattern' => [
                'user:*' => 3600,
                'product:*' => 86400,
                'category:*' => 43200
            ]
        ],
        'event_driven' => [
            'enabled' => true,
            'events' => [
                'user.updated' => ['user:{id}', 'user-profile:{id}'],
                'product.updated' => ['product:{id}', 'category:{category_id}']
            ]
        ]
    ],
    'tags' => [
        'auto_tagging' => true,
        'tag_patterns' => [
            'user:*' => ['user', 'profile'],
            'product:*' => ['product', 'catalog'],
            'category:*' => ['category', 'catalog']
        ]
    ],
    'dependencies' => [
        'auto_track' => true,
        'max_depth' => 5
    ],
    'queue' => [
        'enabled' => true,
        'batch_size' => 100,
        'process_interval' => 5
    ]
]
```

## API Endpoints
```
# Cache Invalidation Management
POST   /cache/invalidate            # Invalidate by key
POST   /cache/invalidate/tags       # Invalidate by tags
POST   /cache/invalidate/pattern    # Invalidate by pattern
POST   /cache/invalidate/dependency # Invalidate by dependency
GET    /cache/tags                  # List all tags
GET    /cache/tags/{tag}            # Get keys for a tag
DELETE /cache/tags/{tag}            # Delete a tag
GET    /cache/dependencies          # List dependencies
GET    /cache/stats/invalidation    # Get invalidation statistics
```

## Invalidation Statistics Response
```json
{
  "total_invalidations": 15420,
  "invalidations_by_type": {
    "time_based": 12450,
    "event_driven": 2340,
    "explicit": 630
  },
  "tags": {
    "total_tags": 142,
    "most_used": [
      {"tag": "user", "keys": 2450},
      {"tag": "product", "keys": 1842},
      {"tag": "category", "keys": 342}
    ]
  },
  "performance": {
    "average_invalidation_time": "2.4ms",
    "queue_size": 0,
    "failed_invalidations": 3
  },
  "last_24_hours": {
    "invalidations": 12450,
    "peak_hour": "14:00",
    "peak_count": 842
  }
}
```

## Evaluation Criteria
- [ ] Time-based expiration works correctly
- [ ] Event-driven invalidation triggers on events
- [ ] Pattern-based purging matches patterns
- [ ] Cache tagging system functions
- [ ] Tag-based invalidation removes tagged entries
- [ ] Cache key dependencies are tracked
- [ ] Cascading invalidation propagates correctly
- [ ] Cache versioning manages versions
- [ ] Graceful degradation handles failures
- [ ] Background cleanup processes queue
- [ ] Invalidation queue manages workload
- [ ] Metrics and monitoring collect data
- [ ] Code is well-organized and documented
- [ ] Tests cover invalidation functionality

## Resources
- [Cache Invalidation Strategies](https://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/Invalidation.html)
- [Cache Invalidation Patterns](https://microservices.io/patterns/data/event-sourcing.html)
- [Redis Cache Invalidation](https://redis.io/commands/expire)
- [HTTP Cache Invalidation](https://developer.mozilla.org/en-US/docs/Web/HTTP/Caching#cache_invalidation)