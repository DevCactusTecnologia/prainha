<?php

namespace App\Enums\Appointment;

enum StatusEnum: int
{
    case PENDING = 0;
    case COMPLETED = 1;
    case CANCELED = 2;

    public function getName(): string
    {
        return match($this) {
            static::PENDING => 'Pendente',
            static::COMPLETED => 'Finalizado',
            static::CANCELED => 'Cancelado',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            static::PENDING => 'alert-warning rounded-pill px-3 py-1',
            static::COMPLETED => 'alert-success rounded-pill px-3 py-1',
            static::CANCELED => 'alert-danger rounded-pill px-3 py-1',
        };
    }

    public static function getValues(): array
    {
        return collect(self::cases())
            ->pluck('value')
            ->toArray();
    }

}
