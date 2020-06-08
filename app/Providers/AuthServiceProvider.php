<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Entities\Item;
use Modules\Main\Policies\BoxItemPolicy;
use Modules\Main\Policies\ItemPolicy;
use Modules\Users\Entities\User;
use Modules\Users\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $policies = [
        User::class     => UserPolicy::class,
        BoxItem::class  => BoxItemPolicy::class,
        Item::class     => ItemPolicy::class,
    ];

    /**
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
