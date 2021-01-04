<?php

namespace Sanjok\Blog\Filters;

use Closure;
use Illuminate\Support\Str;

/**
 * Filter
 */
abstract class Filter
{
    /**
     * Method handle
     *
     * @param $request $request [explicite description]
     * @param Closure $next [explicite description]
     *
     * @return void
     */
    public function handle($request, Closure $next)
    {
        if (!request()->has($this->filterName())) {
            return $next($request);
        }
        return $this->applyFilter($next($request));
    }

    /**
     * Method applyFilter
     *
     * @param $builder $builder [explicite description]
     *
     * @return void
     */
    abstract protected function applyFilter($builder);

    /**
     * Method filterName
     *
     * @return void
     */
    protected function filterName()
    {
        return Str::snake(class_basename($this));
    }
}
