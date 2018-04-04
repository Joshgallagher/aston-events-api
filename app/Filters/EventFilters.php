<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class EventFilters extends AbstractFilters
{
    /**
     * Available Event filters.
     *
     * @var array
     */
    protected $filters = ['my', 'today'];

    /**
     * Return the authenticated users created Events.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function my(): Builder
    {
        return $this->builder->where('user_id', auth()->id());
    }

    /**
     * Return all Events taking place today.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function today(): Builder
    {
        return $this->builder->where('date', Carbon::today()->format('Y-m-d'));
    }
}
