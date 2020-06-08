<ul class="profile-nav">
    <li class="active">Инвентарь</li>
    <li>История заявок на вывод</li>
</ul>
<div class="tab active">
    <div class="c-title">
        <div class="c-title-text">
            <b>Ваши предметы:</b>
            <span>Весь список ваших предметов</span>
        </div>
        <div class="c-title-line"></div>
        <div class="clear"></div>
    </div>
    <div class="viewn-loop my" id="inventoryContent">
        @include('users::profiles.partials.show_footer.manager_items')
    </div>
    <div class="clear"></div>
</div>
<div class="tab">
    <div class="c-title">
        <div class="c-title-text">
            <b>Заявки на вывод:</b>
            <span>Весь список ваших  заявок</span>
        </div>
        <div class="c-title-line"></div>
        <div class="clear"></div>
    </div>
    <div class="table table-6">
        <div class="table-i">
            <div>№ заявки:</div>
            <div>Тип:</div>
            <div>Сумма:</div>
            <div>Дата:</div>
            <div>Статус: </div>
            <div>Обратная связь:</div>
        </div>
        @foreach ($user->withdraws()->latest()->get() as $withdraw)
            <div class="table-i">
                <div>{{ $withdraw->id }}</div>
                <div>{{ $withdraw->type_human }}</div>
                <div>{{ $withdraw->amount_human }}</div>
                <div>{{ $withdraw->created_at->format('d.m.Y H:i') }}</div>
                <div><span class="{{ $withdraw->status_class }}">{{ $withdraw->status_human }}</span></div>
                <div>
                    @if ($withdraw->status === \Modules\Main\Entities\Withdraw::STATUS_RESOLVE)
                        <a class="status-0">Связаться через VK</a>
                    @else
                        <a href="{{ _s('settings-default', 'vkontakte-link') }}" class="status-1">Связаться через VK</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
