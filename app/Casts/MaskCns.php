<?php
 
namespace App\Casts;

use App\Helpers\Fill;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
 
class MaskCns implements CastsAttributes
{
    public function __construct(
        private string $column
    ) {}

    public function get($model, $key, $value, $attributes)
    {
        $cns = $attributes[$this->column];

        if (is_null($cns) || empty($cns)) {
            return '-';
        }

        return Fill::maskCns($cns);
    }
 
    public function set($model, $key, $value, $attributes)
    {
        // 
    }
}
