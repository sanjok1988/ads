<?php

namespace Sanjok\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'file', 'file_type',
        'document_type', 'status'
    ];
}
