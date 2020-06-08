<div class="alert">
    <div class="row">
        @foreach ((collect(\Modules\Main\Entities\BoxItem::WEALTHS)
            ->chunk(round(count(\Modules\Main\Entities\BoxItem::WEALTHS) / 3))) as $chunk)
            <div class="col-md-4">
                <ul>
                    @foreach ($chunk as $wealth)
                        <li><strong>{{ $wealth }}</strong> - {{ trans("ui.models.box_items.wealths.{$wealth}") }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
