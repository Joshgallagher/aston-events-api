<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilters
{
    /**
     * Request object.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Query builder object.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * Available filters.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Creates an instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Applies the filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * Get all relevant filters from the request.
     *
     * @return array
     */
    public function getFilters(): array
    {
        return array_filter($this->request->only($this->filters));
    }
}
