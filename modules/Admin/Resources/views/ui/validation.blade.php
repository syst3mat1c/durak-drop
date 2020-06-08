@if ($errors->has($el))
    <strong class="text-danger">{{ $errors->first($el) }}</strong>
@endif
