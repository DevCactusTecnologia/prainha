<?php
 
namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
 
class MaskMoney implements CastsAttributes
{
    public function __construct(
        private string $column
    ) {}

    public function get($model, $key, $value, $attributes)
    {
        $money = $attributes[$this->column];

        if (is_null($money)) {
            return '-';
        }

        return 'R$ ' . number_format($money, 2, ',', '.');
    }
 
    public function set($model, $key, $value, $attributes)
    {
        // 
    }
}
