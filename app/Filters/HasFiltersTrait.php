<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

trait HasFiltersTrait
{
    /**
     * Apply all relevant filters to the given Model.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Filters\AbstractFilters          $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, AbstractFilters $filters): Builder
    {
        return $filters->apply($query);
    }
}
