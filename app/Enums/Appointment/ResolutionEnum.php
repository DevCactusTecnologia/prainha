<?php

namespace App\Enums\Appointment;

enum ResolutionEnum: int
{
    case PENDING = 0;
    case PATIENT_NOTIFIED = 1;
    case PATIENT_NOTIFIED_THROUGH_OTHER_PEOPLE = 2;
    case OCCURRENCE_RESOLVED = 3;
    case RECOLLECT = 4;
    case RESOLVED = 5;

    public function getName(): string
    {
        return match($this) {
            static::PENDING => 'PENDENTE',
            static::PATIENT_NOTIFIED => 'PACIENTE FOI AVISADO',
            static::PATIENT_NOTIFIED_THROUGH_OTHER_PEOPLE => 'PACIENTE FOI AVISADO ATAVÉS DE TERCEIRO',
            static::OCCURRENCE_RESOLVED => 'OCORRÊNCIA RESOLVIDA',
            static::RECOLLECT => 'RECOLETA REALIZADA',
            static::RESOLVED => 'RESOLVIDA',
        };
    }
}
