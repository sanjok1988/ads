<?php

namespace Sanjok\Blog\Filters\Posts;

use Sanjok\Blog\Filters\Filter;

class Category extends Filter
{
    protected function applyFilter($builder)
    {
        $input = request()->input('category');

        if (isset($input)) {
            return $builder->whereHas('category', function ($query) use ($input) {
                if (is_numeric($input)) {
                    return $query->where('category_id', $input);
                } else {
                    return $query->where('slug', $input);
                }
            });
        } else {
            return $builder;
        }
    }
}
