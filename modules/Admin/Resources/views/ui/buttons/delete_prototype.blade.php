<button type="submit" class="btn btn-danger btn-xs{{ isset($class) ? (' ' . $class) : '' }}"@isset($attributes) @include('admin::ui.attributes') @endisset>
    <i class="fa fa-trash"></i>
</button>
