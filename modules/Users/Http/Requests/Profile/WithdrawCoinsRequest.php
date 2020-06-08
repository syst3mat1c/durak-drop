<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26/04/2019
 * Time: 11:40
 */

namespace Modules\Users\Http\Requests\Profile;


use App\Services\Requests\ApiRequest;

class WithdrawCoinsRequest extends ApiRequest {

    /**
     * @return array
     */
    public function rules(): array {
        return [
            'amount'   => 'required|integer|min:100|max:100000000',
        ];
    }
}