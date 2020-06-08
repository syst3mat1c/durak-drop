<a href="{{ route('admin.referrals', ['order_by' => $column]) }}"
   class="btn btn-default{{ ((!request()->has('order_by') && isset($default)) || request()->get('order_by') == $column) ? ' active' : '' }}"
   style="width:50%;">
    {{ $name }}
</a>
