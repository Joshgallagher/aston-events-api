<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class EventFilter extends AbstractFilter
{
    /**
     * Available Event filters.
     *
     * @var array
     */
    protected $filters = ['my', 'today', 'popular', 'favorited'];

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

    /**
     * Return the most favorited Events in descending order.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function popular(): Builder
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('favorites_count', 'desc');
    }

    /**
     * Return an authenticated users favorited Events.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function favorited(): Builder
    {
        return $this->builder->whereHas('favorites', function ($query) {
            $query->where('user_id', auth()->id());
        });
    }
}
