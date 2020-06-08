<?php /** @var \Modules\Main\Entities\BoxItem $boxItem */ ?>
@foreach ($rouletteItems as $boxItem)
    <li class="color-{{ $boxItem->rarity }} {{ $loop->iteration === 100 ? 'lucky' : 'unlucky' }} {{ $loop->iteration >= 95 && $loop->iteration < 105 ? 'performance' : 'backstage'  }}">
        <span class="rulet-{{ $boxItem->type_human }}" data-sense="type"></span>
        <span class="rulet-col">{{ $boxItem->amount_human }}</span>
        <span class="rulet-name">{{ $boxItem->plural }}</span>
    </li>
@endforeach
