<?php

return [
    'disabled'      => env('BOSS_DISABLED', false),
    'confirmation'  => [
        'always'        => false,
        'production'    => true,
    ],
    'usage' => [
        'listeners' => [
            'database' => true,
            'frontend' => true,
        ]
    ],
    'extra' => [
        'nwidart_modules' => true,
    ]
];
