<?php

namespace App\Model;

use App\ORM\Model;

class User extends Model
{
    protected static string $table = 'users';
    protected static array $fillable = ['name', 'email', 'age', 'created_at', 'updated_at'];
    protected static array $hidden = ['password'];
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    
    public function posts()
    {
        return new class($this) {
            protected $user;
            
            public function __construct($user)
            {
                $this->user = $user;
            }
            
            public function __call($method, $parameters)
            {
                // Delegate to Post model query builder
                $query = Post::query();
                return $query->where('user_id', $this->user->id)->$method(...$parameters);
            }
            
            public function getResults()
            {
                return Post::query()->where('user_id', $this->user->id)->get();
            }
            
            public function __invoke()
            {
                return $this->getResults();
            }
        };
    }
    
    public function __call(string $method, array $parameters)
    {
        // Handle dynamic relationship methods
        if ($method === 'posts') {
            return $this->posts();
        }
        
        // Delegate to parent
        return parent::__call($method, $parameters);
    }
    
    public function __get(string $name)
    {
        // Handle relationship access
        if ($name === 'posts') {
            return $this->posts();
        }
        
        return parent::__get($name);
    }
    
    public function __toString(): string
    {
        return "User: {$this->name} ({$this->email})";
    }
}