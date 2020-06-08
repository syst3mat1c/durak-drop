<?php

namespace Modules\Users\Services\Socialite;

use Illuminate\Support\Collection;
use Modules\Users\Services\Socialite\Providers\UserSocialiteVkontakte;

class UserSocialiteService
{
    private $map = [
        UserSocialiteVkontakte::class
    ];

    /** @var Collection */
    private $singletons;

    /**
     * UserSocialiteService constructor.
     */
    public function __construct()
    {
        $this->singletons = collect();

        $this->createSingletons();
    }

    /**
     * @return void
     */
    private function createSingletons()
    {
        collect($this->map)->each(function($path) {
            $this->singletons->push(new $path);
        });
    }

    /**
     * @param int $key
     * @return UserSocialiteable|null
     */
    public function findByKey(int $key)
    {
        return $this->singletons->filter(function(UserSocialiteable $singleton) use ($key) {
            return $singleton->key() === $key;
        })->first();
    }

    /**
     * @param string $path
     * @return UserSocialiteable|null
     */
    public function findByPath(string $path)
    {
        return $this->singletons->filter(function(UserSocialiteable $singleton) use ($path) {
            return $singleton->path() === $path;
        })->first();
    }

    /**
     * @param string $class
     * @return UserSocialiteable|null
     */
    public function getByClass(string $class)
    {
        return $this->singletons->filter(function(UserSocialiteable $singleton) use ($class) {
            return is_a($singleton, $class);
        })->first();
    }

    /**
     * @param string $class
     * @return UserSocialiteable|null
     */
    public function keyByClass(string $class)
    {
        return optional($this->getByClass($class))->key();
    }

    /**
     * @param int $key
     * @return bool
     */
    public function existsKey(int $key)
    {
        return is_a($this->findByKey($key), UserSocialiteable::class);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function existsPath(string $path)
    {
        return is_a($this->findByPath($path), UserSocialiteable::class);
    }
}
