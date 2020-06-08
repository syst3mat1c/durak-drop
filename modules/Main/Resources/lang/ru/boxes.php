<?php

use Modules\Main\Entities\Box;

return [
    'types' => [
        Box::TYPE_COUNTERS      => 'Счётчики',
        Box::TYPE_PERCENTS      => 'Проценты',
    ],

    'statuses' => [
        Box::STATUS_DISABLED    => 'Скрыт',
        Box::STATUS_ENABLED     => 'Открыт',
    ]
];
