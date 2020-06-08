<?php

namespace Modules\Main\Observers;

use Modules\Main\Entities\Item;
use Modules\Main\Services\LiveFeed\LiveFeedService;
use Modules\Users\Entities\User;
use Modules\Users\Repositories\UserRepository;

class ItemObserver
{
    /**
     * @param Item $item
     */
    public function saving(Item $item)
    {
        if ($item->isDirty('status')) {
            if ($item->isDirty('user_id') && is_null($item->user_id) && $item->getOriginal('user_id') &&
                $item->status === Item::STATUS_CLOSE) {
                $user = app(UserRepository::class)->find($item->getOriginal('user_id'));
                app(UserRepository::class)->addMoney($user, $item->price);
            }

            if ($item->isDirty('user_id') && filled($item->user_id) && is_null($item->getOriginal('user_id')) &&
                $item->status === Item::STATUS_CLOSE) {
                app(LiveFeedService::class)->add($item);
            }
        }
    }
}
