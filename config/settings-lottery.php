<?php

use kvrvch\Settings\Services\SettingsTypes as Settings;

return [
    [
        'key'       => 'is_started',
        'type'      => Settings::TYPE_INTEGER,
        'default'   => 0,
        'validator' => 'required|integer',
    ],
    [
        'key'       => 'price_for_participants_by_money',
        'type'      => Settings::TYPE_INTEGER,
        'default'   => 20,
        'validator' => 'required|integer',
    ],
    [
        'key'       => 'awaiting_time_for_free_paticipation_in_seconds',
        'type'      => Settings::TYPE_INTEGER,
        'default'   => 86400,
        'validator' => 'required|integer',
    ],
    [
        'key'       => 'round_time_in_seconds',
        'type'      => Settings::TYPE_INTEGER,
        'default'   => 300,
        'validator' => 'required|integer',
    ],
    [
        'key'       => 'item_id',
        'type'      => Settings::TYPE_INTEGER,
        'default'   => 130,
        'validator' => 'required|integer',
    ],
    [
        'key'       => 'count_win_users',
        'type'      => Settings::TYPE_INTEGER,
        'default'   => 10,
        'validator' => 'required|integer',
    ],
];