@extends('admin::layouts.master')

@section('title', 'Настройки')
@section('header', 'Настройки - ' . $group)

@section('content')
    {{ Form::open(['url' => route('admin.settings.groups.save', compact('group')), 'type' => 'POST']) }}
    @foreach ($items as $item)
        @include('admin::settings.partials._item', compact('item', 'settingsService'))
    @endforeach

    @include('admin::ui.buttons.form_edit')
    {{ Form::close() }}

    {{ Form::open(['method' => 'DELETE', 'style' => 'display:none;', 'id' => 'deleteItem']) }}
    {{ Form::close() }}
@endsection

@push('js')
    <script type="text/javascript" href=""></script>
    <script type="text/javascript">
        $(function () {
            $('[data-sense=reset]').click(function (e) {
                e.preventDefault();

                if (confirm('Вы уверены?')) {
                    $form = $('#deleteItem');
                    $form.prop('action', $(this).prop('href'));
                    $form.submit();
                }
            });
        });
    </script>
@endpush
