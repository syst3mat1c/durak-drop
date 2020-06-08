<?php

namespace Modules\Users\Listeners\UserRegistered;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Users\Entities\User;
use Modules\Users\Repositories\UserRepository;

class ReferralListener
{
    /** @var UserRepository */
    protected $userRepo;

    /** @var Request */
    protected $request;

    /**
     * ReferralListener constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, Request $request)
    {
        $this->userRepo = $userRepository;
        $this->request  = $request;
    }

    /**
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if (($referralId = $this->request->cookie('referral_id')) && $this->userRepo->find($referralId)) {
            /** @var User $user */
            $user = $event->user;

            $this->userRepo->update($user, ['referral_id', (int) $referralId]);
        }
    }
}
