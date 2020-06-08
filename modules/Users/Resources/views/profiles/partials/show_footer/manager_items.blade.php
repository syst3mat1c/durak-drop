@foreach ($user->items()->latest()->get() as $item)
    <div class="viewn color-{{ $item->rarity }}">
        <div class="viewn-{{ $item->type_human }}"></div>
        <div class="viewn-col">{{ $item->amount_human }}</div>
        <div class="viewn-name">{{ $item->plural }}</div>

        @can('manage_himself', $user)
            @can('sell', $item)
                <a href="{{ route('items.sell', compact('item')) }}" data-action="sell" class="viewn-pole1">
                    {{ $item->price_human }}
                </a>
            @else
                @if ($item->status === \Modules\Main\Entities\Item::STATUS_OPEN)
                    <div class="viewn-pole2">{{ $item->price_human }}</div>
                @elseif ($item->status === \Modules\Main\Entities\Item::STATUS_WITHDRAW)
                    <div class="viewn-pole3">Выведен</div>
                @endif
            @endcan
        @endcan
    </div>
@endforeach
