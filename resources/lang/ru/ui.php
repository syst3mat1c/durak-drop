<?php

use Modules\Main\Entities\{
    Withdraw, Box, BoxItem
};

return [
    'choice'  => [
        'times' => 'раз|раза|раз',
    ],
    'plurals' => [
        'coins'   => 'монета|монеты|монет',
        'credits' => 'кредит|кредитов|кредитов',
        'bonuses' => 'бонус|бонуса|бонусов',
    ],
    'models'  => [
        'boxes'     => [
            'rarities' => [
                Box::RARITY_BLUE   => 'Синий цвет',
                Box::RARITY_PURPLE => 'Фиолетовый цвет',
                Box::RARITY_ORANGE => 'Оранжевый цвет',
                Box::RARITY_GREEN  => 'Зеленый цвет',
            ],
            'icons'    => [
                Box::ICON_COINS   => 'Монеты',
                Box::ICON_CREDITS => 'Кредиты',
                Box::ICON_BONUS   => 'Бонусный',
            ],
        ],
        'box_items' => [
            'rarities' => [
                BoxItem::RARITY_PURPLE => 'Фиолетовый цвет',
                BoxItem::RARITY_BLUE   => 'Синий цвет',
                BoxItem::RARITY_SIREN  => 'Сиреневый цвет',
                BoxItem::RARITY_AQUA   => 'Голубой цвет',
                BoxItem::RARITY_ORANGE => 'Оранжевый цвет',
                BoxItem::RARITY_GREEN  => 'Зелёный цвет',
            ],
            'types'    => [
                BoxItem::TYPE_COINS   => 'Монеты',
                BoxItem::TYPE_CREDITS => 'Кредиты',
            ],
            'wealths'  => [
                BoxItem::WEALTH_COMMON    => 'Дешёвая',
                BoxItem::WEALTH_UNCOMMON  => 'Дешёвая+',
                BoxItem::WEALTH_RARE      => 'Средняя',
                BoxItem::WEALTH_MYTHICAL  => 'Дорогая',
                BoxItem::WEALTH_LEGENDARY => 'Бесценная',
                BoxItem::WEALTH_IMMORTAL  => 'Бесценная+',
            ],
        ],
        'withdraws' => [
            'statuses'      => [
                Withdraw::STATUS_PENDING => 'Ожидание',
                Withdraw::STATUS_RESOLVE => 'Выведено',
                Withdraw::STATUS_REJECT  => 'Ошибка',
            ],
            'status_colors' => [
                Withdraw::STATUS_PENDING => 'status-pending',
                Withdraw::STATUS_RESOLVE => 'status-resolve',
                Withdraw::STATUS_REJECT  => 'status-reject',
            ],
            'types'         => [
                Withdraw::TYPE_COINS   => 'Монеты',
                Withdraw::TYPE_CREDITS => 'Кредиты',
            ],
        ],
    ],
];
