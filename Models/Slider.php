<?php namespace Sanjok\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use  Sanjok\Blog\Models\User;

class Slider extends Model
{
    protected $dates = ['deleted_at'];
    protected $fillable = ['title', 'subtitle', 'link', 'image','status', 'content', 'user_id'];

    /*Make relation with users*/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*get image */
//   public function getImageAttribute($image)
//    {
//       if($image){
//           return asset($image);
//       }else{
//         return false;
//       }

//    }
}
