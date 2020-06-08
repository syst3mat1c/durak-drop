@if (isset($attributes) && is_array($attributes))
    @foreach ($attributes as $key => $value)
        @if (!is_string($value))
            @break
        @endif
        {{ $key }}="{{ $value }}"
    @endforeach
@endif
