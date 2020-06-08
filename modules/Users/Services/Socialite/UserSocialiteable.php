<?php

namespace Modules\Users\Services\Socialite;

abstract class UserSocialiteable
{
    abstract public function key() : int;
    abstract public function path() : string;
    abstract public function driver() : string;
}
