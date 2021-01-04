<?php

namespace Sanjok\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Sanjok\Blog\Models\User;

class Post extends Model
{
    protected $dates = ['deleted_at'];
    protected $fillable = ['title', 'slug', 'content', 'excerpt', 'image', 'user_id', 'author', 'url', 'status', 'published_at'];

    public function categories()
    {
        return $this->belongsToMany('Sanjok\Blog\Models\Category', 'category_post', 'post_id', 'category_id');
    }

    public function category()
    {
        return $this->belongsToMany('Sanjok\Blog\Models\Category', 'category_post', 'post_id', 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->select(['id', 'name']);
    }

    public function medias()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function metaTags()
    {
        return $this->morphOne('meta_tags', 'model_id', 'post_id', 'id');
    }

//    public function getFeatureImageAttribute($feature_image)
//     {
//        if($feature_image){
//            return asset($feature_image);
//        }else{
//          return false;
//        }

//     }
}
