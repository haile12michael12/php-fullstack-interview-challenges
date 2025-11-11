<?php

namespace App\Models;

use App\ORM\ActiveRecord;

class Category extends ActiveRecord
{
    protected static $table = 'categories';
    protected static $fillable = ['name', 'description', 'created_at', 'updated_at'];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_category');
    }
}