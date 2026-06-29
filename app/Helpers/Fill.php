<?php

namespace App\Helpers;

class Fill
{
    public static function maskCpf($value)
    {
        if (is_null($value) || empty($value)) {
            return '-';
        }

        $primarysDigits = substr($value, 0, 3);
        $secondarysDigits = substr($value, 3, 3);
        $trheesDigits = substr($value, 6, 3);
        $digitsVerify = substr($value, 9, 2);

        return "{$primarysDigits}.{$secondarysDigits}.{$trheesDigits}-{$digitsVerify}";
    }

    public static function maskCns($value)
    {
        if (is_null($value) || empty($value)) {
            return '-';
        }

        $primarysDigits = substr($value, 0, 3);
        $secondarysDigits = substr($value, 3, 4);
        $trheesDigits = substr($value, 7, 4);
        $fourDigits = substr($value, 11, 4);

        return "{$primarysDigits} {$secondarysDigits} {$trheesDigits} {$fourDigits}";
    }

    public static function maskCnpj($value)
    {
        if (is_null($value) || empty($value)) {
            return '';
        }

        $primarysDigits = substr($value, 0, 2);
        $secondarysDigits = substr($value, 2, 3);
        $trheesDigits = substr($value, 5, 3);
        $fourDigits = substr($value, 8, 4);
        $digitsVerify = substr($value, 12, 2);

        return "{$primarysDigits}.{$secondarysDigits}.{$trheesDigits}/{$fourDigits}-{$digitsVerify}";
    }

    public static function maskNis($value)
    {
        if (is_null($value) || empty($value)) {
            return '';
        }

        $primarysDigits = substr($value, 0, 10);
        $digitsVerify = substr($value, 10, 1);

        return "{$primarysDigits}-{$digitsVerify}";
    }

    public static function maskCep($value)
    {
        if (is_null($value) || empty($value)) {
            return '';
        }

        $primarysDigits = substr($value, 0, 5);
        $secondarysDigits = substr($value, 5, 3);
        
        return "{$primarysDigits}-{$secondarysDigits}";
    }

    public static function maskCurrencyBrlToEua($value)
    {
        $currencyEua = $value;

        if (strpos($value, '.') >= 1) {
            $currencyEua = str_replace('.', '', $value);
        }

        return str_replace(',', '.', $currencyEua);
    }

    public static function maskTelephone($value)
    {
        if (is_null($value) || empty($value)) {
            return '';
        }

        $primarysDigit = substr($value, 0, strlen($value) - 4);
        $secondarysDigits = substr($value, -4);

        return "{$primarysDigit}-{$secondarysDigits}";
    }

    public static function filterSanitizeNumber($value)
    {
        return preg_replace('/[^\d]/', '', $value);
    }

}
