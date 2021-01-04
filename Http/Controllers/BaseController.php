<?php

namespace Sanjok\Blog\Http\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function getView($path)
    {
        return config('blog.package').'::'.$path;
    }

}
