<?php

use Modules\Main\Entities\Item;
use Modules\Main\Entities\BoxItem;

return [
    'rarities' => [
        Item::RARITY_TYPE_COMMON => 'Дешёвая',
        Item::RARITY_TYPE_UNCOMMON => 'Дешёвая+',
        Item::RARITY_TYPE_RARE => 'Средняя',
        Item::RARITY_TYPE_MYTHICAL => 'Дорогая',
        Item::RARITY_TYPE_LEGENDARY => 'Бесценная',
        Item::RARITY_TYPE_IMMORTAL => 'Бесценная+',
    ],
    'statuses' => [
        Item::STATUS_CLOSE => 'В продаже',
        Item::STATUS_OPEN => 'Продана',
    ],
    'types' => [
        BoxItem::TYPE_WILL_NOT_DROP => 'Не выпадает',
        BoxItem::TYPE_WILL_DROP     => 'Выпадает'
    ]
];
