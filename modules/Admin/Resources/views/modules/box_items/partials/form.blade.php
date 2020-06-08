<div class="form-group">
    {{ Form::label('price', 'Цена, руб') }}
    {{ Form::text('price', null, [
        'class' => 'form-control',
        'placeholder' => 'Цена (руб)',
        'name' => 'price'
    ]) }}
    @include('admin::ui.validation', ['el' => 'price'])
</div>

<div class="form-group">
    {{ Form::label('amount', 'Цена, валюта') }}
    {{ Form::text('amount', null, [
        'class' => 'form-control',
        'placeholder' => 'Цена (валюта)',
        'name' => 'amount'
    ]) }}
    @include('admin::ui.validation', ['el' => 'amount'])
</div>

<div class="form-group">
    {{ Form::label('type', 'Валюта') }}
    {{ Form::select('type', app(\Modules\Main\Repositories\BoxItemRepository::class)->serializeTypes(), null, [
        'class' => 'form-control',
        'placeholder' => 'Выберите валюту',
        'name' => 'type'
    ]) }}
    @include('admin::ui.validation', ['el' => 'type'])
</div>

<div class="form-group">
    {{ Form::label('rarity', 'Цвет') }}
    {{ Form::select('rarity', app(\Modules\Main\Repositories\BoxItemRepository::class)->serializeRarities(), null, [
        'class' => 'form-control',
        'placeholder' => 'Выберите цвет',
        'name' => 'rarity'
    ]) }}
    @include('admin::ui.validation', ['el' => 'rarity'])
</div>

<div class="form-group">
    {{ Form::label('wealth', 'Ценность') }}
    {{ Form::select('wealth', app(\Modules\Main\Repositories\BoxItemRepository::class)->serializeWealths(), null, [
        'class' => 'form-control',
        'placeholder' => 'Выберите ценность',
        'name' => 'wealth'
    ]) }}
    @include('admin::ui.validation', ['el' => 'wealth'])
</div>

<div class="form-group">
    {{ Form::label('is_gaming', 'Разыгрывается?') }}
    {{ Form::select('is_gaming', [1 => 'Да', 0 => 'Нет'], null, [
        'class' => 'form-control',
        'placeholder' => 'Выберите',
        'name' => 'is_gaming'
    ]) }}
    @include('admin::ui.validation', ['el' => 'is_gaming'])
</div>
