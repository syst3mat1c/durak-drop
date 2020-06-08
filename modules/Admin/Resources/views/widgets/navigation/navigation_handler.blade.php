@if (isset($element['sense']) && is_string($element['sense']) && isset($element['name']) && is_string($element['name']))
    @switch ($element['sense'])
        @case('group')
            @include('admin::widgets.navigation.navigation_group', compact('element'))
            @break

        @case('link')
            @if (isset($element['route']) && is_string($element['route']) && isset($element['icon']) && is_string($element['icon']))
                @include('admin::widgets.navigation.navigation_link', compact('element'))
            @endif
            @break
    @endswitch
@endif
