<?php

namespace Modules\Main\Services\GamblingServices;

use Modules\Main\Entities\Box;

class GamblingManager
{
    /** @var Box */
    protected $box;

    /** @var int */
    protected $pieces;

    /** @var GamblingDistributor */
    protected $distributor;

    public function __construct(Box $box, int $pieces)
    {
        $this->box      = $box;
        $this->pieces   = $pieces;
    }

    public function pieces()
    {
        return $this->pieces;
    }

    public function box()
    {
        return $this->box;
    }

    public function price()
    {
        return $this->box->price * $this->pieces;
    }

    /**
     * @return GamblingDistributor
     */
    public function distribute()
    {
        if ($this->distributor) {
            return $this->distributor;
        } else {
            return $this->distributor = new GamblingDistributor($this);
        }
    }
}
