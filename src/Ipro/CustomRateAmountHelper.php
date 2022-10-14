<?php

namespace IproSync\Ipro;

use Illuminate\Support\Str;

class CustomRateAmountHelper
{
    public static function convertListToHumanReadable(array $amountList, string $currency = '$'): array
    {
        $formattedAmountList = [];
        foreach ($amountList as $key => $item) {
            $formattedAmountList[$key] = static::convertToHumanReadable((string) $item, $currency);
        }

        return $formattedAmountList;
    }

    public static function convertToHumanReadable(string $amount, string $currency = '$'): array
    {
        $amount = trim($amount);

        $offer   = '';
        $useFrom = false;

        if ($amount === '-2') {
            return [
                'text' => trans('ipro-sync::prices.hide'),
                'hint' => trans('ipro-sync::prices.hints.hide'),
            ];
        }

        if ($amount === '-1') {
            return [
                'text' => trans('ipro-sync::prices.no_price'),
                'hint' => trans('ipro-sync::prices.hints.no_price'),
            ];
        }

        if ($amount === '0') {
            return [
                'text' => trans('ipro-sync::prices.previous_week'),
                'hint' => trans('ipro-sync::prices.hints.previous_week'),
            ];
        }

        if (Str::startsWith($amount, '+')) {
            $useFrom = true;
            $amount  = Str::after($amount, '+');
        }

        if (Str::endsWith($amount, '*')) {
            $offer  = Str::after($amount, '*').'*';
            $amount = trim(Str::before($amount, '*'));
        }

        $priceText = Price::format((float)$amount, $currency);

        if ($useFrom) {
            $priceText = trans('ipro-sync::prices.additional.from', ['price' => $priceText]);
        }

        if ($offer) {
            $priceText = trans('ipro-sync::prices.additional.offer.'.$offer, ['price' => $priceText]);
        }

        return [
            'text' => $priceText,
            'hint' => trans('ipro-sync::prices.hints.price'),
        ];
    }
}
