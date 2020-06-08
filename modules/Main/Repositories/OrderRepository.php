<?php

namespace Modules\Main\Repositories;

use Carbon\Carbon;
use Modules\Main\Entities\Deposit;
use Modules\Main\Entities\Order;

class OrderRepository {
    /**
     * @param Carbon $start
     * @param Carbon $end
     * @return float
     */
    public function calcPeriod(Carbon $start, Carbon $end) {
        return (float)Order::where('created_at', '>=', $start)->where('created_at', '<=', $end)->sum('amount');
    }

    /**
     * @return float
     */
    public function sumToday() {
        return (float)Order::where('created_at', '>=', now()->startOfDay())->sum('amount');
    }

    /**
     * @return float
     */
    public function sumAll() {
        return (float)Order::sum('amount');
    }

    /**
     * @param array $data
     * @return Order
     */
    public function store(array $data) {
        return Order::create($data);
    }

    /**
     * @param Order $order
     * @param array $data
     * @return bool
     */
    public function update(Order $order, array $data) {
        return $order->update($data);
    }

    /**
     * @param Order $order
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Order $order) {
        return $order->delete();
    }

    /**
     * @param Deposit $deposit
     * @return Order
     */
    public function addOrder(Deposit $deposit) {
        $currentDateTime = Carbon::now();
        $currentDateTime = $currentDateTime->toDateTimeString();
        $orderData       = [
            'user_id'    => $deposit->user_id,
            'amount'     => $deposit->amount_smart,
            'created_at' => $currentDateTime,
            'updated_at' => $currentDateTime,
        ];
        return $this->store($orderData);
    }
}
