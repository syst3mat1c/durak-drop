<ul class="profile-nav">
</ul>
<div class="tab active">
    <div class="c-title">
        <div class="c-title-text">
            <b>Предметы {{ $user->name }}:</b>
            <span>Весь список предметов пользователя</span>
        </div>
        <div class="c-title-line"></div>
        <div class="clear"></div>
    </div>
    <div class="viewn-loop my">
        @foreach ($user->items()->latest('updated_at')->get() as $item)
            <div class="viewn color-{{ $item->rarity }}">
                <div class="viewn-{{ $item->type_human }}"></div>
                <div class="viewn-col">{{ $item->amount_human }}</div>
                <div class="viewn-name">{{ $item->plural }}</div>
            </div>
        @endforeach
    </div>
    <div class="clear"></div>
</div>
