<?php

namespace App\Models;

use App\ORM\ActiveRecord;

class Post extends ActiveRecord
{
    protected static $table = 'posts';
    protected static $fillable = ['title', 'content', 'user_id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }
}