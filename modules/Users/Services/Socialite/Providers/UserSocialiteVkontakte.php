<?php

namespace Modules\Users\Services\Socialite\Providers;

use Modules\Users\Services\Socialite\UserSocialiteable;

class UserSocialiteVkontakte extends UserSocialiteable
{
    const KEY       = 1;
    const PATH      = 'vkontakte';
    const DRIVER    = 'vkontakte';

    /**
     * @return int
     */
    public function key(): int
    {
        return self::KEY;
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return self::PATH;
    }

    /**
     * @return string
     */
    public function driver() : string
    {
        return self::DRIVER;
    }
}
