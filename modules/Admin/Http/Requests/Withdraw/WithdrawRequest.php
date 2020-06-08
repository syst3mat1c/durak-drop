<?php

namespace Modules\Admin\Http\Requests\Withdraw;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Main\Entities\Withdraw;

class WithdrawRequest extends FormRequest
{
    public $validated = ['status'];

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|integer|in:' . implode(',', Withdraw::STATUSES)
        ];
    }

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
