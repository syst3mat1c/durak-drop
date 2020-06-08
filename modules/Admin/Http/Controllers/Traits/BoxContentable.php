<?php

namespace Modules\Admin\Http\Controllers\Traits;

use Modules\Main\Entities\Box;
use Modules\Main\Repositories\BoxRepository;
use Modules\Main\Repositories\BuyerItemRepository;

trait BoxContentable
{
    /**
     * @param Box $box
     * @return string
     * @throws \Throwable
     */
    protected function getItemsContent(Box $box)
    {
        $mainItems = app(BoxRepository::class)->boxItems($box);
        $customItems = app(BoxRepository::class)->boxItemsCustom($box);
        $buyerItems = app(BuyerItemRepository::class)->all();

        return view('admin::modules.boxes.partials.items',
            compact('box', 'mainItems', 'customItems', 'buyerItems'))->render();
    }
}
