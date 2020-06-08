<?php

namespace Modules\Admin\Services\ProfitService;

use Cache;
use Modules\Main\Repositories\ItemRepository;
use Modules\Main\Repositories\OrderRepository;

class ProfitDayService
{
    protected $interval;

    protected $cacheLifetime = 30;

    const CACHE_NAME_DEPOSITS   = 'deposits';
    const CACHE_NAME_OUTPUTS    = 'outputs';
    const CACHE_NAME_INCOME     = 'income';
    const CACHE_NAMES = [self::CACHE_NAME_INCOME, self::CACHE_NAME_DEPOSITS, self::CACHE_NAME_OUTPUTS];

    /**
     * ProfitDayService constructor.
     * @param ProfitInterval $interval
     */
    public function __construct(ProfitInterval $interval)
    {
        $this->interval = $interval;

        $this->prepare();
    }

    protected function prepare()
    {
        if ($this->isToday()) {
            $this->flushCache();
        }
    }

    /**
     * @return void
     */
    protected function flushCache()
    {
        foreach (self::CACHE_NAMES as $cacheName) {
            Cache::forget($this->getCacheName($cacheName));
        }
    }

    /**
     * @return bool
     */
    protected function isToday()
    {
        return $this->interval->isToday();
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->interval->start()->format('d.m.Y');
    }

    /**
     * Сумма всех пополнений
     * @param bool $formatted
     * @return string
     */
    public function deposits(bool $formatted = true)
    {
        $amount = Cache::remember($this->getCacheName(self::CACHE_NAME_DEPOSITS), $this->cacheLifetime, function() {
            return app(OrderRepository::class)->calcPeriod($this->interval->start(), $this->interval->end());
        });

        return $formatted ? money($amount) : $amount;
    }

    /**
     * Сумма всех выводов
     * @param bool $formatted
     * @return string
     */
    public function outputs(bool $formatted = true)
    {
        $amount = Cache::remember($this->getCacheName(self::CACHE_NAME_OUTPUTS), $this->cacheLifetime, function() {
            return app(ItemRepository::class)->calcOutputs($this->interval->start(), $this->interval->end());
        });

        return $formatted ? money($amount) : $amount;
    }

    /**
     * Сумма дохода
     * @param bool $formatted
     * @return string
     */
    public function income(bool $formatted = true)
    {
        $amount = Cache::remember($this->getCacheName(self::CACHE_NAME_INCOME), $this->cacheLifetime, function() {
            return $this->deposits(false) - $this->outputs(false);
        });

        return $formatted ? money($amount) : $amount;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getCacheName(string $name)
    {
        return 'ProfitDayService' . $this->getDate() . '_' . $name;
    }
}
