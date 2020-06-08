<?php

use kvrvch\Settings\Services\SettingsTypes as Settings;

return [
    [
        'key'       => 'vkontakte-link',
        'type'      => Settings::TYPE_STRING,
        'default'   => 'https://vk.com/id1',
        'validator' => 'required|string|url|max:255'
    ],
    [
        'key'       => 'main-referral-users',
        'type'      => Settings::TYPE_STRING,
        'default'   => '32',
        'validator' => 'required|string|max:255'
    ],
    [
        'key'       => 'main-referral-users-credits-bonus-sum',
        'type'      => Settings::TYPE_STRING,
        'default'   => '50000',
        'validator' => 'required|string|max:255'
    ],
    [
        'key'       => 'referral-users-credits-bonus-sum',
        'type'      => Settings::TYPE_STRING,
        'default'   => '10000',
        'validator' => 'required|string|max:255'
    ]
];
