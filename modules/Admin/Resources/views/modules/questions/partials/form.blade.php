<div class="form-group">
    {{ Form::label('title', 'Заголовок') }}
    {{ Form::text('title', null, ['class' => 'form-control', 'id' => 'title']) }}
    @include('admin::ui.validation', ['el' => 'title'])
</div>

<div class="form-group">
    {{ Form::label('content', 'Содержимое') }}
    {{ Form::textarea('content', null, ['class' => 'form-control', 'id' => 'content']) }}
    @include('admin::ui.validation', ['el' => 'content'])
</div>


<div class="form-group">
    {{ Form::label('order', 'Расположение в списке (чем больше - тем выше)') }}
    {{ Form::number('order', null, ['class' => 'form-control', 'id' => 'order']) }}
    @include('admin::ui.validation', ['el' => 'order'])
</div>

