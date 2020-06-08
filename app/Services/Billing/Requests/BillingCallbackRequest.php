<?php

namespace App\Services\Billing\Requests;

use App\Services\Requests\ApiRequest;

class BillingCallbackRequest extends ApiRequest
{
    public function rules() : array
    {
        return [
            'AMOUNT'                => 'required|string',
            'MERCHANT_ORDER_ID'     => 'required|integer',
            'SIGN'                  => 'required|string'
        ];
    }
}
