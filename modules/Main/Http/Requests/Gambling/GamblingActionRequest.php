<?php

namespace Modules\Main\Http\Requests\Gambling;

use App\Services\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class GamblingActionRequest extends ApiRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'size' => 'required|integer|min:1|max:3'
        ];
    }
}
