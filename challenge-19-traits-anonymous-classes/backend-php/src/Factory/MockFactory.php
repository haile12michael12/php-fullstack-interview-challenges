<?php

namespace App\Factory;

use App\Contracts\CacheInterface;
use App\Traits\SerializableTrait;

class MockFactory
{
    public static function createArrayCache(): CacheInterface
    {
        return new class implements CacheInterface {
            use \App\Traits\SerializableTrait;
            
            private array $storage = [];
            
            public function get(string $key)
            {
                if (!$this->has($key)) {
                    return null;
                }
                
                $item = $this->storage[$key];
                if (time() > $item['expires']) {
                    $this->delete($key);
                    return null;
                }
                
                return $item['data'];
            }
            
            public function set(string $key, $value, int $ttl = 3600): bool
            {
                $this->storage[$key] = [
                    'data' => $value,
                    'expires' => time() + $ttl
                ];
                
                return true;
            }
            
            public function has(string $key): bool
            {
                return isset($this->storage[$key]);
            }
            
            public function delete(string $key): bool
            {
                if ($this->has($key)) {
                    unset($this->storage[$key]);
                    return true;
                }
                
                return false;
            }
            
            public function clear(): bool
            {
                $this->storage = [];
                return true;
            }
            
            public function getStats(): array
            {
                return [
                    'count' => count($this->storage),
                    'keys' => array_keys($this->storage)
                ];
            }
        };
    }
    
    public static function createUserService(): object
    {
        return new class {
            use \App\Traits\LoggerTrait;
            use \App\Traits\TimestampableTrait;
            
            private array $users = [];
            
            public function createUser(array $data): array
            {
                $this->updateTimestamps();
                
                $user = array_merge($data, [
                    'id' => uniqid(),
                    'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
                    'updated_at' => $this->getUpdatedAt()->format('Y-m-d H:i:s')
                ]);
                
                $this->users[] = $user;
                $this->info("Created user with ID: {$user['id']}");
                
                return $user;
            }
            
            public function getUsers(): array
            {
                return $this->users;
            }
        };
    }
}