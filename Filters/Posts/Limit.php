<?php

namespace Sanjok\Blog\Filters\Posts;

use Closure;
use Sanjok\Blog\Filters\Filter;

class Limit extends Filter
{
    protected function applyFilter($builder)
    {
        $limit = request()->input('limit');
      
        if ($limit>0) {
            return $builder->limit($limit);
        } else {
            return $builder;
        }
    }
}
