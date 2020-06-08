<?php

namespace Modules\Users\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'login'     => 'required|string|alpha_dash',
            'password'  => 'required|string',
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
