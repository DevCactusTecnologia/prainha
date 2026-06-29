<?php

namespace App\Traits\ResultExam;

use PDF;

trait ConfigPdf
{
    private function config()
    {
        PDF::SetCreator('TCPDF');
        PDF::SetAuthor('DevCactus');
        PDF::SetTitle("Resultado dos exames");
        PDF::SetSubject('Resultado dos exames dos pacientes');
        PDF::SetKeywords('resultado, exame, paciente, protocolo');

        $marginLeft = 10;
        $marginRight = 10;
        $marginTop = 41;

        PDF::SetMargins($marginLeft, $marginTop, $marginRight);
        PDF::SetHeaderMargin(0);
    }
}
