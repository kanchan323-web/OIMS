<?php

function IND_money_format($number)
{
    $number = (float)$number;

    $decimal = $number - floor($number);
    $money = floor($number);

    $moneyStr = strrev((string)$money);
    $formatted = '';

    for ($i = 0; $i < strlen($moneyStr); $i++) {
        if ($i == 3 || ($i > 3 && ($i - 1) % 2 == 0)) {
            $formatted .= ',';
        }
        $formatted .= $moneyStr[$i];
    }

    $result = strrev($formatted);

    // Format decimal if present
    if ($decimal > 0) {
        $decimalFormatted = number_format($decimal, 2);
        $decimalFormatted = substr($decimalFormatted, 1); // remove leading 0
        $result .= $decimalFormatted;
    }

    return $result;
}

