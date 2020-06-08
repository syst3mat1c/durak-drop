<?php

namespace Modules\Main\Repositories;

use Illuminate\Support\Collection;
use Modules\Main\Entities\Box;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Entities\Item;
use Cache;

class BoxRepository
{
    const CACHE_OPEN_PREFIX = 'BoxRepo_Open_';

    /**
     * @param Box $box
     * @param int $wealth
     * @return BoxItem|null
     */
    public function randomItemWealth(Box $box, int $wealth)
    {
        return $box->boxItems()->drop()->whereWealth($wealth)->inRandomOrder()->first();
    }

    /**
     * @param Box $box
     * @param array $wealths
     * @return mixed
     */
    public function randomItemWealthIn(Box $box, array $wealths)
    {
        return $box->boxItems()->drop()->whereIn('wealth', $wealths)->inRandomOrder()->first();
    }

    public function all()
    {
        return Box::latest()->paginate();
    }

    /**
     * @return Collection
     */
    public function getAll()
    {
        return Box::enabled()->get();
    }

    /**
     * @param Box $box
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getItems(Box $box)
    {
        return $box->boxItems()->orderByDesc('price')->get();
    }

    /**
     * @param Box $box
     * @return array
     */
    public function getClassIds(Box $box)
    {
        return $box->boxItems()->pluck('classid')->unique()->values()->toArray();
    }

    /**
     * @return mixed
     */
    public function proposeOrder()
    {
        return ((int) optional(Box::select('order')->orderBy('order')->first())->order) + 1;
    }

    /**
     * @return array
     */
    public function resourceTypes()
    {
        return collect(Box::TYPES)->mapWithKeys(function($type) {
            return [$type => trans(Box::LANG_TYPE_PATH . $type)];
        })->toArray();
    }

    /**
     * @return array
     */
    public function resourceStatuses()
    {
        return collect(Box::STATUSES)->mapWithKeys(function($status) {
            return [$status => trans(Box::LANG_STATUS_PATH . $status)];
        })->toArray();
    }

    /**
     * @param Box $box
     * @return int
     */
    public function countOpen(Box $box)
    {
        return Cache::get(self::CACHE_OPEN_PREFIX . $box->id) ?: 0;
    }

    /**
     * @param Box $box
     * @return void
     */
    public function addCountOpen(Box $box)
    {
        Cache::has(self::CACHE_OPEN_PREFIX . $box->id)
            ? Cache::increment(self::CACHE_OPEN_PREFIX . $box->id)
            : Cache::put(self::CACHE_OPEN_PREFIX . $box->id, 1, 15);
    }

    /**
     * @return Box
     */
    public function first()
    {
        return Box::first();
    }

    /**
     * @param array $data
     * @return Box
     */
    public function store(array $data)
    {
        return Box::create($data);
    }

    /**
     * @param Box $box
     * @param array $data
     * @return bool
     */
    public function update(Box $box, array $data)
    {
        return $box->update($data);
    }

    /**
     * @param Box $box
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Box $box)
    {
        return $box->delete();
    }

    /**
     * @return array
     */
    public function serializeIcons()
    {
        return collect(Box::ICONS)->mapWithKeys(function($value) {
            return [$value => trans("ui.models.boxes.icons.{$value}")];
        })->toArray();
    }

    /**
     * @return array
     */
    public function serializeRarities()
    {
        return collect(Box::RARITIES)->mapWithKeys(function($value) {
            return [$value => trans("ui.models.boxes.rarities.{$value}")];
        })->toArray();
    }
}
