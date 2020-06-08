<?php

namespace Modules\Main\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Entities\Item;
use Modules\Users\Entities\User;

class ItemRepository
{
    /**
     * @param BoxItem $boxItem
     * @param User $user
     * @return Item
     */
    public function createFromBoxItem(BoxItem $boxItem, User $user)
    {
        return $this->store([
            'box_item_id'   => $boxItem->id,
            'user_id'       => $user->id,
            'status'        => Item::STATUS_CLOSE
        ]);
    }

    /**
     * @return Collection
     */
    public function getForLive()
    {
        return Item::open()->get();
    }

    /**
     * @param array $data
     * @return Item
     */
    public function store(array $data)
    {
        return Item::create($data);
    }

    /**
     * @param Item $item
     * @param array $data
     * @return bool
     */
    public function update(Item $item, array $data)
    {
        return $item->update($data);
    }

    /**
     * @param Item $item
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Item $item)
    {
        return $item->delete();
    }
}
