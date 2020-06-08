<?php

use kvrvch\Settings\Services\SettingsTypes as Settings;

return [
    [
        'key'       => 'bonuses-percent-buying-box',
        'type'      => Settings::TYPE_INTEGER,
        'default'   => 10,
        'validator' => 'required|integer',
    ],
    [
        'key'       => 'auction-default-winner-bonus-percent',
        'type'      => Settings::TYPE_INTEGER,
        'default'   => 20,
        'validator' => 'required|integer',
    ],
];