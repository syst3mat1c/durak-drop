<?php

namespace App\Policies;

use Modules\Users\Entities\User;

trait Policyable
{
    /**
     * @param User $user
     * @return bool
     */
    public function is_root(User $user)
    {
        return $user->is_admin;
    }
}
