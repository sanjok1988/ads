<?php

namespace Sanjok\Blog\Models;

use Sanjok\Blog\Post;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'url', 'parent', 'slug', 'icon', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function posts()
    {
        return $this->belongsToMany('Sanjok\Blog\Models\Post', 'category_post', 'category_id', 'post_id');
    }
}
