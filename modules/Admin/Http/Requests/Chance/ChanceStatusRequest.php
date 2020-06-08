<?php

namespace Modules\Admin\Http\Requests\Chance;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Users\Entities\Chance;

class ChanceStatusRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|integer|in:' . implode(',', [Chance::STATUS_ENABLED, Chance::STATUS_DISABLED])
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
