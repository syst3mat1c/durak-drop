<?php

namespace Modules\Admin\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AddMoneyRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'money' => 'required|numeric|min:-1000000|max:1000000',
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
