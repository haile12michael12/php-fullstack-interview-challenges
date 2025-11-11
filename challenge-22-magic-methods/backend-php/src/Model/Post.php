<?php

namespace App\Model;

use App\ORM\Model;

class Post extends Model
{
    protected static string $table = 'posts';
    protected static array $fillable = ['title', 'content', 'user_id', 'created_at', 'updated_at'];
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    
    public function user()
    {
        return new class($this) {
            protected $post;
            
            public function __construct($post)
            {
                $this->post = $post;
            }
            
            public function __call($method, $parameters)
            {
                // Delegate to User model
                $user = User::find($this->post->user_id);
                return $user ? $user->$method(...$parameters) : null;
            }
            
            public function getResults()
            {
                return User::find($this->post->user_id);
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
        if ($method === 'user') {
            return $this->user();
        }
        
        // Delegate to parent
        return parent::__call($method, $parameters);
    }
    
    public function __get(string $name)
    {
        // Handle relationship access
        if ($name === 'user') {
            return $this->user();
        }
        
        return parent::__get($name);
    }
    
    public function __toString(): string
    {
        return "Post: {$this->title}";
    }
}