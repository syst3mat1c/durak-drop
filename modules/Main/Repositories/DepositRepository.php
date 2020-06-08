<?php

namespace Modules\Main\Repositories;

use Modules\Main\Entities\Deposit;
use Modules\Main\Entities\Promocode;
use Modules\Users\Entities\User;

class DepositRepository
{
    /**
     * @param array $data
     * @return Deposit
     */
    public function store(array $data)
    {
        return Deposit::create($data);
    }

    /**
     * @param Deposit $deposit
     * @param array $data
     * @return bool
     */
    public function update(Deposit $deposit, array $data)
    {
        return $deposit->update($data);
    }

    /**
     * @param Deposit $deposit
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Deposit $deposit)
    {
        return $deposit->delete();
    }

    /**
     * @param User $user
     * @param float $amount
     * @param null $promocodeId
     * @return Deposit
     */
    public function create(User $user, float $amount, $promocodeId = null)
    {
        return $this->store([
            'user_id'       => $user->id,
            'amount'        => $amount,
            'status'        => Deposit::STATUS_PENDING,
            'promocode_id'  => $promocodeId,
        ]);
    }

    /**
     * @param int $depositId
     * @return Deposit|null
     */
    public function find(int $depositId)
    {
        return Deposit::find($depositId);
    }
}
