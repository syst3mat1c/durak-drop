<?php

namespace Modules\Admin\Http\Requests\Chance;

use Illuminate\Foundation\Http\FormRequest;

class ChanceRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'provider_id'   => 'required|integer|exists:users,provider_id',
            'json'          => 'required|string|max:255',
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
