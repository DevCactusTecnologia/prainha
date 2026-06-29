<?php

namespace App\Helpers;

class Sanitize
{
    public static function number($value)
    {
        return preg_replace('/[^\d]/', '', $value);
    }

}
