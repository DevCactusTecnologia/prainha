<?php

namespace App\Traits\ResultExam;

use PDF;

trait FooterPdf
{
    private function footer($isPaginate = true)
    {
        PDF::setFooterCallback(function ($pdf) use ($isPaginate) {
            $this->drawRectangle($pdf);
            
            $pdf->SetTextColor(0, 0, 0);
            $pdf->setTextShadow(['enabled' => true]);

            $pdf->SetXY(15, $pdf->getPageHeight() - 18);
            $pdf->SetFont('helvetica', '', 7);
            $pdf->Cell(w: $pdf->getPageWidth() - 20, h: 7, txt: 'Passagem Castelo Branco', border: 0, ln: 1, align: 'L', fill: false, link: '', stretch: 0);
            
            $pdf->SetXY(100, $pdf->getPageHeight() - 18);
            $pdf->SetFont('helvetica', 'B', 7);
            $pdf->Cell(w: $pdf->getPageWidth() - 20, h: 7, txt: 'RESPONSÁVEL TÉCNICO', border: 0, ln: 1, align: 'L', fill: false, link: '', stretch: 0);

            $pdf->SetXY(15, $pdf->getPageHeight() - 15);
            $pdf->SetFont('helvetica', '', 7);
            $pdf->Cell(w: $pdf->getPageWidth() - 20, h: 7, txt: 'Bairro da Paz', border: 0, ln: 1, align: 'L', fill: false, link: '', stretch: 0);

            $pdf->SetXY(100, $pdf->getPageHeight() - 15);
            $pdf->Cell(w: $pdf->getPageWidth() - 20, h: 7, txt: 'NUBIA SIMEI OTONI MAGNO', border: 0, ln: 1, align: 'L', fill: false, link: '', stretch: 0);

            $pdf->SetXY(15, $pdf->getPageHeight() - 12);
            $pdf->SetFont('helvetica', 'B', 7);
            $pdf->Cell(w: $pdf->getPageWidth() - 20, h: 7, txt: 'Prainha/PA', border: 0, ln: 1, align: 'L', fill: false, link: '', stretch: 0);

            $pdf->SetXY(100, $pdf->getPageHeight() - 12);
            $pdf->SetFont('helvetica', '', 7);
            $pdf->Cell(w: $pdf->getPageWidth() - 20, h: 7, txt: 'CRF/PA: 1884', border: 0, ln: 1, align: 'L', fill: false, link: '', stretch: 0);

            if ($isPaginate) {
                $pdf->SetXY(0, $pdf->getPageHeight() - 8);
                $pdf->setFontSize('7');
                $pdf->Cell(212, 9, 'Página '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
            }
        });
    }

    private function drawRectangle(&$pdf): void
    {
        $absX = 10; // Abscissa x of upper-left corner.
        $absY = $pdf->getPageHeight() - 18; // Abscissa y of upper-left corner.
        $rectWidth = $pdf->getPageWidth() - 19;
        $rectHeight = 12;

        /*
            <li>S or D: Stroke the path.</li>
            <li>s or d: Close and stroke the path.</li>
            <li>f or F: Fill the path, using the nonzero winding number rule to determine the region to fill.</li>
            <li>f* or F*: Fill the path, using the even-odd rule to determine the region to fill.</li>
            <li>B or FD or DF: Fill and then stroke the path, using the nonzero winding number rule to determine the region to fill.</li>
            <li>B* or F*D or DF*: Fill and then stroke the path, using the even-odd rule to determine the region to fill.</li>
            <li>b or fd or df: Close, fill, and then stroke the path, using the nonzero winding number rule to determine the region to fill.</li>
            <li>b or f*d or df*: Close, fill, and then stroke the path, using the even-odd rule to determine the region to fill.</li>
            <li>CNZ: Clipping mode using the even-odd rule to determine which regions lie inside the clipping path.</li>
            <li>CEO: Clipping mode using the nonzero winding number rule to determine which regions lie inside the clipping path</li>
            <li>n: End the path object without filling or stroking it.</li>
        */
        $rectStyle = 'F';
        $rectBorderStyle = []; // ['T' => 'T'] => L = left, T = top, R = right, B = bottom
        $rectFillColor = [239, 242, 247];

        $pdf->Rect($absX, $absY, $rectWidth, $rectHeight, $rectStyle, $rectBorderStyle, $rectFillColor); 
    }
}
