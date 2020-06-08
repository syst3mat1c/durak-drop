<?php

namespace Modules\Main\Repositories;

use Modules\Main\Entities\Promocode;

class PromocodeRepository
{
    /**
     * @return mixed
     */
    public function all()
    {
        return Promocode::withCounts()->get();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        return Promocode::create($data);
    }

    /**
     * @param Promocode $promocode
     * @param array $data
     * @return bool
     */
    public function update(Promocode $promocode, array $data)
    {
        return $promocode->update($data);
    }

    /**
     * @param Promocode $promocode
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Promocode $promocode)
    {
        return $promocode->delete();
    }

    /**
     * @param string $code
     * @return Promocode|null
     */
    public function findByCode(string $code = null)
    {
        return Promocode::where('code', $code)->withCounts()->first();
    }
}
