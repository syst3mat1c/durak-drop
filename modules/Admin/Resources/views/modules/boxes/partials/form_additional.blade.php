<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('percents', 'Процент хороших призов') }}
            {{ Form::text('percents', null, [
                'class' => 'form-control',
                'placeholder' => 'Процент хороших призов',
                'id' => 'percents'
            ]) }}
            @include('admin::ui.validation', ['el' => 'percents'])
        </div>
        <div class="form-group">
            {{ Form::label('two_percents', 'Процент средних призов') }}
            {{ Form::text('two_percents', null, [
                'class' => 'form-control',
                'placeholder' => 'Процент средних призов',
                'id' => 'two_percents'
            ]) }}
            @include('admin::ui.validation', ['el' => 'two_percents'])
        </div>
        <div class="form-group">
            {{ Form::label('three_percents', 'Процент дешёвых+ призов') }}
            {{ Form::text('three_percents', null, [
                'class' => 'form-control',
                'placeholder' => 'Процент дешёвых+ призов',
                'id' => 'three_percents'
            ]) }}
            @include('admin::ui.validation', ['el' => 'three_percents'])
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('counter', 'Основной счётчик') }}
            {{ Form::text('counter', null, [
                'class' => 'form-control',
                'placeholder' => 'Текущее значение основного счётчика',
                'id' => 'counter'
            ]) }}
            @include('admin::ui.validation', ['el' => 'counter'])
        </div>
        <div class="form-group">
            {{ Form::label('counter_two', 'Дополнительный счётчик') }}
            {{ Form::text('counter_two', null, [
                'class' => 'form-control',
                'placeholder' => 'Текущее значение дополнительного счётчика',
                'id' => 'counter_two'
            ]) }}
            @include('admin::ui.validation', ['el' => 'counter_two'])
        </div>
        <div class="form-group">
            {{ Form::label('max_counter_two', 'Максимум дополнительного счётчика') }}
            {{ Form::text('max_counter_two', null, [
                'class' => 'form-control',
                'placeholder' => 'Максимальный минус дополнительного счетчика',
                'id' => 'max_counter_two'
            ]) }}
            @include('admin::ui.validation', ['el' => 'max_counter_two'])
        </div>
    </div>
    <!-- /.col -->
</div>
