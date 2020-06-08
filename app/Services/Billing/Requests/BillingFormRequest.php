<?php

namespace App\Services\Billing\Requests;

use App\Services\Requests\ApiRequest;

class BillingFormRequest extends ApiRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'amount'        => 'required|numeric|min:10|max:100000',
            'promo'         => 'nullable|string|max:64',
        ];
    }
}
