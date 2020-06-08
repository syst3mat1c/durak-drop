<?php

namespace Modules\Main\Services\LiveFeed;

use Illuminate\Support\Collection;
use Modules\Main\Entities\Item;
use Cache;

class LiveFeedService
{
    const CACHE_BOXES_COUNT = 'LiveBoxesCount';
    const CACHE_BOXES_ITEMS = 'LiveBoxesItems';
    const CACHE_BOXES_ITEMS_TAKE = 15;

    public function __construct()
    {

    }

    /**
     * @return Collection
     */
    public function get()
    {
        return is_a($res = Cache::get(self::CACHE_BOXES_ITEMS)->take(20), Collection::class)
            ? $res : collect();
    }

    /**
     * @return int
     */
    public function count()
    {
        return Cache::get(self::CACHE_BOXES_COUNT) ?: 0;
    }

    /**
     * @param Item $item
     * @return Collection
     */
    public function add(Item $item)
    {
        $items = $this->get();

        $item = $this->resource($item);

        event (
            new LiveFeedEvent($item)
        );

        $items->prepend($item)->take(self::CACHE_BOXES_ITEMS_TAKE);

        Cache::increment(self::CACHE_BOXES_COUNT);
        Cache::forever(self::CACHE_BOXES_ITEMS, $items);

        return $items;
    }

    /**
     * @return void
     */
    public function flush()
    {
        Cache::forget(self::CACHE_BOXES_ITEMS);
        Cache::forget(self::CACHE_BOXES_COUNT);
    }

    /**
     * @param Item $item
     * @return array
     */
    public function resource(Item $item)
    {
        return $item->only(['id', 'type_human', 'rarity', 'plural', 'amount_human'])
            + ['user' => $item->user->only(['name', 'avatar_asset', 'profile_url'])];
    }
}
