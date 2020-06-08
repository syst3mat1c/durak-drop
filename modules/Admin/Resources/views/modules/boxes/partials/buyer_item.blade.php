<?php /** @var \Modules\Main\Entities\BuyerItem $buyerItem */ ?>

<li style="border: 4px double black; cursor: pointer;" class="add-box-item" data-id="{{ $buyerItem->id }}" data-url="{{ route('admin.boxes.items.add', $box) }}">
    <img src="{{ $buyerItem->img }}" alt="" style="    width: 20%;">
    <a class="users-list-name" href="{{ $buyerItem->product_url }}" target="_blank">{{ $buyerItem->name }}</a>
</li>
