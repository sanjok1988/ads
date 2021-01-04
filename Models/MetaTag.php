<?php

namespace Sanjok\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class MetaTag extends Model
{
    protected $dates = ['deleted_at'];
    protected $fillable = ['meta_title', 'meta_description', 'meta_image', 'meta_url'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
