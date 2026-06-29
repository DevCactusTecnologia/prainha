<?php

namespace App\Helpers;

class Pagination
{
    public static function getLimit(): int
    {
        $limit = session()->has('page_limit')
            ? session()->get('page_limit')
            : config('app.page_limit');

        return $limit;
    }

}
