<?php

namespace App\Services\Billing\Services;

class BillingService
{
    /**
     * @return string
     */
    public function merchantId()
    {
        return _s('settings-billing', 'merchant-id');
    }

    /**
     * @return string
     */
    public function merchantSecretOne()
    {
        return _s('settings-billing', 'merchant-secret1');
    }

    /**
     * @return string
     */
    public function merchantSecretTwo()
    {
        return _s('settings-billing', 'merchant-secret2');
    }

    /**
     * @param string $amount
     * @param string $orderId
     * @return string
     */
    public function hashForm(string $amount, string $orderId)
    {
        return md5($this->merchantId().':'.$amount.':'.$this->merchantSecretOne().':'.$orderId);
    }

    /**
     * @param string $amount
     * @param string $orderId
     * @return string
     */
    public function hashCallback(string $amount, string $orderId)
    {
        return md5($this->merchantId().':'.$amount.':'.$this->merchantSecretTwo().':'.$orderId);
    }
}
