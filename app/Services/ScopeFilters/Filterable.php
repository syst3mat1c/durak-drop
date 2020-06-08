<?php

namespace App\Services\ScopeFilters;

use Illuminate\Database\Eloquent\Builder;

abstract class Filterable
{
    /** @var mixed */
    protected $input;

    /**
     * @param $data
     * @return Filterable
     */
    public function setInput($data): Filterable
    {
        $this->input = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return void
     */
    public function beforeHandle() : void {}

    /**
     * @param Builder $builder
     * @return void
     */
    public function handleDefault(Builder $builder) : void {}

    /**
     * @param Builder $builder
     * @return void
     */
    abstract public function handle(Builder $builder): void;

    /**
     * @param $data
     * @return bool
     */
    abstract function validate($data): bool;
}
