<?php

namespace Modules\Users\Http\Requests\Auth;

use App\Services\Mutators\Mutatorable;
use App\Services\Mutators\Variables\PhoneRequestMutator;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\WithoutSpacesRule;

class UserRegisterRequest extends FormRequest
{
    use Mutatorable;

    /** @var array  */
    public $validated = ['name', 'login', 'phone', 'password', 'country'];

    /** @var array  */
    protected $mutators = [PhoneRequestMutator::class];

    /**
     * @return void
     */
    public function prepareForValidation()
    {
        $this->mutate();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => 'required|string|max:32',
            'login'     => ['required', 'string', 'regex:/(^([a-zA-Z_]+)(\d+)?$)/u', 'max:16', 'unique:users'],
            'email'     => ['required', 'string', 'email', 'unique:users'],
            'password'  => 'required|string|min:6|confirmed',
            'terms'     => 'required|accepted|in:0,1'
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
