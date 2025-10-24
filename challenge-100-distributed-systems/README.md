# Challenge 100: Distributed Systems Patterns

## Description
In this final challenge, you'll implement distributed computing patterns in PHP. Distributed systems are collections of independent computers that appear to users as a single coherent system. You'll build implementations of key distributed patterns including consensus algorithms, distributed caching, message queues, and fault-tolerant architectures that form the foundation of modern scalable applications.

## Learning Objectives
- Understand distributed systems fundamentals and challenges
- Implement consensus algorithms (Raft, Paxos)
- Build distributed caching systems
- Create message queue implementations
- Implement fault-tolerant architectures
- Understand distributed transactions and consistency
- Build service discovery mechanisms
- Implement distributed locking systems

## Requirements

### Core Features
1. **Consensus Algorithms**
   - Implement Raft consensus algorithm
   - Build Paxos algorithm implementation
   - Create leader election mechanisms
   - Implement log replication
   - Handle cluster membership changes

2. **Distributed Caching**
   - Implement consistent hashing
   - Build cache replication strategies
   - Create cache invalidation protocols
   - Implement distributed cache clusters
   - Handle cache coherence

3. **Message Queues**
   - Build distributed message brokers
   - Implement message persistence
   - Create pub/sub patterns
   - Handle message ordering
   - Implement dead letter queues

4. **Fault Tolerance**
   - Implement circuit breaker patterns
   - Build retry mechanisms with exponential backoff
   - Create health check systems
   - Implement failover mechanisms
   - Build resilience patterns

### Implementation Details
1. **Distributed System Interface**
   ```php
   interface DistributedSystemInterface
   {
       public function joinCluster(string $nodeId): void;
       public function leaveCluster(string $nodeId): void;
       public function sendMessage(string $to, mixed $message): bool;
       public function broadcastMessage(mixed $message): void;
       public function getState(): array;
   }
   ```

2. **Consensus Interface**
   ```php
   interface ConsensusInterface
   {
       public function propose(mixed $value): bool;
       public function getCurrentLeader(): ?string;
       public function getCommittedValue(): mixed;
       public function addNode(string $nodeId): void;
   }
   ```

## Project Structure
```
challenge-100-distributed-systems/
├── backend-php/
│   ├── src/
│   │   ├── Distributed/
│   │   │   ├── DistributedSystem.php
│   │   │   ├── DistributedSystemInterface.php
│   │   │   ├── Node.php
│   │   │   └── Exception/
│   │   │       ├── DistributedException.php
│   │   │       └── ConsensusException.php
│   │   ├── Consensus/
│   │   │   ├── Raft.php
│   │   │   ├── Paxos.php
│   │   │   ├── ConsensusInterface.php
│   │   │   └── LogEntry.php
│   │   ├── Cache/
│   │   │   ├── DistributedCache.php
│   │   │   ├── ConsistentHashing.php
│   │   │   ├── ReplicationManager.php
│   │   │   └── CacheNode.php
│   │   ├── Messaging/
│   │   │   ├── MessageQueue.php
│   │   │   ├── MessageBroker.php
│   │   │   ├── Publisher.php
│   │   │   └── Subscriber.php
│   │   └── FaultTolerance/
│   │       ├── CircuitBreaker.php
│   │       ├── RetryMechanism.php
│   │       ├── HealthChecker.php
│   │       └── FailoverManager.php
│   ├── config/
│   ├── public/
│   │   └── index.php
│   ├── tests/
│   ├── composer.json
│   ├── Dockerfile
│   └── README.md
├── frontend-react/
│   ├── src/
│   │   ├── components/
│   │   │   ├── ClusterDashboard.jsx
│   │   │   ├── ConsensusVisualizer.jsx
│   │   │   ├── CacheMonitor.jsx
│   │   │   └── MessageQueue.jsx
│   │   └── App.jsx
│   ├── package.json
│   ├── vite.config.js
│   ├── Dockerfile
│   └── README.md
├── docker-compose.yml
└── README.md
```

## Setup Instructions

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 16+
- npm or yarn
- Docker (optional, for containerized deployment)
- Redis (for distributed caching examples)

### Backend Setup
1. Navigate to the [backend-php](file:///c%3A/projects/php-fullstack-challenges/challenge-100-distributed-systems/backend-php) directory
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Start multiple instances for distributed system simulation:
   ```bash
   # Terminal 1
   php public/index.php --node=node1 --port=8001
   
   # Terminal 2
   php public/index.php --node=node2 --port=8002
   
   # Terminal 3
   php public/index.php --node=node3 --port=8003
   ```

### Frontend Setup
1. Navigate to the [frontend-react](file:///c%3A/projects/php-fullstack-challenges/challenge-100-distributed-systems/frontend-react) directory
2. Install JavaScript dependencies:
   ```bash
   npm install
   ```
3. Start the development server:
   ```bash
   npm run dev
   ```

### Docker Setup
1. From the challenge root directory, run:
   ```bash
   docker-compose up -d
   ```
2. Access the application at `http://localhost:3000`

## API Endpoints

### Distributed Systems
- **POST** `/cluster/join` - Join a cluster node
- **POST** `/cluster/leave` - Leave a cluster node
- **POST** `/consensus/propose` - Propose a value for consensus
- **GET** `/cache/get` - Get value from distributed cache
- **POST** `/cache/set` - Set value in distributed cache
- **POST** `/messaging/publish` - Publish a message
- **POST** `/messaging/subscribe` - Subscribe to a topic

## Implementation Details

### Core Distributed Systems

1. **Distributed Node**
   ```php
   class DistributedNode
   {
       private string $nodeId;
       private string $address;
       private array $peers = [];
       private array $messageQueue = [];
       private bool $isLeader = false;
       
       public function __construct(string $nodeId, string $address)
       {
           $this->nodeId = $nodeId;
           $this->address = $address;
       }
       
       public function joinCluster(array $peerAddresses): void
       {
           foreach ($peerAddresses as $address) {
               $this->peers[] = $address;
               // Send join request to peer
               $this->sendHttpRequest($address, 'POST', '/cluster/join', [
                   'node_id' => $this->nodeId,
                   'address' => $this->address
               ]);
           }
       }
       
       public function sendMessage(string $to, mixed $message): bool
       {
           // In a real implementation, this would send to the actual node
           // For simulation, we'll add to message queue
           $this->messageQueue[] = [
               'to' => $to,
               'message' => $message,
               'timestamp' => microtime(true)
           ];
           
           return true;
       }
       
       public function broadcastMessage(mixed $message): void
       {
           foreach ($this->peers as $peer) {
               $this->sendMessage($peer, $message);
           }
       }
       
       public function processMessages(): void
       {
           while (!empty($this->messageQueue)) {
               $message = array_shift($this->messageQueue);
               $this->handleMessage($message);
           }
       }
       
       private function handleMessage(array $message): void
       {
           // Process different message types
           switch ($message['message']['type'] ?? '') {
               case 'heartbeat':
                   $this->handleHeartbeat($message);
                   break;
               case 'proposal':
                   $this->handleProposal($message);
                   break;
               case 'vote':
                   $this->handleVote($message);
                   break;
               default:
                   // Handle generic messages
                   break;
           }
       }
       
       private function sendHttpRequest(string $url, string $method, string $endpoint, array $data = []): void
       {
           // Simulate HTTP request to peer node
           // In real implementation, you would use cURL or similar
       }
   }
   ```

2. **Raft Consensus Implementation**
   ```php
   class RaftConsensus implements ConsensusInterface
   {
       private string $nodeId;
       private array $nodes = [];
       private string $state = 'follower'; // follower, candidate, leader
       private int $currentTerm = 0;
       private ?string $votedFor = null;
       private array $log = [];
       private int $commitIndex = 0;
       private int $lastApplied = 0;
       private array $nextIndex = [];
       private array $matchIndex = [];
       private ?string $leaderId = null;
       private int $electionTimeout = 0;
       private int $lastHeartbeat = 0;
       
       public function __construct(string $nodeId)
       {
           $this->nodeId = $nodeId;
           $this->resetElectionTimeout();
       }
       
       public function propose(mixed $value): bool
       {
           if ($this->state !== 'leader') {
               throw new ConsensusException('Only leader can propose values');
           }
           
           $logEntry = [
               'term' => $this->currentTerm,
               'value' => $value,
               'index' => count($this->log)
           ];
           
           $this->log[] = $logEntry;
           
           // Replicate to followers
           $this->replicateLog();
           
           return true;
       }
       
       public function getCurrentLeader(): ?string
       {
           return $this->leaderId;
       }
       
       public function getCommittedValue(): mixed
       {
           if ($this->commitIndex >= 0 && $this->commitIndex < count($this->log)) {
               return $this->log[$this->commitIndex]['value'] ?? null;
           }
           return null;
       }
       
       public function addNode(string $nodeId): void
       {
           $this->nodes[$nodeId] = [
               'address' => '',
               'last_heartbeat' => time()
           ];
       }
       
       public function receiveHeartbeat(string $leaderId, int $term): void
       {
           if ($term >= $this->currentTerm) {
               $this->currentTerm = $term;
               $this->leaderId = $leaderId;
               $this->state = 'follower';
               $this->lastHeartbeat = time();
               $this->resetElectionTimeout();
           }
       }
       
       public function requestVote(string $candidateId, int $term, int $lastLogIndex, int $lastLogTerm): array
       {
           $voteGranted = false;
           
           if ($term >= $this->currentTerm) {
               $this->currentTerm = $term;
               $this->votedFor = null;
               
               // Check if candidate's log is at least as up-to-date as receiver's log
               $lastLogEntry = end($this->log);
               $lastLogEntryTerm = $lastLogEntry['term'] ?? 0;
               $lastLogEntryIndex = key($this->log) ?? 0;
               
               if ($lastLogTerm > $lastLogEntryTerm || 
                   ($lastLogTerm === $lastLogEntryTerm && $lastLogIndex >= $lastLogEntryIndex)) {
                   
                   if ($this->votedFor === null || $this->votedFor === $candidateId) {
                       $this->votedFor = $candidateId;
                       $voteGranted = true;
                   }
               }
           }
           
           return [
               'term' => $this->currentTerm,
               'vote_granted' => $voteGranted
           ];
       }
       
       public function tick(): void
       {
           $now = time();
           
           // Check if election timeout has passed
           if ($this->state !== 'leader' && ($now - $this->lastHeartbeat) > $this->electionTimeout) {
               $this->startElection();
           }
           
           // If leader, send heartbeats
           if ($this->state === 'leader') {
               $this->sendHeartbeats();
           }
       }
       
       private function startElection(): void
       {
           $this->state = 'candidate';
           $this->currentTerm++;
           $this->votedFor = $this->nodeId;
           $this->leaderId = null;
           
           $votesReceived = 1; // Vote for self
           $totalNodes = count($this->nodes) + 1; // +1 for self
           
           // Request votes from other nodes
           foreach ($this->nodes as $nodeId => $node) {
               $response = $this->sendVoteRequest($nodeId);
               if ($response['vote_granted']) {
                   $votesReceived++;
               }
           }
           
           // Check if we have majority
           if ($votesReceived > $totalNodes / 2) {
               $this->becomeLeader();
           }
       }
       
       private function becomeLeader(): void
       {
           $this->state = 'leader';
           $this->leaderId = $this->nodeId;
           $this->lastHeartbeat = time();
           
           // Initialize nextIndex and matchIndex for each follower
           foreach ($this->nodes as $nodeId => $node) {
               $this->nextIndex[$nodeId] = count($this->log);
               $this->matchIndex[$nodeId] = 0;
           }
       }
       
       private function sendHeartbeats(): void
       {
           foreach ($this->nodes as $nodeId => $node) {
               $this->sendHeartbeat($nodeId);
           }
       }
       
       private function replicateLog(): void
       {
           foreach ($this->nodes as $nodeId => $node) {
               $this->sendAppendEntries($nodeId);
           }
       }
       
       private function resetElectionTimeout(): void
       {
           // Random timeout between 150-300ms
           $this->electionTimeout = rand(150, 300) / 1000;
       }
       
       private function sendVoteRequest(string $nodeId): array
       {
           // In real implementation, this would send HTTP request
           // For simulation, we'll return a mock response
           return [
               'term' => $this->currentTerm,
               'vote_granted' => rand(0, 1) === 1
           ];
       }
       
       private function sendHeartbeat(string $nodeId): void
       {
           // In real implementation, this would send HTTP request
       }
       
       private function sendAppendEntries(string $nodeId): void
       {
           // In real implementation, this would send HTTP request
       }
   }
   ```

3. **Distributed Cache with Consistent Hashing**
   ```php
   class DistributedCache
   {
       private array $nodes = [];
       private ConsistentHashing $hashRing;
       private int $replicationFactor = 3;
       
       public function __construct(int $replicationFactor = 3)
       {
           $this->replicationFactor = $replicationFactor;
           $this->hashRing = new ConsistentHashing();
       }
       
       public function addNode(string $nodeId, string $address): void
       {
           $this->nodes[$nodeId] = [
               'address' => $address,
               'data' => []
           ];
           $this->hashRing->addNode($nodeId);
       }
       
       public function set(string $key, mixed $value, int $ttl = 3600): bool
       {
           $primaryNode = $this->hashRing->getNode($key);
           
           if (!$primaryNode) {
               return false;
           }
           
           // Store on primary node
           $this->nodes[$primaryNode]['data'][$key] = [
               'value' => $value,
               'expires' => time() + $ttl
           ];
           
           // Replicate to other nodes
           $replicaNodes = $this->hashRing->getNodes($key, $this->replicationFactor);
           foreach ($replicaNodes as $nodeId) {
               if ($nodeId !== $primaryNode) {
                   $this->replicateToNode($nodeId, $key, $value, $ttl);
               }
           }
           
           return true;
       }
       
       public function get(string $key): mixed
       {
           $primaryNode = $this->hashRing->getNode($key);
           
           if (!$primaryNode) {
               return null;
           }
           
           // Check primary node
           $data = $this->nodes[$primaryNode]['data'][$key] ?? null;
           if ($data && $data['expires'] > time()) {
               return $data['value'];
           }
           
           // Check replica nodes
           $replicaNodes = $this->hashRing->getNodes($key, $this->replicationFactor);
           foreach ($replicaNodes as $nodeId) {
               if ($nodeId !== $primaryNode) {
                   $data = $this->nodes[$nodeId]['data'][$key] ?? null;
                   if ($data && $data['expires'] > time()) {
                       // Promote replica to primary
                       $this->nodes[$primaryNode]['data'][$key] = $data;
                       return $data['value'];
                   }
               }
           }
           
           return null;
       }
       
       public function delete(string $key): bool
       {
           $primaryNode = $this->hashRing->getNode($key);
           
           if (!$primaryNode) {
               return false;
           }
           
           unset($this->nodes[$primaryNode]['data'][$key]);
           
           // Delete from replica nodes
           $replicaNodes = $this->hashRing->getNodes($key, $this->replicationFactor);
           foreach ($replicaNodes as $nodeId) {
               if ($nodeId !== $primaryNode) {
                   unset($this->nodes[$nodeId]['data'][$key]);
               }
           }
           
           return true;
       }
       
       public function getNodeStatus(): array
       {
           $status = [];
           foreach ($this->nodes as $nodeId => $node) {
               $status[$nodeId] = [
                   'address' => $node['address'],
                   'keys' => count($node['data']),
                   'hash_position' => $this->hashRing->getNodePosition($nodeId)
               ];
           }
           return $status;
       }
       
       private function replicateToNode(string $nodeId, string $key, mixed $value, int $ttl): void
       {
           $this->nodes[$nodeId]['data'][$key] = [
               'value' => $value,
               'expires' => time() + $ttl,
               'replicated' => true
           ];
       }
   }
   
   class ConsistentHashing
   {
       private array $ring = [];
       private array $nodes = [];
       private int $replicas = 160; // Number of virtual nodes per physical node
       
       public function addNode(string $node): void
       {
           $this->nodes[$node] = true;
           for ($i = 0; $i < $this->replicas; $i++) {
               $key = crc32($node . $i);
               $this->ring[$key] = $node;
           }
           ksort($this->ring);
       }
       
       public function removeNode(string $node): void
       {
           unset($this->nodes[$node]);
           for ($i = 0; $i < $this->replicas; $i++) {
               $key = crc32($node . $i);
               unset($this->ring[$key]);
           }
       }
       
       public function getNode(string $key): ?string
       {
           if (empty($this->ring)) {
               return null;
           }
           
           $hash = crc32($key);
           foreach ($this->ring as $position => $node) {
               if ($hash <= $position) {
                   return $node;
               }
           }
           
           // Wrap around to the first node
           return reset($this->ring);
       }
       
       public function getNodes(string $key, int $count): array
       {
           if (empty($this->ring)) {
               return [];
           }
           
           $nodes = [];
           $hash = crc32($key);
           $ringKeys = array_keys($this->ring);
           
           // Find starting position
           $startIndex = 0;
           foreach ($ringKeys as $index => $position) {
               if ($hash <= $position) {
                   $startIndex = $index;
                   break;
               }
           }
           
           // Get unique nodes
           $ringCount = count($ringKeys);
           for ($i = 0; $i < $ringCount && count($nodes) < $count; $i++) {
               $position = $ringKeys[($startIndex + $i) % $ringCount];
               $node = $this->ring[$position];
               if (!in_array($node, $nodes)) {
                   $nodes[] = $node;
               }
           }
           
           return $nodes;
       }
       
       public function getNodePosition(string $node): array
       {
           $positions = [];
           for ($i = 0; $i < $this->replicas; $i++) {
               $key = crc32($node . $i);
               $positions[] = $key;
           }
           return $positions;
       }
   }
   ```

4. **Message Queue Implementation**
   ```php
   class MessageQueue
   {
       private array $topics = [];
       private array $subscribers = [];
       private array $messages = [];
       private int $messageIdCounter = 0;
       
       public function createTopic(string $topic): void
       {
           if (!isset($this->topics[$topic])) {
               $this->topics[$topic] = [
                   'messages' => [],
                   'subscribers' => []
               ];
           }
       }
       
       public function publish(string $topic, mixed $message, array $headers = []): string
       {
           if (!isset($this->topics[$topic])) {
               $this->createTopic($topic);
           }
           
           $messageId = 'msg_' . ++$this->messageIdCounter;
           $timestamp = microtime(true);
           
           $messageData = [
               'id' => $messageId,
               'topic' => $topic,
               'message' => $message,
               'headers' => $headers,
               'timestamp' => $timestamp,
               'status' => 'published'
           ];
           
           $this->topics[$topic]['messages'][] = $messageData;
           $this->messages[$messageId] = $messageData;
           
           // Deliver to subscribers
           $this->deliverMessage($topic, $messageData);
           
           return $messageId;
       }
       
       public function subscribe(string $topic, string $subscriberId, callable $callback): void
       {
           if (!isset($this->topics[$topic])) {
               $this->createTopic($topic);
           }
           
           if (!isset($this->subscribers[$subscriberId])) {
               $this->subscribers[$subscriberId] = [
                   'callback' => $callback,
                   'subscriptions' => []
               ];
           }
           
           $this->topics[$topic]['subscribers'][] = $subscriberId;
           $this->subscribers[$subscriberId]['subscriptions'][] = $topic;
       }
       
       public function acknowledge(string $messageId, string $subscriberId): bool
       {
           if (!isset($this->messages[$messageId])) {
               return false;
           }
           
           // Mark message as acknowledged by this subscriber
           $this->messages[$messageId]['acknowledged_by'][] = $subscriberId;
           
           return true;
       }
       
       public function getTopicStats(string $topic): array
       {
           if (!isset($this->topics[$topic])) {
               return [];
           }
           
           $topicData = $this->topics[$topic];
           return [
               'topic' => $topic,
               'message_count' => count($topicData['messages']),
               'subscriber_count' => count($topicData['subscribers']),
               'subscribers' => $topicData['subscribers']
           ];
       }
       
       private function deliverMessage(string $topic, array $message): void
       {
           if (!isset($this->topics[$topic])) {
               return;
           }
           
           foreach ($this->topics[$topic]['subscribers'] as $subscriberId) {
               if (isset($this->subscribers[$subscriberId])) {
                   try {
                       call_user_func($this->subscribers[$subscriberId]['callback'], $message);
                   } catch (Exception $e) {
                       // Handle delivery failure
                       error_log("Message delivery failed to {$subscriberId}: " . $e->getMessage());
                   }
               }
           }
       }
   }
   ```

5. **Circuit Breaker**
   ```php
   class CircuitBreaker
   {
       private string $name;
       private int $failureThreshold = 5;
       private int $timeout = 60; // seconds
       private int $failureCount = 0;
       private int $lastFailureTime = 0;
       private string $state = 'closed'; // closed, open, half-open
       
       public function __construct(string $name, int $failureThreshold = 5, int $timeout = 60)
       {
           $this->name = $name;
           $this->failureThreshold = $failureThreshold;
           $this->timeout = $timeout;
       }
       
       public function call(callable $function, array $args = [])
       {
           if ($this->state === 'open') {
               if ((time() - $this->lastFailureTime) > $this->timeout) {
                   $this->state = 'half-open';
               } else {
                   throw new CircuitBreakerException("Circuit breaker is open for {$this->name}");
               }
           }
           
           try {
               $result = call_user_func_array($function, $args);
               
               if ($this->state === 'half-open') {
                   $this->state = 'closed';
                   $this->failureCount = 0;
               }
               
               return $result;
           } catch (Exception $e) {
               $this->recordFailure();
               throw $e;
           }
       }
       
       private function recordFailure(): void
       {
           $this->failureCount++;
           $this->lastFailureTime = time();
           
           if ($this->failureCount >= $this->failureThreshold) {
               $this->state = 'open';
           }
       }
       
       public function getState(): string
       {
           return $this->state;
       }
       
       public function getFailureCount(): int
       {
           return $this->failureCount;
       }
       
       public function reset(): void
       {
           $this->state = 'closed';
           $this->failureCount = 0;
           $this->lastFailureTime = 0;
       }
   }
   ```

### Frontend Interface
The React frontend should:
1. Visualize distributed system clusters
2. Show consensus algorithm in action
3. Display distributed cache performance
4. Monitor message queue activity
5. Provide circuit breaker status
6. Offer educational content about distributed systems

## Evaluation Criteria
1. **Correctness** (30%)
   - Proper implementation of distributed algorithms
   - Correct consensus and coordination
   - Accurate distributed caching

2. **Scalability** (25%)
   - Efficient distributed operations
   - Proper handling of node failures
   - Good performance characteristics

3. **Robustness** (20%)
   - Fault tolerance implementation
   - Proper error handling
   - Recovery mechanisms

4. **Completeness** (15%)
   - Comprehensive distributed patterns
   - Multiple consensus algorithms
   - Full fault tolerance coverage

5. **Educational Value** (10%)
   - Clear explanations of distributed concepts
   - Practical examples and use cases
   - Interactive demonstrations

## Resources
1. [Raft Consensus Algorithm](https://raft.github.io/)
2. [Paxos Made Simple](https://lamport.azurewebsites.net/pubs/paxos-simple.pdf)
3. [Distributed Systems Principles](https://www.distributed-systems.net/)
4. [Consistent Hashing](https://en.wikipedia.org/wiki/Consistent_hashing)
5. [Circuit Breaker Pattern](https://martinfowler.com/bliki/CircuitBreaker.html)
6. [Distributed Cache Patterns](https://redis.io/topics/partitioning)
7. [Message Queue Patterns](https://www.enterpriseintegrationpatterns.com/)

## Stretch Goals
1. Implement distributed transactions (2PC, 3PC)
2. Create a service mesh implementation
3. Build a distributed file system
4. Implement event sourcing patterns
5. Create a distributed search engine
6. Develop advanced consensus algorithms (PBFT)
7. Implement distributed machine learning