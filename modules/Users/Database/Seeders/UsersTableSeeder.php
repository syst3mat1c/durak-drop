<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\Repositories\UserRepository;
use Modules\Users\Services\Socialite\Providers\UserSocialiteVkontakte;

class UsersTableSeeder extends Seeder
{
    protected $seeds = [
        [
            'provider_class'    => UserSocialiteVkontakte::class,
            'provider_id'       => 218103398,
            'name'              => 'Sergo Sergo',
            'money'             => 0.00,
            'is_admin'          => true,
            'avatar'            => '/images/no-image.png',
        ],

        [
            'provider_class'    => UserSocialiteVkontakte::class,
            'provider_id'       => 378301760,
            'name'              => 'Dmytro Karpachov',
            'money'             => 0.00,
            'is_admin'          => true,
            'avatar'            => '/images/no-image.png',
        ]
    ];

    protected $validated = ['provider_id', 'name', 'money', 'is_admin', 'avatar'];

    /**
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $userRepo = app(UserRepository::class);

        foreach ($this->seeds as $seed) {
            $userRepo->store($this->format($seed));
        }
    }

    /**
     * @param array $seed
     * @return array
     */
    protected function format(array $seed)
    {
        return collect($seed)->only($this->validated)
            ->put('provider_type', app('user-socialite')->getByClass($seed['provider_class'])->key())
            ->toArray();
    }
}
