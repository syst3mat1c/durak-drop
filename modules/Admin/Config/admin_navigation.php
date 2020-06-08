<?php

return [
    [
        'name' => 'Основное',
        'sense' => 'group',
        'children' => [
            [
                'name'  => 'Главная',
                'sense' => 'link',
                'icon'  => 'fa fa-home',
                'route' => 'admin.index',
            ],

            [
                'name'  => 'Рефералы',
                'sense' => 'link',
                'icon'  => 'fa fa-users',
                'route' => 'admin.referrals',
            ],

            /*[
                'name'  => 'Доход',
                'sense' => 'link',
                'icon'  => 'fa fa-usd',
                'route' => 'admin.profit',
            ],*/
        ],
    ],

    [
        'name' => 'Бонусы',
        'sense' => 'group',
        'children' => [
            [
                'name'  => 'Промо-коды',
                'sense' => 'link',
                'icon'  => 'fa fa-gift',
                'route' => 'admin.promocodes.index',
            ],
        ],
    ],

    [
        'name' => 'Пользователи',
        'sense' => 'group',
        'children' => [
            [
                'name'  => 'Поиск',
                'sense' => 'link',
                'icon'  => 'fa fa-search',
                'route' => 'admin.index',
                'show_active'   => false,
                'class'         => 'navigation-user-search',
            ],

            [
                'name'  => 'Подкрутка',
                'sense' => 'link',
                'icon'  => 'fa fa-sliders',
                'route' => 'admin.chances.index',
            ],

            [
                'name'  => 'Заявки на вывод',
                'sense' => 'link',
                'icon'  => 'fa fa-list',
                'route' => 'admin.withdraws.index',
            ],
        ],
    ],

    [
        'name' => 'Система кейсов',
        'sense' => 'group',
        'children' => [
            [
                'name'  => 'Категории',
                'sense' => 'link',
                'icon'  => 'fa fa-th',
                'route' => 'admin.categories.index',
            ],

            [
                'name'  => 'Кейсы',
                'sense' => 'link',
                'icon'  => 'fa fa-th-large',
                'route' => 'admin.boxes.index',
            ],
        ],
    ],

    [
        'name' => 'Прочее',
        'sense' => 'group',
        'children' => [
            [
                'name'  => 'FAQ',
                'sense' => 'link',
                'icon'  => 'fa fa-link',
                'route' => 'admin.questions.index',
            ],

            [
                'name'  => 'Настройки',
                'sense' => 'link',
                'icon'  => 'fa fa-cog',
                'route' => 'admin.settings.index',
            ],
        ],
    ],
];
