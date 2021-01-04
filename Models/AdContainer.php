<?php

namespace Sanjok\Blog\Models;

use Sanjok\Blog\Models\Ad;
use Sanjok\Blog\Models\User;
use Illuminate\Database\Eloquent\Model;

class AdContainer extends Model
{
    protected $table = "containers";
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'width',
        'height',
        'is_public',
        'placeholder',
        'url',
        'rate',
        'payment_method'
    ];

    public function ads () {
        $this->hasMany(Ad::class);
    }
}
