<?php

namespace Modules\Main\Policies;

use App\Policies\Policyable;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Main\Entities\BoxItem;
use Modules\Users\Entities\User;

class BoxItemPolicy
{
    use HandlesAuthorization, Policyable;
}
