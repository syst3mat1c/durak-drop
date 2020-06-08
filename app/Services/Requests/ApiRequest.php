<?php

namespace App\Services\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class ApiRequest extends FormRequest
{
    /** @var bool */
    protected $onlyFirst = false;

    /** @var array */
    private $errorCodes = [];

    /**
     * Custom validation: it'll always return application/json
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($this->schema($validator), 422));
    }

    /**
     * @param Validator $validator
     * @return array
     */
    final private function schema(Validator $validator)
    {
        return $this->onlyFirst
            ? ['error' => $this->getFirstError($validator), 'codes' => $this->errorCodes]
            : [
                'error' => $this->getFirstError($validator),
                'error_fields' => $validator->errors(),
                'errors_flat' => $this->getFlatErrors($validator),
                'codes' => $this->errorCodes
            ];
    }

    /**
     * @param int $code
     * @return array
     */
    final protected function addErrorCode(int $code)
    {
        $this->errorCodes[] = $code;
        return $this->errorCodes;
    }

    /**
     * @param Validator $validator
     * @return mixed
     */
    final private function getFirstError(Validator $validator)
    {
        return collect($validator->errors())->first()[0];
    }

    /**
     * @param Validator $validator
     * @return array
     */
    final private function getFlatErrors(Validator $validator)
    {
        return collect($validator->errors())->flatten()->toArray();
    }

    /**
     * @return array
     */
    abstract public function rules() : array;

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
