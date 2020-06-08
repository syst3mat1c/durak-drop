<?php

namespace App\Services\Billing\Events;

use Illuminate\Queue\SerializesModels;

class PaymentSuccessEvent
{
    use SerializesModels;

    /** @var int */
    protected $accountId;

    /** @var float */
    protected $amount;

    public function __construct(int $accountId, float $amount)
    {
        $this->accountId    = $accountId;
        $this->amount       = $amount;
    }

    /**
     * @return float
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function accountId()
    {
        return $this->accountId;
    }
}
