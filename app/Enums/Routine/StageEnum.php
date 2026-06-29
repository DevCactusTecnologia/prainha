<?php

namespace App\Enums\Routine;

enum StageEnum: int
{
    case REGISTER = 1;
    case INSERT_RESULT = 2;
    case CHECKED = 3;
    case PRINTER = 4;
    case CANCELED = 5;
    case EDIT_RESULT = 6;
    case RESTORED = 7;

    public function getName(): string
    {
        return match($this) {
            static::REGISTER => 'Cadastro',
            static::INSERT_RESULT => 'Inserir Resultado',
            static::CHECKED => 'Conferido',
            static::PRINTER => 'Impressão',
            static::CANCELED => 'Cancelado',
            static::EDIT_RESULT => 'Ateração do resultado',
            static::RESTORED => 'Restaurado',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            static::REGISTER => '<i class="mdi mdi-information-outline mdi-18px align-middle text-warning"></i>',
            static::INSERT_RESULT => '<i class="mdi mdi-check mdi-18px align-middle text-info"></i>',
            static::CHECKED => '<i class="mdi mdi-check-all mdi-18px align-middle text-success"></i>',
            static::PRINTER => '<i class="mdi mdi-printer mdi-18px align-middle text-dark"></i>',
            static::CANCELED => '<i class="mdi mdi-information-outline mdi-18px align-middle text-danger"></i>',
            static::EDIT_RESULT => '<i class="mdi mdi-pencil mdi-18px align-middle text-secondary"></i>',
            static::RESTORED => '<i class="mdi mdi-delete-restore mdi-18px align-middle text-secondary"></i>',
        };   
    }

    public static function getValues(): array
    {
        return collect(self::cases())
            ->pluck('value')
            ->toArray();
    }
}
