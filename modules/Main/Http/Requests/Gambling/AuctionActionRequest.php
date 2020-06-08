<?php

namespace Modules\Main\Http\Requests\Gambling;

use App\Services\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class AuctionActionRequest extends ApiRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
//            'bet_sum' => 'required|integer|min:1|max:3'
        ];
    }
}
