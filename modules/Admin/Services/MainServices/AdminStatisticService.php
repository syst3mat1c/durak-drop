<?php

namespace Modules\Admin\Services\MainServices;

use Modules\Main\Repositories\ItemRepository;
use Modules\Main\Repositories\OrderRepository;
use Modules\Users\Repositories\UserRepository;
use Cache;

class AdminStatisticService
{
    /** @var UserRepository */
    protected $userRepo;

    /** @var OrderRepository */
    protected $orderRepo;

    /** @var ItemRepository */
    protected $itemRepo;

    public function __construct(UserRepository $userRepository, OrderRepository $orderRepository,
                                ItemRepository $itemRepository)
    {
        $this->userRepo = $userRepository;
        $this->orderRepo = $orderRepository;
        $this->itemRepo = $itemRepository;
    }

    /**
     * Зарегистрировано пользователей за всё время
     * @return string
     */
    public function usersTotalRegistered()
    {
        return Cache::remember('statistics_usersTotalRegistered', 15, function() {
            return _count($this->userRepo->totalRegisteredCount(), '');
        });
    }

    /**
     * Зарегистрировано пользователей сегодня
     * @return string
     */
    public function usersDailyRegistered()
    {
        return Cache::remember('statistics_usersDailyRegistered', 15, function() {
            return _count($this->userRepo->todayRegisteredCount(), '');
        });
    }

    /**
     * Баланс бота
     * @return string
     */
    public function botBalance()
    {
        return Cache::remember('statistics_botBalance', 15, function() {
            return money($this->itemRepo->sumTradeBalance());
        });
    }

    /**
     * Доступно пин-кодов у бота
     * @return string
     */
    public function botPinsAvailable()
    {
        return Cache::remember('statistics_botPinsAvailable', 15, function() {
            return _count($this->itemRepo->countTradeBalance());
        });
    }

    /**
     * Пополнений за сегодня
     * @return string
     */
    public function depositsDaily()
    {
        return Cache::remember('statistics_depositsDaily', 15, function() {
            return money($this->orderRepo->sumToday());
        });
    }

    /**
     * Доход за сегодня
     * @return string
     */
    public function incomeDaily()
    {
        return Cache::remember('statistics_incomeDaily', 15, function() {
            return money(0);
        });
    }

    /**
     * Пополнений всего
     * @return string
     */
    public function depositsTotal()
    {
        return Cache::remember('statistics_depositsTotal', 15, function() {
            return money($this->orderRepo->sumAll());
        });
    }
}
