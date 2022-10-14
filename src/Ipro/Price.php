<?php

namespace IproSync\Ipro;

class Price
{
    public static function format(float $amount, ?string $currency = null): string
    {
        return $currency.number_format(
            $amount,
            (int) config('iprosoftware-sync.number_format.decimals'),
            config('iprosoftware-sync.number_format.decimal_separator'),
            config('iprosoftware-sync.number_format.thousands_separator'),
        );
    }
}
