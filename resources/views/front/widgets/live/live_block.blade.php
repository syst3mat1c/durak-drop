<?php /** @var \Modules\Main\Services\LiveFeed\LiveFeedService $liveService */ ?>

<div class="live">
    <div class="live-ul">
        @foreach ($liveService->get() as $item)
            @include('front.widgets.live.live_item', compact('item'))
        @endforeach
    </div>
</div>
