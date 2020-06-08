@if (isset($route) && is_string($route) && isset($label) && is_string($label))
    <li class="{{ request()->route()->getName() === $route ? 'active ' : '' }}{{ $class ?? '' }}">
        <a href="{{ route($route) }}">{{ $label }}</a>
    </li>
@endif
