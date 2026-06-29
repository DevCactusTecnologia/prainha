<?php

namespace App\Traits\ResultExam;

use App\Models\Appointment\Appointment;
use PDF;

trait HeaderPdf
{
    private function header(Appointment $appointment, $patient)
    {
        PDF::setHeaderCallback(function ($pdf) use ($appointment, $patient) {
            $this->generateTop($pdf);
            $this->generateBody($appointment, $pdf, $patient);
            $this->drawBarCode($appointment->id, $pdf);
        });
    }

    private function headerAppointmentByDay(array $appointment)
    {
        PDF::setHeaderCallback(function ($pdf) use ($appointment) {
            $this->generateTop($pdf);
            $this->generateBody($appointment, $pdf);
            $this->drawBarCode($appointment['protocol'], $pdf);
        });
    }

    private function generateTop(&$pdf): void
    {
        // NOME DO ARQUIVO (CAMINHO)
        $filePath = public_path('assets/images/brasao.png');

        // ABSCISSA NUM DETERMINADO PONTO HORIZONTAL DA PÁGINA
        $absX = 10;

        // ABSCISSA NUM DETERMINADO PONTO VERTICAL DA PÁGINA
        $absY = 5;

        // LARGURA DA IMAGEM EM UNDIADES unit, SE FOR 0, A LARGURA DA IMAGEM É CALCULADA AUTOMATICAMENTE
        $imageWidth = 14;

        // ALTURA DA IMAGEM EM UNDIADES unit, SE FOR 0, A ALTURA DA IMAGEM É CALCULADA AUTOMATICAMENTE
        $imageHeight = 14;

        // JPEG E PNG (SEM GD LIBRARY), SE NÃO ESPECIFICADO, SERÁ USADO A EXTENSÃO DA IMAGEM
        $format = '';

        // LINK DA URL CASO TENHA, OU UTILIZAR A FUNÇÃO AddLink()
        $link = '';

        //  INDICA O ALINHAMENTO DO PONTO PROXIMO DA INSERÇÃO DA IMAGEM RELATIVO A ALTURA. 
        //  O VALOR PODE SER: 'T': TOP-DIREITA, 'M': CENTRO-DIREITA, 'B': BAIXO-DIREITA, 'N': PRÓXIMA LINHA
        $align = '';

        // SE TRUE REDIMENSIONA A IMAGEM PARA A ALTURA E LARGURA DA IMAGEM  (REQUER GD OU ImageMagick LIBRARY); 
        // SE FALSE, NÃO REDIMENSIONA 
        $resize = true;

        // PONTOS POR PIXEL - RESOLUÇÃO USADA PARA REDIMENSINAR A IMAGEM
        $dpi = 400;

        // PERMITE CENTRALIZAR OU ALINHAR A IMAGEM NA LINHA ATUAL. 
        // OS VALORES SÃO: 'L', 'C', 'R', ''
        $rowAlign = '';

        // VERIFICA SE A IMAGEM POSSUI MÁSCARA, SE SIM TRUE, CASO CONTRÁRIO FALSE
        $isMask = false;

        // VERIFICA SE A IMAGEM POSSUI MÁSCARA, SE SIM TRUE, CASO CONTRÁRIO FALSE
        $isMask = false;

        // OBJETO DA IMAGEM RETORNADO DESSA FUNÇÃO OU FALSE
        $imageMask = false;

        // INDICA SE A BORDA DEVE SER DESENHADA EM TORNO DA CÉLULA 
        // O VALOR PODE SER: 0: SEM BORDA (DEFAULT), 1: FRAME OU UMA STRING  COM TODOS OS SEGUINTES CARACTERES (EM QUALQUER ORDEM)
        // L: left, T: top, R: right, B: bottom OU UM ARRAY DE ESTILOS DE LINHAS: array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)))
        $border = 0;

        $pdf->Image($filePath, $absX, $absY, $imageWidth, $imageHeight, $format, $link, $align, $resize, $dpi, $rowAlign, $isMask, $imageMask, $border);

        $pdf->SetXY(28, 5);
        $pdf->SetFont('helvetica', '', 14);

        /**
         * @param boolean $reseth if true reset the last cell height (default true).
         * @param int $stretch font stretch mode: <ul><li>0 = disabled</li><li>1 = horizontal scaling only if text is larger than cell width</li><li>2 = forced horizontal scaling to fit cell width</li><li>3 = character spacing only if text is larger than cell width</li><li>4 = forced character spacing to fit cell width</li></ul> General font stretching and scaling values will be preserved when possible.
         * @param boolean $ishtml INTERNAL USE ONLY -- set to true if $txt is HTML content (default = false). Never set this parameter to true, use instead writeHTMLCell() or writeHTML() methods.
         * @param boolean $autopadding if true, uses internal padding and automatically adjust it to account for line width.
         * @param float $maxh maximum height. It should be >= $h and less then remaining space to the bottom of the page, or 0 for disable this feature. This feature works only when $ishtml=false.
         * @param string $valign Vertical alignment of text (requires $maxh = $h > 0). Possible values are:<ul><li>T: TOP</li><li>M: middle</li><li>B: bottom</li></ul>. This feature works only when $ishtml=false and the cell must fit in a single page.
         * @param boolean $fitcell if true attempt to fit all the text within the cell by reducing the font size (do not work in HTML mode). $maxh must be greater than 0 and equal to $h.
         * @return int Return the number of cells or 1 for html mode.
         * @public
         * @since 1.3
         * @see SetFont(), SetDrawColor(), SetFillColor(), SetTextColor(), SetLineWidth(), Cell(), Write(), SetAutoPageBreak() */

        $multiCellWidth = 150; // largura da celula. se 0, a celula se extende para a margem a direita
        $multiCellHeight = 15; // Altura da celula. padrão 0.
        $text = " PREFEITURA MUNICIPAL DE PRAINHA \n SECRETARIA MUNICIPAL DE SAÚDE\n LABORATÓRIO MUNICIPAL WILSON RIBEIRO\n CNES: 2331756";
        $withBorder = 0; // 0: sem borda (default) 1: com borda, 'L': left 'T': top, 'R': right, 'B': bottom, array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)))
        $positionCurrent = 1; // Indica onde a posição atual deve ir após a chamada. 0: to the right (or left for RTL languages)</li><li>1: to the beginning of the next line</li><li>2: below</li></ul> Putting 1 is equivalent to putting 0 and calling Ln() just after. Default value: 0.
        $alignText = ''; // C = CENTER, L = LEFT, R = RIGHT, J = JUSTIFY
        $fill = false; // $fill Indicates if the cell background must be painted (true) or transparent (false).
        $ln = ''; // Indicates where the current position should go after the call. Possible values are:<ul><li>0: to the right</li><li>1: to the beginning of the next line [DEFAULT]</li><li>2: below</li></ul>
        
        $x = null; // * @param float|null $x x position in user units
        $y = null; // * @param float|null $y y position in user units

        $reseth = true; // * $reseth if true reset the last cell height (default true).
        $stretch = 0; // * $stretch font stretch mode: <ul><li>0 = disabled</li><li>1 = horizontal scaling only if text is larger than cell width</li><li>2 = forced horizontal scaling to fit cell width</li><li>3 = character spacing only if text is larger than cell width</li><li>4 = forced character spacing to fit cell width</li></ul> General font stretching and scaling values will be preserved when possible.

        $isHtml = true; // * $ishtml INTERNAL USE ONLY -- set to true if $txt is HTML content (default = false). Never set this parameter to true, use instead writeHTMLCell() or writeHTML() methods.
        $autopadding = true; // *$autopadding if true, uses internal padding and automatically adjust it to account for line width.
        
        $maxh = 0; //  $maxh maximum height. It should be >= $h and less then remaining space to the bottom of the page, or 0 for disable this feature. This feature works only when $ishtml=false.
        $valign = 'T'; // $valign Vertical alignment of text (requires $maxh = $h > 0). Possible values are:<ul><li>T: TOP</li><li>M: middle</li><li>B: bottom</li></ul>. This feature works only when $ishtml=false and the cell must fit in a single page.
        $fitcell = false; // *$fitcell if true attempt to fit all the text within the cell by reducing the font size (do not work in HTML mode). $maxh must be greater than 0 and equal to $h.

        $pdf->MultiCell($multiCellWidth, $multiCellHeight, $text, $withBorder, $positionCurrent, $alignText, $fill, $ln, $x, $y, $reseth, $stretch, $isHtml, $autopadding, $maxh, $valign);

        $pdf->SetXY(10, 22);

        /**
         * Draws a line between two points.
         * @param float $x1 Abscissa of first point.
         * @param float $y1 Ordinate of first point.
         * @param float $x2 Abscissa of second point.
         * @param float $y2 Ordinate of second point.
         * @param array $style Line style. Array like for SetLineStyle(). Default value: default line style (empty array).
         * @public
         * @since 1.0
         * @see SetLineWidth(), SetDrawColor(), SetLineStyle()
         */
        $x1 = 10;
        $y1 = 21;
        $x2 = 199;
        $y2 = 21;
        $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(214, 219, 228));
        $pdf->Line($x1, $y1, $x2, $y2, $style);
    }

    private function generateBody(array|Appointment $appointment, &$pdf, $patient = null): void
    {   
        $pdf->SetXY(10, 23);
        $pdf->SetFont('helvetica', '', 9);

        if (is_array($appointment)) {
            $patientName = $appointment['patient_name'];
            $patientAge = $appointment['patient_age'];
            $patientGender = $appointment['patient_gender_name'];
            $companyName = $appointment['company_name'];
            $doctorName = $appointment['doctor_name'];
            $unityShortName = $appointment['unity_short_name'];
            $registeredAt = $appointment['registered_at'];
            $checkedAt = date('d/m/Y', strtotime($appointment['checked_at']));
        } else {
            $patientName = $appointment->patient->first_name;
            $patientAge = $patient->ageExtended($appointment->appointment_date);
            $patientGender = $patient->gender_name;
            $companyName = $appointment->company?->name;
            $doctorName = $appointment->doctor->first_name;
            $unityShortName = $appointment->unity?->short_name;
            $registeredAt = $appointment->registered_at_formatted;
            $checkedAt = $appointment->checked_at_formatted;
        }

        $html = <<<HTML
            <table style="color: #000000; font-family: helvetica; width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 60%;"><span>Paciente &nbsp;&nbsp;: <strong>{$patientName}</strong></span><br />
                            <span>Idade &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$patientAge}</span><br />
                            <span>Convênio &nbsp;: {$companyName}</span><br />
                            <span>Solicitante : {$doctorName}</span>
                        </td>
                        <td style="width: 40%;"><span>Sexo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {$patientGender}</span><br />
                            <span>Unidade&nbsp;&nbsp;&nbsp;&nbsp;: {$unityShortName}</span><br />
                            <span>Cadastro&nbsp;&nbsp;&nbsp;: {$registeredAt}</span><br />
                            <span>Conferido&nbsp;&nbsp;: {$checkedAt}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        HTML;

        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '',
            $html, $border = 0, $ln = 1, $fill = 0,
            $reseth = true, $align = 'top', $autopadding = true
        );
    }

    private function drawBarCode(int|string|null $code, &$pdf): void
    {
        $pdf->SetFont('helvetica', '', 8);
        $pdf->writeHTMLCell(w: 40, h: 0, x: 177, y: 23,
            html: 'Protocolo', border: 0, ln: 1, fill: 0,
            reseth: true, align: 'top', autopadding: true
        );

        /**
         * Print a Linear Barcode.
         * @param string $code code to print
         * @param string $type type of barcode (see tcpdf_barcodes_1d.php for supported formats).
         * @param float|null $x x position in user units (null = current x position)
         * @param float|null $y y position in user units (null = current y position)
         * @param float|null $w width in user units (null = remaining page width)
         * @param float|null $h height in user units (null = remaining page height)
         * @param float|null $xres width of the smallest bar in user units (null = default value = 0.4mm)
         * @param array $style array of options:<ul>
         * <li>boolean $style['border'] if true prints a border</li>
         * <li>int $style['padding'] padding to leave around the barcode in user units (set to 'auto' for automatic padding)</li>
         * <li>int $style['hpadding'] horizontal padding in user units (set to 'auto' for automatic padding)</li>
         * <li>int $style['vpadding'] vertical padding in user units (set to 'auto' for automatic padding)</li>
         * <li>array $style['fgcolor'] color array for bars and text</li>
         * <li>mixed $style['bgcolor'] color array for background (set to false for transparent)</li>
         * <li>boolean $style['text'] if true prints text below the barcode</li>
         * <li>string $style['label'] override default label</li>
         * <li>string $style['font'] font name for text</li><li>int $style['fontsize'] font size for text</li>
         * <li>int $style['stretchtext']: 0 = disabled; 1 = horizontal scaling only if necessary; 2 = forced horizontal scaling; 3 = character spacing only if necessary; 4 = forced character spacing.</li>
         * <li>string $style['position'] horizontal position of the containing barcode cell on the page: L = left margin; C = center; R = right margin.</li>
         * <li>string $style['align'] horizontal position of the barcode on the containing rectangle: L = left; C = center; R = right.</li>
         * <li>string $style['stretch'] if true stretch the barcode to best fit the available width, otherwise uses $xres resolution for a single bar.</li>
         * <li>string $style['fitwidth'] if true reduce the width to fit the barcode width + padding. When this option is enabled the 'stretch' option is automatically disabled.</li>
         * <li>string $style['cellfitalign'] this option works only when 'fitwidth' is true and 'position' is unset or empty. Set the horizontal position of the containing barcode cell inside the specified rectangle: L = left; C = center; R = right.</li></ul>
         * @param string $align Indicates the alignment of the pointer next to barcode insertion relative to barcode height. The value can be:<ul><li>T: top-right for LTR or top-left for RTL</li><li>M: middle-right for LTR or middle-left for RTL</li><li>B: bottom-right for LTR or bottom-left for RTL</li><li>N: next line</li></ul>
         * @author Nicola Asuni
        * @since 3.1.000 (2008-06-09)
        * @public
        */

        $style = array(
            'position' => 'R',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false,
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 1
        );

        $pdf->write1DBarcode(code: $code, type: 'CODABAR', x: 10, y: 25, w: 35, h: 15, xres: 0.4, style: $style, align: 'N');
        $pdf->Ln();
    }
}
