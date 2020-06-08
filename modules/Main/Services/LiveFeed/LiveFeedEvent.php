<?php

namespace Modules\Main\Services\LiveFeed;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class LiveFeedEvent implements ShouldBroadcast
{
    use SerializesModels;

    public $serializedItem;
    public $template;

    public function __construct(array $serializedItem)
    {
        $this->serializedItem = $serializedItem;
        $this->template = view('front.widgets.live.live_item', ['item' => $serializedItem])->render();
    }

    /**
     * @return string
     */
    public function broadcastOn()
    {
        return 'LiveFeed';
    }
}
