<?php

namespace App\Listeners;

use App\Services\Billing\Events\PaymentSuccessEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Main\Entities\Deposit;
use Modules\Main\Repositories\DepositRepository;
use Modules\Main\Repositories\OrderRepository;
use Modules\Users\Repositories\UserRepository;

class BillingUserListener {
    /** @var UserRepository */
    protected $userRepo;

    /** @var DepositRepository */
    protected $depositRepo;

    /** @var OrderRepository */
    protected $orderRepository;

    /**
     * BillingUserListener constructor.
     * @param UserRepository $userRepository
     * @param DepositRepository $depositRepository
     */
    public function __construct(UserRepository $userRepository, DepositRepository $depositRepository) {
        $this->userRepo        = $userRepository;
        $this->depositRepo     = $depositRepository;
        $this->orderRepository = new OrderRepository();
    }

    /**
     * @param PaymentSuccessEvent $event
     */
    public function handle(PaymentSuccessEvent $event) {
        $deposit = $this->depositRepo->find($event->accountId());
        $reward  = $deposit->amount_smart;

        if ($deposit && $deposit->status === Deposit::STATUS_PENDING) {
            $this->userRepo->addMoney($deposit->user, (float)$reward);
            $this->userRepo->rewardReferral($deposit->user, $reward);
            $this->depositRepo->update($deposit, ['status' => Deposit::STATUS_RESOLVED]);
            $this->orderRepository->addOrder($deposit);
        }
    }
}
