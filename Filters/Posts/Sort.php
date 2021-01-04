<?php

namespace Sanjok\Blog\Filters\Posts;

use Closure;
use Sanjok\Blog\Filters\Filter;

class Sort extends Filter
{
    protected function applyFilter($builder)
    {
        $order = request()->input('sort');
        if ($order == 'asc' || $order == 'desc') {
            return $builder->orderBy('title', $order);
        } else {
            return $builder;
        }
    }
}
