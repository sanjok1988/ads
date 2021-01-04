<?php

namespace Sanjok\Blog\Models;

use Sanjok\Blog\Models\Page;
use Sanjok\Blog\Models\Post;
use Sanjok\Blog\Models\Team;
// use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
// use Spatie\Permission\Traits\HasRoles;
// use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // use Notifiable;
    // use HasRoles;
    // use HasApiTokens;
    // use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getGravatarAttribute()
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash";
    }

    /*return posts*/

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /*return pages*/
    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    /*return topics*/
    // public function topics()
    // {
    //     return $this->hasMany(Topics::class);
    // }
    public function teams()
    {
        return $this->belongsTo(Team::class);
    }

    public static function getUserId()
    {
        if ($user = Auth::user()) {
            return $user->id;
        }

        return null;
    }
}
