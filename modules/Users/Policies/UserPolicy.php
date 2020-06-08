<?php

namespace Modules\Users\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Policies\Policyable;
use Modules\Users\Entities\User;

class UserPolicy
{
    use HandlesAuthorization, Policyable;

    /**
     * @param User $spectator
     * @param User $target
     * @return bool
     */
    public function details(User $spectator, User $target)
    {
        return $this->manage($spectator, $target);
    }

    /**
     * @param User $spectator
     * @param User $target
     * @return bool
     */
    public function manage(User $spectator, User $target)
    {
        return $this->is_himself($spectator, $target) || $this->is_root($spectator);
    }

    /**
     * @param User $spectator
     * @param User $target
     * @return bool
     */
    public function manage_himself(User $spectator, User $target)
    {
        return $this->is_himself($spectator, $target);
    }

    /**
     * @param User $spectator
     * @param User $target
     * @return bool
     */
    public function super_manage(User $spectator, User $target)
    {
        return $this->is_root($spectator);
    }

    /**
     * @param User $spectator
     * @param User $target
     * @return bool
     */
    protected function is_himself(User $spectator, User $target)
    {
        return optional($spectator)->is($target);
    }
}
