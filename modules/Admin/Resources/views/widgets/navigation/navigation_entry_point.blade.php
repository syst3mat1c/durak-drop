@foreach ($elements as $element)
    @include('admin::widgets.navigation.navigation_handler', compact('element'))
@endforeach
