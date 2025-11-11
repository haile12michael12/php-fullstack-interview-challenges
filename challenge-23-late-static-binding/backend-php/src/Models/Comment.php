<?php

namespace App\Models;

use App\ORM\ActiveRecord;

class Comment extends ActiveRecord
{
    protected static $table = 'comments';
    protected static $fillable = ['content', 'user_id', 'post_id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}