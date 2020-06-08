<?php

namespace Modules\Users\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Http\Request;
use Modules\Main\Services\LiveFeed\LiveFeedService;

class LiveWidget extends AbstractWidget
{
    /**
     * @param LiveFeedService $liveService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function run(LiveFeedService $liveService)
    {
        return view('front.widgets.live.live_block', compact('liveService'));
    }
}
