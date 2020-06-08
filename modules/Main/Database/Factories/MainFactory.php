<?php

use Faker\Generator as Faker;
use Modules\Main\Entities\{
    Category, Box, BoxItem, Item, Promocode, BuyerItem
};

$factory->define(Category::class, function(Faker $faker) {
    return [
        'title' => $faker->sentence(3)
    ];
});

$factory->define(Box::class, function(Faker $faker) {
    return [
        'category_id'       => Category::pluck('id')->random(),
        'name'              => ($name = collect(['Новичок', 'Красавчик', 'Молодняк', 'Королевский', 'Императорский'])->random()),
        'description'       => 'Выигрыш до ' . (collect([1000, 5000, 10000, 20000])->random()),

        'slug'              => str_slug($name . '-' . str_random(3)),

        'price'             => ($currentPrice = collect([25,50,60,75,90])->random()),

        'counter'           => 0,
        'counter_two'       => 0,
        'max_counter_two'   => 0,
        'percents'          => collect([10,20,30])->random(),
        'two_percents'      => 4,
        'three_percents'    => 5,
        'discount'          => 0,

        'status'            => Box::STATUS_ENABLED,
        'type'              => Box::TYPE_COUNTERS,

        'icon'              => Box::ICON_CREDITS,
        'rarity'            => Box::RARITY_GREEN,

        'old_price'         => $currentPrice,
        'order'             => Box::max('order') + 1,
    ];
});

$factory->state(Box::class, 'credits', function(Faker $faker) {
    return [
        'icon'      => Box::ICON_CREDITS,
        'rarity'    => Box::RARITY_GREEN,
    ];
});

$factory->state(Box::class, 'coins', function(Faker $faker) {
    return [
        'icon'      => Box::ICON_COINS,
        'rarity'    => collect(Box::RARITIES)->random(),
    ];
});

//'box_id', 'name', 'price', 'img', 'rarity', 'type', 'max_price', 'classid', 'rarity_color'
$factory->define(BoxItem::class, function(Faker $faker) {
    return [
        'box_id'            => Box::pluck('id')->random(),
        'price'             => collect([100,200,300])->random(),
        'amount'            => collect([100, 1000, 5000, 10000, 50000, 100000, 500000, 45000, 20000, 15000, 500])->random(),
        'type'              => collect(BoxItem::TYPES)->random(),
        'rarity'            => collect(BoxItem::RARITIES)->random(),
        'wealth'            => collect(BoxItem::WEALTHS)->random(),
        'is_gaming'         => true,
    ];
});

$factory->define(Item::class, function (Faker $faker) {
    return [
        'user_id'       => \Modules\Users\Entities\User::inRandomOrder()->first()->id,
        'box_item_id'   => \Modules\Main\Entities\BoxItem::inRandomOrder()->first()->id,
        'status'        => collect(Item::STATUSES)->random(),
    ];
});

$factory->define(Promocode::class, function(Faker $faker) {
    return [
        'code'          => $faker->domainWord . rand(1000,9999),
        'percent'       => rand(5,15),
        'attempts'      => collect([null, null, null, rand(10,50)])->random(),
        'min_amount'    => collect([100,200,300])->random(),
        'type'          => collect([Promocode::TYPE_PUBLIC, Promocode::TYPE_PRIVATE])->random()
    ];
});

