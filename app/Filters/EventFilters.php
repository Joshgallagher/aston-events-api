<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class EventFilters extends AbstractFilters
{
    /**
     * Available Event filters.
     *
     * @var array
     */
    protected $filters = ['my'];

    /**
     * Filter the query to only return the currently
     * authenticated User's created Events.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function my(): Builder
    {
        return $this->builder->where('user_id', auth()->id());
    }
}
