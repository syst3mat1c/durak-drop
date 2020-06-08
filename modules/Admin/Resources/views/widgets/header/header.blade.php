<?php /** @var \Modules\Users\Entities\User $user */ ?>

<div class="user-panel">
    <div class="pull-left image">
        <img src="{{ $user->avatar_asset }}" class="img-circle" alt="User Image">
    </div>
    <div class="pull-left info">
        <p>{{ $user->name }}</p>
        <!-- Status -->
        <a href="{{ route('admin.index') }}"><i class="fa fa-circle text-success"></i> Online</a>
    </div>
</div>
