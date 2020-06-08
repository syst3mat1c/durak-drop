<?php

namespace Modules\Main\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Main\Entities\Item;
use Modules\Main\Repositories\ItemRepository;
use Modules\Main\Services\LiveFeed\LiveFeedService;

class ItemLiveSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $itemRepo = app(ItemRepository::class);
        $liveService = app(LiveFeedService::class);

        $liveService->flush();

        $itemRepo->getForLive()->each(function(Item $item) use ($liveService) {
            $liveService->add($item);
        });
    }
}
