<?php

namespace Modules\Users\Repositories;

use Modules\Users\Entities\Chance;
use Modules\Users\Entities\User;

class ChanceRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Chance::with(['user'])->get();
    }

    /**
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function findByUser(User $user)
    {
        return $user->chance();
    }

    /**
     * @param User $user
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(User $user, array $data)
    {
        $user->chance()->delete();
        return $user->chance()->create($data);
    }

    /**
     * @param Chance $chance
     * @param array $data
     * @return bool
     */
    public function updateJson(Chance $chance, array $data)
    {
        return $this->update($chance, ['json' => json_encode($data, 256)]);
    }

    /**
     * @param Chance $chance
     * @param array $data
     * @return bool
     */
    public function update(Chance $chance, array $data)
    {
        return $chance->update($data);
    }

    /**
     * @param Chance $chance
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Chance $chance)
    {
        return $chance->delete();
    }
}
