<?php

if (! function_exists('money')) {
    function money(float $amount)
    {
        return number_format($amount, 0, '', ' ') . 'Р';
    }
}

if (! function_exists('_count')) {
    function _count(int $amount, string $suffix = 'шт.')
    {
        return number_format($amount, 0, '', ' ') . ' ' . $suffix;
    }
}

if (! function_exists('_datetime')) {
    function _datetime($time)
    {
        return is_a($time, \Carbon\Carbon::class) ? $time->format('d.m.Y H:i:s') : null;
    }
}

if (! function_exists('credits')) {
    function credits($amount) {
        $suffixHuman = '';
        $amountHuman = $amount;

        if ($amount >= 1000 && $amount < 1000000) {
            $amountHuman = $amount / 1000;
            $suffixHuman = 'K';
        } elseif ($amount >= 1000000) {
            $amountHuman = $amount / 1000000;
            $suffixHuman = 'M';
        }

        if (is_round($amountHuman)) {
            $decimals = 0;
        } else {
            $mAmount = round($amountHuman * 10, 1);

            if (is_round($mAmount)) {
                $decimals = 1;
            } else {
                $decimals = 2;
            }
        }

        $amountHuman = number_format($amountHuman, $decimals, ',', ' ');

        return "{$amountHuman}{$suffixHuman}";
    }
}

if (! function_exists('is_round')) {
    function is_round($value) {
        return is_numeric($value) && intval($value) == $value;
    }
}

//if (! function_exists('selectize')) {
//    function selectize(array $array) {
//        return [null => 'Не выбрано'] + $array;
//    }
//}
