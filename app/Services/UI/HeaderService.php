<?php

namespace App\Services\UI;

class HeaderService
{
    /** @var \Illuminate\Support\Collection */
    protected $segments;

    protected $title;

    /**
     * HeaderService constructor.
     */
    public function __construct()
    {
        $this->clear();
    }

    /**
     * @param string $title
     * @return HeaderService
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $segment
     * @return HeaderService
     */
    public function add(string $segment)
    {
        $this->segments->push($segment);
        return $this;
    }

    /**
     * @param string $segment
     * @return HeaderService
     */
    public function addOnly(string $segment)
    {
        $this->clear();
        $this->segments->push($segment);
        return $this;
    }

    /**
     * @return HeaderService
     */
    public function clear()
    {
        $this->segments = collect();
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title ?: $this->getDefault();
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->has()
            ? $this->segments->implode(': ')
            : $this->getDefault();
    }

    /**
     * @return string
     */
    protected function getDefault()
    {
        return 'Durak drop | Дурак дроп';
    }

    /**
     * @return bool
     */
    public function has()
    {
        return (bool) $this->segments->count();
    }
}
