<?php

namespace App\Enums\Shared;

enum ActiveEnum: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;

    public function getName(): string
    {
        return match($this) {
            static::INACTIVE => 'Inativo',
            static::ACTIVE => 'Ativo'
        };
    }

    public function getColor(): string
    {
        return match($this) {
            static::INACTIVE => 'alert-danger rounded-pill px-3 py-1',
            static::ACTIVE => 'alert-success rounded-pill px-3 py-1'
        };
    }

    public static function getValues(): array
    {
        return collect(self::cases())
            ->pluck('value')
            ->toArray();
    }

}
