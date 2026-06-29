<?php
 
namespace App\Casts;

use App\Helpers\Fill;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
 
class MaskCnpj implements CastsAttributes
{
    public function __construct(
        private string $column
    ) {}

    public function get($model, $key, $value, $attributes)
    {
        $cnpj = $attributes[$this->column];

        if (is_null($cnpj) || empty($cnpj)) {
            return '-';
        }

        return Fill::maskCnpj($cnpj);
    }
 
    public function set($model, $key, $value, $attributes)
    {
        // 
    }
}
