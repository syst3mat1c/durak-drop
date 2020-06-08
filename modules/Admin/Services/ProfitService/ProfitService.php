<?php

namespace Modules\Admin\Services\ProfitService;

use Iterator;

class ProfitService implements Iterator
{
    protected $days = 12;

    protected $position = 0;

    protected $daysServices;

    public function __construct()
    {
        $this->position = 0;
        $this->daysServices = collect();

        $this->run();
    }

    protected function run()
    {
        foreach (range(0, $this->days) as $i) {
            $calculatedDays = $this->calculateDays(-$i);
            $this->daysServices->push(
                new ProfitDayService(new ProfitInterval($calculatedDays['start'], $calculatedDays['end']))
            );
        }
    }

    /**
     * @param int $daysBefore
     * @return array
     */
    final private function calculateDays(int $daysBefore)
    {
        return [
            'start' => ($start = now()->addDays($daysBefore)->startOfDay()),
            'end'   => $start->copy()->endOfDay()
        ];
    }

    /**
     * @return int
     */
    public function days()
    {
        return $this->days;
    }

    /**
     * @return float
     */
    public function totalIncome()
    {
        return money($this->daysServices->sum(function(ProfitDayService $profitDayService) {
            return $profitDayService->income(false);
        }));
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->daysServices->has($this->position);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->daysServices->get($this->position);
    }

    /**
     * @return int|mixed
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return void
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }
}
