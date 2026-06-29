<?php
 
namespace App\Casts;

use App\Helpers\Fill;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
 
class MaskNis implements CastsAttributes
{
    public function __construct(
        private string $column
    ) {}

    public function get($model, $key, $value, $attributes)
    {
        $nis = $attributes[$this->column];

        if (is_null($nis) || empty($nis)) {
            return '-';
        }

        return Fill::maskNis($nis);
    }
 
    public function set($model, $key, $value, $attributes)
    {
        // 
    }
}
