<?php

namespace App\Enums\Appointment;

enum DocumentTypeEnum: int
{
    case DOCUMENT_PESSOAL = 1;
    case COMPROVANT_HOUSE = 2;
    case ATEST = 3;
    case PRESCRIPTION = 4;
    case REQUISITION = 5;
    case PROCCESS = 6;

    public function getName(): string
    {
        return match($this) {
            static::DOCUMENT_PESSOAL => 'DOCUMENTO PESSOAL',
            static::COMPROVANT_HOUSE => 'COMPROVANTE DE RESIDÊNCIA',
            static::ATEST => 'ATESTADO',
            static::PRESCRIPTION => 'PRESCRIÇÃO MÉDICA',
            static::REQUISITION => 'REQUISIÇÃO',
            static::PROCCESS => 'PROCESSO JUDICIAL',
        };
    }
}
