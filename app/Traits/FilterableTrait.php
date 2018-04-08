<?php

namespace App\Traits;

use App\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

trait FilterableTrait
{
    /**
     * Apply all relevant filters to the given Model.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Filters\AbstractFilters          $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, AbstractFilter $filters): Builder
    {
        return $filters->apply($query);
    }
}
