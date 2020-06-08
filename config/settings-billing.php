<?php

use kvrvch\Settings\Services\SettingsTypes as Settings;

return [
    [
        'key'       => 'merchant-id',
        'type'      => Settings::TYPE_STRING,
        'default'   => '100',
        'validator' => 'required|integer|min:0|max:1000000'
    ],

    [
        'key'       => 'merchant-secret1',
        'type'      => Settings::TYPE_STRING,
        'default'   => 'secret1',
        'validator' => 'required|string|max:255'
    ],

    [
        'key'       => 'merchant-secret2',
        'type'      => Settings::TYPE_STRING,
        'default'   => 'secret2',
        'validator' => 'required|string|max:255'
    ],
];
