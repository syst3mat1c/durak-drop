<?php

namespace App\Services\Mutators;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class Mutator
{
    protected $request;
    protected $formRequest;

    public function __construct(ParameterBag $request, FormRequest $formRequest)
    {
        $this->request = $request;
        $this->formRequest = $formRequest;
    }

    abstract public function mutate();
    abstract public function validate(array $data) : bool;
}
