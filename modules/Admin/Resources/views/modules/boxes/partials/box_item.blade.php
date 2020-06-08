<?php /** @var \Modules\Main\Entities\BoxItem $boxItem */ ?>
<li style="border: 4px double black; cursor: pointer;">
    <img src="{{ $boxItem->img }}" alt="{{ $boxItem->name }}" style="    width: 20%;">
    <a class="users-list-name" href="{{ $boxItem->product_url }}" target="_blank">{{ $boxItem->name }}</a>
    <span class="users-list-date">На боте: {{ _count($boxItem->items_count) }}<br></span>
    <span class="users-list-date">{{ $boxItem->price_human }}</span>

    {{ Form::select('type', app(\Modules\Main\Repositories\BoxItemRepository::class)->resourceTypes(),
        $boxItem->type, [
            'class' => 'form-control update-box-item',
            'data-url'   => route('admin.box_items.type.update', $boxItem)
        ]) }}

    {{ Form::select('rarity', app(\Modules\Main\Repositories\ItemRepository::class)->resourceRarities(),
        $boxItem->rarity, [
            'class' => 'form-control update-box-item',
            'data-url'   => route('admin.box_items.rarity.update', $boxItem)
        ]) }}
</li>
