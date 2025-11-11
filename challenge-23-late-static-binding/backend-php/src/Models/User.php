<?php

namespace App\Models;

use App\ORM\ActiveRecord;

class User extends ActiveRecord
{
    protected static $table = 'users';
    protected static $fillable = ['name', 'email', 'password', 'created_at', 'updated_at'];
    protected static $hidden = ['password'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}