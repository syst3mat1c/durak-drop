<?php

namespace Modules\Main\Repositories;

use Illuminate\Support\Collection;
use Modules\Main\Entities\Withdraw;

class WithdrawRepository
{
    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function all()
    {
        return Withdraw::with(['user'])->orderBy('status')->orderByDesc('id')->paginate();
    }

    /**
     * @param array $data
     * @return Withdraw
     */
    public function store(array $data)
    {
        return Withdraw::create($data);
    }

    /**
     * @param Withdraw $withdraw
     * @param array $data
     * @return bool
     */
    public function update(Withdraw $withdraw, array $data)
    {
        return $withdraw->update($data);
    }

    /**
     * @param Withdraw $withdraw
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Withdraw $withdraw)
    {
        return $withdraw->delete();
    }

    /**
     * @return array
     */
    public function serializeStatuses()
    {
        return collect(Withdraw::STATUSES)->mapWithKeys(function($value) {
            return [$value => trans("ui.models.withdraws.statuses.{$value}")];
        })->toArray();
    }
}
