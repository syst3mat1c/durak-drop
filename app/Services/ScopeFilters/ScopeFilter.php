<?php

namespace App\Services\ScopeFilters;

use Illuminate\Database\Eloquent\Builder;

class ScopeFilter
{
    /** @var Builder */
    protected $builder;

    /** @var \Illuminate\Support\Collection */
    protected $filters;

    /**
     * ScopeFilter constructor.
     * @param Builder $builder
     * @param array $filters
     */
    public function __construct(Builder $builder, array $filters = [])
    {
        $this->filters = collect();

        $this->builder = $builder;

        collect($filters)->each(function($filter) {
            $this->addFilter($filter);
        });
    }

    /**
     * @param $filter
     * @return void
     */
    public function addFilter($filter)
    {
        if (is_object($filter) && is_a($filter, Filterable::class)) {
            $this->filters->push($filter);
        }
    }

    /**
     * @return void
     */
    public function gargle()
    {
        $this->filters->each(function(Filterable $filter) {
            $this->useFilter($filter);
        });
    }

    /**
     * @param Filterable $filter
     * @return void
     */
    public function useFilter(Filterable $filter)
    {
        if ($filter->validate($filter->getInput())) {
            $filter->beforeHandle();
            $filter->handle($this->builder);
        } else {
            $filter->handleDefault($this->builder);
        }
    }
}
