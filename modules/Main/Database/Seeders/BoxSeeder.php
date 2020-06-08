<?php

namespace Modules\Main\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Modules\Main\Entities\Box;
use Modules\Main\Entities\BoxItem;
use Modules\Main\Entities\Category;

class BoxSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->createCoins();
        $this->createCredits();
    }

    protected function createCoins()
    {
        /** @var Category $coinsCategory */
        $coinsCategory = factory(Category::class)->create([
            'title' => 'Монеты'
        ]);

        /** @var Collection $coinsBoxes */
        $coinsBoxes = factory(Box::class, 3)->state('coins')->create([
            'category_id'   => $coinsCategory->id,
        ]);

        $coinsBoxes->each(function(Box $box) {
            factory(BoxItem::class, 20)->create([
                'box_id'    => $box->id,
                'type'      => BoxItem::TYPE_COINS
            ]);
        });
    }

    protected function createCredits()
    {
        /** @var Category $creditsCategory */
        $creditsCategory = factory(Category::class)->create([
            'title' => 'Кредиты'
        ]);

        /** @var Collection $creditsBoxes */
        $creditsBoxes = factory(Box::class, 3)->state('credits')->create([
            'category_id'   => $creditsCategory->id
        ]);

        $creditsBoxes->each(function(Box $box) {
            factory(BoxItem::class, 20)->create([
                'box_id'    => $box->id,
                'type'      => BoxItem::TYPE_CREDITS
            ]);
        });
    }
}
