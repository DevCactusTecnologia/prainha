<?php

namespace App\Helpers;

use Carbon\Carbon;

class Date
{
    const MONTH_NAME = [
        ['id' => 1, 'name' => 'Janeiro'],
        ['id' => 2, 'name' => 'Fevereiro'],
        ['id' => 3, 'name' => 'Março'],
        ['id' => 4, 'name' => 'Abril'],
        ['id' => 5, 'name' => 'Maio'],
        ['id' => 6, 'name' => 'Junho'],
        ['id' => 7, 'name' => 'Julho'],
        ['id' => 8, 'name' => 'Agosto'],
        ['id' => 9, 'name' => 'Setembro'],
        ['id' => 10, 'name' => 'Outubro'],
        ['id' => 11, 'name' => 'Novembro'],
        ['id' => 12, 'name' => 'Dezembro']
    ];

    const DAY_OF_WEEK = [
        ['id' => 1, 'name' => 'Domingo'],
        ['id' => 2, 'name' => 'Segunda'],
        ['id' => 3, 'name' => 'Terça'],
        ['id' => 4, 'name' => 'Quarta'],
        ['id' => 5, 'name' => 'Quinta'],
        ['id' => 6, 'name' => 'Sexta'],
        ['id' => 7, 'name' => 'Sábado']
    ];

    const MONTHS = [
        '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'
    ];

    public static function monthName(int $number)
    {
        return collect(self::MONTH_NAME)
            ->firstWhere('id', $number)['name'];
    }

    public static function dayOfWeeks()
    {
        return json_decode(json_encode(self::DAY_OF_WEEK), false);
    }

    public static function age($dateOfBirth)
    {   
        return Carbon::parse($dateOfBirth)->age;  
    }

    public static function dayOfWeekBr($number)
    {
        $dayOfWeekBr = [
            ['id' => '0', 'name' => 'Domingo'],
            ['id' => '1', 'name' => 'Segunda-feira'],
            ['id' => '2', 'name' => 'Terça-feira'],
            ['id' => '3', 'name' => 'Quarta-feira'],
            ['id' => '4', 'name' => 'Quinta-feira'],
            ['id' => '5', 'name' => 'Sexta-feira'],
            ['id' => '6', 'name' => 'Sábado']
        ];

        return collect($dayOfWeekBr)->firstWhere('id', $number)['name']; 
    }

}
