<?php

namespace Modules\Users\Observers;

use Modules\Users\Entities\Chance;

class ChanceObserver
{
    /**
     * @param Chance $chance
     * @return void
     */
    public function saving(Chance $chance)
    {
        if (!isset($chance->status) && !isset($chance->iteration)) {
            $chance->status = Chance::STATUS_ENABLED;
            $chance->iteration = 0;
        }
    }
}
