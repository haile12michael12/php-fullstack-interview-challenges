# Challenge 77: Distributed Caching

## Description
In this challenge, you'll implement a distributed caching system that spans multiple servers to provide scalable and resilient caching capabilities. Distributed caching is essential for high-traffic applications that need to share cached data across multiple instances.

## Learning Objectives
- Understand distributed caching architectures
- Implement cache clustering and replication
- Handle cache consistency across nodes
- Manage cache partitioning and sharding
- Create cache node discovery
- Implement fault tolerance and recovery

## Requirements
Create a distributed caching system with the following features:

1. **Cluster Management**:
   - Node discovery and registration
   - Cluster topology management
   - Node health monitoring
   - Automatic failover
   - Load distribution
   - Cluster configuration

2. **Data Distribution**:
   - Consistent hashing for partitioning
   - Data replication strategies
   - Cache key routing
   - Data synchronization
   - Partition rebalancing
   - Backup and recovery

3. **Consistency Models**:
   - Strong consistency
   - Eventual consistency
   - Read-your-writes consistency
   - Cache coherency protocols
   - Conflict resolution
   - Version vectors

4. **Advanced Features**:
   - Cache warming across nodes
   - Distributed locking
   - Transaction support
   - Metrics aggregation
   - Cross-cluster replication
   - Security and encryption

## Features to Implement
- [ ] Node discovery and registration
- [ ] Cluster topology management
- [ ] Node health monitoring
- [ ] Automatic failover
- [ ] Consistent hashing partitioning
- [ ] Data replication
- [ ] Cache key routing
- [ ] Data synchronization
- [ ] Strong/eventual consistency
- [ ] Distributed locking
- [ ] Metrics aggregation
- [ ] Security and encryption

## Project Structure
```
backend-php/
├── src/
│   ├── Cache/
│   │   ├── Distributed/
│   │   │   ├── DistributedCache.php
│   │   │   ├── ClusterManager.php
│   │   │   ├── NodeRegistry.php
│   │   │   ├── HealthMonitor.php
│   │   │   └── FailoverManager.php
│   │   ├── Partitioning/
│   │   │   ├── ConsistentHashing.php
│   │   │   ├── PartitionRouter.php
│   │   │   ├── Rebalancer.php
│   │   │   └── ReplicaManager.php
│   │   ├── Consistency/
│   │   │   ├── ConsistencyManager.php
│   │   │   ├── VersionVector.php
│   │   │   ├── ConflictResolver.php
│   │   │   └── CoherencyProtocol.php
│   │   ├── Locking/
│   │   │   ├── DistributedLock.php
│   │   │   └── LockManager.php
│   │   ├── Security/
│   │   │   ├── EncryptionManager.php
│   │   │   └── AccessControl.php
│   │   └── Stats/
│   │       ├── ClusterStats.php
│   │       └── MetricsAggregator.php
│   ├── Network/
│   │   ├── ClusterNode.php
│   │   ├── MessageBus.php
│   │   └── RpcClient.php
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
│   └── distributed-cache.php
└── vendor/

frontend-react/
├── src/
│   ├── api/
│   │   └── distributed-cache.js
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

## Distributed Cache Architecture

### Node Discovery
Automatic discovery of cache nodes in the cluster:
```php
class NodeRegistry
{
    private $nodes = [];
    private $discoveryMethod;
    
    public function discoverNodes()
    {
        // Discover nodes via different methods
        switch ($this->discoveryMethod) {
            case 'multicast':
                return $this->discoverViaMulticast();
            case 'consul':
                return $this->discoverViaConsul();
            case 'kubernetes':
                return $this->discoverViaKubernetes();
            default:
                return $this->discoverViaConfig();
        }
    }
    
    public function registerNode($node)
    {
        $this->nodes[$node->getId()] = $node;
        $this->notifyClusterChange();
    }
    
    public function getNodes()
    {
        return array_filter($this->nodes, function($node) {
            return $node->isHealthy();
        });
    }
}
```

### Consistent Hashing
Distribute cache keys across nodes:
```php
class ConsistentHashing
{
    private $ring = [];
    private $nodes = [];
    private $replicas = 160; // Number of virtual nodes per physical node
    
    public function addNode($node)
    {
        $this->nodes[$node->getId()] = $node;
        
        // Create virtual nodes
        for ($i = 0; $i < $this->replicas; $i++) {
            $key = crc32($node->getId() . ':' . $i);
            $this->ring[$key] = $node->getId();
        }
        
        ksort($this->ring);
    }
    
    public function getNode($cacheKey)
    {
        if (empty($this->ring)) {
            return null;
        }
        
        $hash = crc32($cacheKey);
        foreach ($this->ring as $position => $nodeId) {
            if ($hash <= $position) {
                return $this->nodes[$nodeId];
            }
        }
        
        // Wrap around to the first node
        reset($this->ring);
        $firstNodeId = current($this->ring);
        return $this->nodes[$firstNodeId];
    }
}
```

### Data Replication
Replicate data across multiple nodes for redundancy:
```php
class ReplicaManager
{
    private $replicationFactor = 3;
    
    public function store($key, $value, $ttl = 3600)
    {
        $primaryNode = $this->hashRing->getNode($key);
        $replicaNodes = $this->getReplicaNodes($key, $this->replicationFactor - 1);
        
        // Store on primary node
        $primaryNode->set($key, $value, $ttl);
        
        // Store on replica nodes
        foreach ($replicaNodes as $node) {
            $node->set($key, $value, $ttl);
        }
        
        return true;
    }
    
    public function get($key)
    {
        $nodes = $this->getNodesForKey($key, $this->replicationFactor);
        
        // Try each node until we find the data
        foreach ($nodes as $node) {
            $value = $node->get($key);
            if ($value !== null) {
                return $value;
            }
        }
        
        return null;
    }
    
    private function getReplicaNodes($key, $count)
    {
        $nodes = [];
        $currentNode = $this->hashRing->getNode($key);
        
        // Get next nodes in the ring
        $ringKeys = array_keys($this->hashRing->getRing());
        $currentIndex = array_search(crc32($key), $ringKeys);
        
        for ($i = 1; $i <= $count; $i++) {
            $nextIndex = ($currentIndex + $i) % count($ringKeys);
            $nodeId = $this->hashRing->getNodeIdByPosition($ringKeys[$nextIndex]);
            $nodes[] = $this->nodes[$nodeId];
        }
        
        return $nodes;
    }
}
```

## Configuration Example
```php
[
    'cluster' => [
        'name' => 'cache-cluster-1',
        'discovery' => [
            'method' => 'consul',
            'consul_host' => 'consul.service.consul',
            'consul_port' => 8500
        ],
        'nodes' => [
            ['host' => 'cache-1.internal', 'port' => 6379],
            ['host' => 'cache-2.internal', 'port' => 6379],
            ['host' => 'cache-3.internal', 'port' => 6379]
        ]
    ],
    'partitioning' => [
        'algorithm' => 'consistent_hashing',
        'replicas' => 160,
        'rebalance_threshold' => 0.1
    ],
    'replication' => [
        'factor' => 3,
        'strategy' => 'synchronous',
        'consistency' => 'eventual'
    ],
    'failover' => [
        'enabled' => true,
        'timeout' => 5,
        'retries' => 3,
        'backup_nodes' => [
            ['host' => 'backup-cache-1.internal', 'port' => 6379]
        ]
    ],
    'security' => [
        'encryption' => true,
        'tls' => true,
        'auth' => [
            'enabled' => true,
            'username' => 'cache_user',
            'password' => 'secure_password'
        ]
    ]
]
```

## Cluster Management API
```php
class ClusterManager
{
    public function getClusterStatus()
    {
        return [
            'cluster_name' => $this->clusterName,
            'nodes' => $this->getNodeStatuses(),
            'partitioning' => $this->getPartitioningInfo(),
            'replication' => $this->getReplicationInfo(),
            'health' => $this->getClusterHealth()
        ];
    }
    
    public function addNode($nodeConfig)
    {
        $node = new ClusterNode($nodeConfig);
        $this->nodeRegistry->registerNode($node);
        $this->partitioning->addNode($node);
        $this->triggerRebalance();
    }
    
    public function removeNode($nodeId)
    {
        $this->nodeRegistry->unregisterNode($nodeId);
        $this->partitioning->removeNode($nodeId);
        $this->triggerRebalance();
    }
    
    public function rebalance()
    {
        $this->partitioning->rebalance();
        $this->replicaManager->resyncReplicas();
    }
}
```

## API Endpoints
```
# Distributed Cache Management
GET    /cache/cluster/status        # Get cluster status
GET    /cache/cluster/nodes         # List cluster nodes
POST   /cache/cluster/nodes         # Add a new node
DELETE /cache/cluster/nodes/{id}    # Remove a node
POST   /cache/cluster/rebalance     # Trigger cluster rebalancing
GET    /cache/partitioning          # Get partitioning information
GET    /cache/replication           # Get replication information
POST   /cache/backup                # Trigger backup
POST   /cache/restore               # Restore from backup
```

## Cluster Status Response
```json
{
  "cluster_name": "cache-cluster-1",
  "nodes": [
    {
      "id": "node-1",
      "host": "cache-1.internal",
      "port": 6379,
      "status": "healthy",
      "role": "primary",
      "memory_usage": "2.4GB",
      "keys": 1542000,
      "uptime": "15d 4h 22m"
    },
    {
      "id": "node-2",
      "host": "cache-2.internal",
      "port": 6379,
      "status": "healthy",
      "role": "primary",
      "memory_usage": "2.1GB",
      "keys": 1425000,
      "uptime": "15d 4h 20m"
    },
    {
      "id": "node-3",
      "host": "cache-3.internal",
      "port": 6379,
      "status": "healthy",
      "role": "primary",
      "memory_usage": "2.3GB",
      "keys": 1487000,
      "uptime": "15d 4h 25m"
    }
  ],
  "partitioning": {
    "algorithm": "consistent_hashing",
    "distribution": {
      "node-1": 33.4,
      "node-2": 33.3,
      "node-3": 33.3
    }
  },
  "replication": {
    "factor": 3,
    "consistency": "eventual",
    "sync_status": "synchronized"
  },
  "health": {
    "overall": "healthy",
    "issues": []
  }
}
```

## Evaluation Criteria
- [ ] Node discovery and registration work correctly
- [ ] Cluster topology management functions
- [ ] Node health monitoring detects failures
- [ ] Automatic failover maintains availability
- [ ] Consistent hashing distributes keys evenly
- [ ] Data replication provides redundancy
- [ ] Cache key routing directs to correct nodes
- [ ] Data synchronization maintains consistency
- [ ] Strong/eventual consistency models work
- [ ] Distributed locking prevents conflicts
- [ ] Metrics aggregation collects cluster data
- [ ] Security and encryption protect data
- [ ] Code is well-organized and documented
- [ ] Tests cover distributed caching functionality

## Resources
- [Distributed Caching Patterns](https://microservices.io/patterns/data/distributed-cache.html)
- [Redis Cluster](https://redis.io/topics/cluster-tutorial)
- [Memcached Distributed Caching](https://github.com/memcached/memcached)
- [Consistent Hashing](https://en.wikipedia.org/wiki/Consistent_hashing)