<?php

namespace Modules\Users\Http\Requests\Profile;

use App\Services\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends ApiRequest {
    /**
     * @return array
     */
    public function rules(): array {
        return [
            'amount' => 'required|integer|min:100000|max:100000000',
        ];
    }
}
