<?php

namespace App\Services\ScopeFilters\Filters;

use App\Services\ScopeFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends Filterable
{
    const DIRECTIONS = ['asc', 'desc'];

    protected $columns = ['created_at'];
    protected $column;
    protected $direction;

    /**
     * @param array $columns
     * @return OrderFilter
     */
    public function setColumns(array $columns = [])
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @param string $column
     * @return OrderFilter
     */
    public function setColumn(string $column = '')
    {
        $this->column = $column;
        return $this;
    }

    /**
     * @param string $direction
     * @return OrderFilter
     */
    public function setDirection(string $direction = '')
    {
        $this->direction = $direction;
        return $this;
    }

    public function handle(Builder $builder) : void
    {
        $builder->orderBy($this->column, $this->direction);
    }

    public function handleDefault(Builder $builder): void
    {
        try {
            $builder->defaultOrder();
        } catch (\Exception $e) {
            $builder->latest();
        }
    }

    /**
     * @param $data
     * @return bool
     */
    public function validate($data): bool
    {
        return is_string($this->direction) && in_array($this->direction, self::DIRECTIONS) &&
            is_string($this->column) && in_array($this->column, $this->columns);
    }

    public function beforeHandle(): void
    {

    }
}
