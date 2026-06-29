<?php
 
namespace App\Casts;

use App\Helpers\Fill;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
 
class MaskCep implements CastsAttributes
{
    public function __construct(
        private string $column
    ) {}

    public function get($model, $key, $value, $attributes)
    {
        $cep = $attributes[$this->column];

        if (is_null($cep) || empty($cep)) {
            return '-';
        }

        return Fill::maskCep($cep);
    }
 
    public function set($model, $key, $value, $attributes)
    {
        // 
    }
}
