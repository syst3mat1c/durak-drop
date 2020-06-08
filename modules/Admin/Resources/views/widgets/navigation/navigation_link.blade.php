<li class="{{ \Route::currentRouteNamed($element['route']) && (!isset($element['show_active']) || $element['show_active'])
                ? 'active' : '' }}">
    <a href="{{ route($element['route']) }}" class="{{ $element['class'] ?? '' }}">
        <i class="{{ $element['icon'] }}"></i>
        <span>{{ $element['name'] }}</span>
    </a>
</li>
