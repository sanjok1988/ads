<?php

namespace Sanjok\Blog\Models;

use Sanjok\Blog\Models\Post;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $dates = ['deleted_at'];
    protected $fillable = ['filename', 'orginal_name', 'path', 'url', 'user_id', 'status'];

    public function tags()
    {
        return $this->morphTo();
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
