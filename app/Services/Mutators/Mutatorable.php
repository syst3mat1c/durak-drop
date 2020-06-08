<?php

namespace App\Services\Mutators;

use Symfony\Component\HttpFoundation\ParameterBag;

trait Mutatorable
{
    /** @var ParameterBag $request */

    /**
     * @return void
     */
    public function mutate()
    {
        if (isset($this->mutators) && is_array($this->mutators) && is_a($this->request, ParameterBag::class)) {
            collect($this->mutators)->each(function($mutator_class) {
                if (class_exists($mutator_class) &&
                    is_a(($class = new $mutator_class($this->request, $this)), Mutator::class)) {
                    /** @var Mutator $class */
                    if ($class->validate($this->request->all())) {
                        $class->mutate();
                    }
                }
            });
        }
    }
}
