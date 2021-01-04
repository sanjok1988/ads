<?php

namespace Sanjok\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Sanjok\Blog\Models\User;

class Attachment extends Model
{
    protected $dates = ['deleted_at'];
    protected $fillable = ['filename', 'orginal_name', 'path', 'url', 'user_id', 'status'];

    public function attachment()
    {
        return $this->morphTo();
    }
}
