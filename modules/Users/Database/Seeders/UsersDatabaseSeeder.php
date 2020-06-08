<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersDatabaseSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        Model::unguard();

         $this->call(UsersTableSeeder::class);
    }
}
