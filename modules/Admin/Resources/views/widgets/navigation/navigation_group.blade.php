<li class="header">{{ $element['name'] }}</li>

@if (isset($element['children']) && is_array($element['children']))
    @include('admin::widgets.navigation.navigation_entry_point', ['elements' => $element['children']])
@endif
