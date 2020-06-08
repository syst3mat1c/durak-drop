{{ Form::open(['method' => 'DELETE', 'url' => $url, 'style' => 'display:inline;', 'class' => 'form-submit']) }}
    @include('admin::ui.buttons.delete_prototype')
{{ Form::close() }}
