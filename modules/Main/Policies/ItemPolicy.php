<?php

namespace Modules\Main\Policies;

use App\Policies\Policyable;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Entities\Item;
use Modules\Users\Entities\User;

class ItemPolicy
{
    use HandlesAuthorization, Policyable;

    /**
     * @param User $user
     * @param Item $item
     * @return bool
     */
    public function sell(User $user, Item $item)
    {
        return $this->manage($user, $item) && $this->is_closed($item);
    }

    /**
     * @param User $user
     * @param Item $item
     * @return bool
     */
    public function manage(User $user, Item $item)
    {
        return $this->is_owner($user, $item);
    }

    /**
     * @param Item $item
     * @return bool
     */
    protected function is_closed(Item $item)
    {
        return $item->status === Item::STATUS_CLOSE;
    }

    /**
     * @param Item $item
     * @return bool
     */
    protected function is_open(Item $item)
    {
        return $item->status === Item::STATUS_OPEN;
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function has_owner(Item $item)
    {
        return filled($item->user_id);
    }

    /**
     * @param User $user
     * @param Item $item
     * @return bool
     */
    public function is_owner(User $user, Item $item)
    {
        return $item->user_id === $user->id;
    }
}
