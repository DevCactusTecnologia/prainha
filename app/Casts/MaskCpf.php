<?php
 
namespace App\Casts;

use App\Helpers\Fill;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
 
class MaskCpf implements CastsAttributes
{
    public function __construct(
        private string $column
    ) {}

    public function get($model, $key, $value, $attributes)
    {
        $cpf = $attributes[$this->column];

        if (is_null($cpf) || empty($cpf)) {
            return '-';
        }

        return Fill::maskCpf($cpf);
    }
 
    public function set($model, $key, $value, $attributes)
    {
        // 
    }
}
