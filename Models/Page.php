<?php

namespace Sanjok\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Sanjok\Blog\Models\User;

class Page extends Model
{

  protected $dates = ['deleted_at'];
  protected $fillable = ['title', 'slug', 'content','feature_image','homepage','order','user_id','status'];

   public function user(){
      return $this->belongsTo(User::class, 'user_id');
   }

   public function getFeatureImageAttribute($feature_image)
    {
       if($feature_image){
           return asset($feature_image);
       }else{
         return false;
       }

    }

    public function scopeHomepage($query)
    {
        return $query->where('homepage', 1);
    }
}
