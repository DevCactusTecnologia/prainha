<?php

namespace App\Enums\Appointment;

enum OccurrenceStatusEnum: int
{
    case PENDING = 0;
    case PARTIAL = 1;
    case RESOLVED = 2;

    public function getName(): string
    {
        return match($this) {
            static::PENDING => 'Pendente',
            static::PARTIAL => 'Parcial',
            static::RESOLVED => 'Resolvido',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            static::PENDING => 'alert-warning rounded-pill px-3 py-1',
            static::PARTIAL => 'alert-info rounded-pill px-3 py-1',
            static::RESOLVED => 'alert-success rounded-pill px-3 py-1',
        };
    }
}
