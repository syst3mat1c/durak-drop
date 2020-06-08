<?php

namespace Modules\Admin\Services\ProfitService;

use Carbon\Carbon;

class ProfitInterval
{
    /** @var Carbon */
    protected $start;

    /** @var Carbon */
    protected $end;

    /**
     * ProfitInterval constructor.
     * @param Carbon $start
     * @param Carbon $end
     */
    public function __construct(Carbon $start, Carbon $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return Carbon
     */
    public function start()
    {
        return $this->start;
    }

    /**
     * @return Carbon
     */
    public function end()
    {
        return $this->end;
    }

    /**
     * @return bool
     */
    public function isToday()
    {
        return $this->start->isCurrentDay() && $this->end->isCurrentDay();
    }
}
