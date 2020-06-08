<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('order', 'Порядковый номер') }}
            {{ Form::text('order', old('order', $proposedOrder ?? $box->order), [
                'class' => 'form-control',
                'placeholder' => 'Порядковый номер',
                'name' => 'order'
            ]) }}
            @include('admin::ui.validation', ['el' => 'order'])
        </div>
        <div class="form-group">
            {{ Form::label('name', 'Название') }}
            {{ Form::text('name', null, [
                'class' => 'form-control',
                'placeholder' => 'Название кейса',
                'id' => 'name'
            ]) }}
            @include('admin::ui.validation', ['el' => 'name'])
        </div>
        <div class="form-group">
            {{ Form::label('description', 'Описание') }}
            {{ Form::text('description', null, [
                'class' => 'form-control',
                'placeholder' => 'Описание кейса',
                'id' => 'description'
            ]) }}
            @include('admin::ui.validation', ['el' => 'description'])
        </div>
        <div class="form-group">
            {{ Form::label('slug', 'URL кейса') }}
            {{ Form::text('slug', null, [
                'class' => 'form-control',
                'placeholder' => 'URL кейса',
                'id' => 'slug'
            ]) }}
            @include('admin::ui.validation', ['el' => 'slug'])
        </div>
        <div class="form-group">
            {{ Form::label('status', 'Статус') }}
            {{ Form::select('status', app(\Modules\Main\Repositories\BoxRepository::class)->resourceStatuses(), null, [
                'class' => 'form-control',
                'placeholder' => 'Выберите статус',
                'id' => 'status'
            ]) }}
            @include('admin::ui.validation', ['el' => 'status'])
        </div>

        <div class="form-group">
            {{ Form::label('icon', 'Иконка') }}
            {{ Form::select('icon', app(\Modules\Main\Repositories\BoxRepository::class)->serializeIcons(), null, [
                'class' => 'form-control',
                'placeholder' => 'Выберите иконку',
                'id' => 'icon'
            ]) }}
            @include('admin::ui.validation', ['el' => 'icon'])
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('price', 'Стоимость') }}
            {{ Form::text('price', null, [
                'class' => 'form-control',
                'placeholder' => 'Стоимость кейса',
                'id' => 'price'
            ]) }}
            @include('admin::ui.validation', ['el' => 'price'])
        </div>
        <div class="form-group">
            {{ Form::label('old_price', 'Старая стоимость') }}
            {{ Form::text('old_price', null, [
                'class' => 'form-control',
                'placeholder' => 'Старая стоимость кейса',
                'id' => 'old_price'
            ]) }}
            @include('admin::ui.validation', ['el' => 'old_price'])
        </div>
        <div class="form-group">
            {{ Form::label('discount', 'Скидка') }}
            {{ Form::text('discount', null, [
                'class' => 'form-control',
                'placeholder' => 'Скидка на кейс (проценты)',
                'id' => 'discount'
            ]) }}
            @include('admin::ui.validation', ['el' => 'discount'])
        </div>
        <div class="form-group">
            {{ Form::label('category_id', 'Категория') }}
            {{ Form::select('category_id', app(\Modules\Main\Repositories\CategoryRepository::class)->resourceSelect(), null, [
                'class' => 'form-control',
                'placeholder' => 'Выберите категорию',
                'id' => 'category_id'
            ]) }}
            @include('admin::ui.validation', ['el' => 'category_id'])
        </div>

        <div class="form-group">
            {{ Form::label('rarity', 'Цвет') }}
            {{ Form::select('rarity', app(\Modules\Main\Repositories\BoxRepository::class)->serializeRarities(), null, [
                'class' => 'form-control',
                'placeholder' => 'Выберите цвет',
                'id' => 'rarity'
            ]) }}
            @include('admin::ui.validation', ['el' => 'rarity'])
        </div>
    </div>
    <!-- /.col -->
</div>
