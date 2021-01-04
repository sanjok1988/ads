<?php

namespace Sanjok\Blog\Models;

use Sanjok\Blog\Models\AdContainer;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $table = "ads";
    protected $dates = ['deleted_at'];
    protected $fillable = ['title', 'image', 'container', 'url', 'is_public', 'user_id', 'start_at', 'end_at'];

    public function containers()
    {
        return $this->belongsToMany(AdContainer::class, 'ad_container', 'ad_id', 'container_id');
    }
}
