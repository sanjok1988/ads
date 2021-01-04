<?php

namespace Sanjok\Blog\Contracts;

use Illuminate\Http\Request;
use Sanjok\Blog\Models\Ad;

interface IAds
{
    public function storeAd(array $data);
}
