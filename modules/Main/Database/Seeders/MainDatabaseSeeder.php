<?php

namespace Modules\Main\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Entities\BuyerItem;
use Modules\Main\Entities\Category;
use Modules\Main\Entities\Box;
use Modules\Main\Entities\Item;
use Modules\Main\Entities\Promocode;

class MainDatabaseSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(BoxSeeder::class);

        factory(Item::class, 50)->create();

        /** @var Collection $promocodes */
        $promocodes = factory(Promocode::class, 10)->create();

//        $promocodeIds = $promocodes->pluck('id');

        $this->call(ItemLiveSeeder::class);
        $this->call(QuestionsTableSeeder::class);
    }
}
