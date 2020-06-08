<?php

namespace Modules\Users\Observers;

use Modules\Users\Entities\User;
use Hash;

class UserObserver
{
    /**
     * @param User $user
     */
    public function creating(User $user)
    {
        $user->referral_key = str_random(rand(6,8));
    }

    /**
     * @param User $user
     * @return void
     */
    public function saving(User $user)
    {
        if ($user->isDirty('password')) {
            $user->password = Hash::make($user->password);
        }
    }
}
