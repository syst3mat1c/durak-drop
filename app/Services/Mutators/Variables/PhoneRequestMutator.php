<?php

namespace App\Services\Mutators\Variables;

use App\Services\Mutators\Mutator;
use Validator;

class PhoneRequestMutator extends Mutator
{
    /**
     * @return void
     */
    public function mutate()
    {
        $this->formRequest->merge(['phone' => ($phone = crop_phone($this->request->get('phone')))]);
        $this->request->set('phone', $phone);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function validate(array $data) : bool
    {
        return Validator::make($data, [
            'phone' => 'required|string'
        ])->passes();
    }
}
