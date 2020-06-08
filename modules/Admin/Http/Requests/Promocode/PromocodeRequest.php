<?php

namespace Modules\Admin\Http\Requests\Promocode;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Main\Entities\Promocode;

class PromocodeRequest extends FormRequest
{
    public $validated = ['code', 'percent', 'attempts', 'min_amount', 'type'];

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'code'          => 'required|string|unique:promocodes',
            'percent'       => 'required|integer|between:1,100',
            'attempts'      => 'nullable|integer|min:1|max:255',
            'min_amount'    => 'required|numeric|between:0,1000',
            'type'          => 'required|integer|in:' . implode(',', [Promocode::TYPE_PUBLIC, Promocode::TYPE_PRIVATE])
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
