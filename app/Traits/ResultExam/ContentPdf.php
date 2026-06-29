<?php

namespace App\Traits\ResultExam;

use App\Models\Biomedical;
use App\Models\Appointment\Result;
use App\Models\Exam\Exam;
use Illuminate\Support\Facades\DB;
use PDF;

trait ContentPdf
{

    /**
     * Text can be aligned, centered or justified. The cell block can be framed and the background painted.
     * @param float $w Width of cells. If 0, they extend up to the right margin of the page.
     * @param float $h Cell minimum height. The cell extends automatically if needed.
     * @param string $txt String to print
     * @param mixed $border Indicates if borders must be drawn around the cell. The value can be a number:<ul><li>0: no border (default)</li><li>1: frame</li></ul> or a string containing some or all of the following characters (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B: bottom</li></ul> or an array of line styles for each border group - for example: array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)))
     * @param string $align Allows to center or align the text. Possible values are:<ul><li>L or empty string: left align</li><li>C: center</li><li>R: right align</li><li>J: justification (default value when $ishtml=false)</li></ul>
     * @param boolean $fill Indicates if the cell background must be painted (true) or transparent (false).
     * @param int $ln Indicates where the current position should go after the call. Possible values are:<ul><li>0: to the right</li><li>1: to the beginning of the next line [DEFAULT]</li><li>2: below</li></ul>
     * @param float|null $x x position in user units
     * @param float|null $y y position in user units
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
     * @see SetFont(), SetDrawColor(), SetFillColor(), SetTextColor(), SetLineWidth(), Cell(), Write(), SetAutoPageBreak()
     */
    private function addHeaderExam(Exam $exam, array &$retests, array $examsIds = [])
    {
        if ($exam->pivot->re_test == '0' && $exam->pivot->status != '2') {
            PDF::SetFont('courier', 'B', 10);
            PDF::SetFillColor(239, 242, 247);
            
            $width = 0;
            $height = 0;
            $border = 0;
            $align = 'L';
            $fill = true;
            $ln = 1;

            PDF::MultiCell($width, $height, $exam->name, $border, $align, $fill, $ln, '', '', true, 0, true); 
        
        // CASO NO ARRAY TENHA COLESTEROL TOTAL E FRACOES E SEJA IGUAL AO EXAME TRIGLICERIDES
        } elseif (in_array(186, $examsIds) && $exam->id === 698 && $exam->pivot->status == '2') {
            // CONTINUE
            
        } else {
            $retests[$exam->id]['name'] = $exam->name;
        }
    }

    private function addHeaderExamRoutine($exam)
    {
        PDF::SetFont('courier', 'B', 10);
        PDF::SetFillColor(239, 242, 247);
        
        $width = 0;
        $height = 0;
        $border = 0;
        $align = 'L';
        $fill = true;
        $ln = 1;

        PDF::MultiCell($width, $height, $exam, $border, $align, $fill, $ln, '', '', true, 0, true);    
    }

    /**
     * Allows to preserve some HTML formatting (limited support).<br />
     * IMPORTANT: The HTML must be well formatted - try to clean-up it using an application like HTML-Tidy before submitting.
     * Supported tags are: a, b, blockquote, br, dd, del, div, dl, dt, em, font, h1, h2, h3, h4, h5, h6, hr, i, img, li, ol, p, pre, small, span, strong, sub, sup, table, tcpdf, td, th, thead, tr, tt, u, ul
     * NOTE: all the HTML attributes must be enclosed in double-quote.
     * @param string $html text to display
     * @param boolean $ln if true add a new line after text (default = true)
     * @param boolean $fill Indicates if the background must be painted (true) or transparent (false).
     * @param boolean $reseth if true reset the last cell height (default false).
     * @param boolean $cell if true add the current left (or right for RTL) padding to each Write (default false).
     * @param string $align Allows to center or align the text. Possible values are:<ul><li>L : left align</li><li>C : center</li><li>R : right align</li><li>'' : empty string : left for LTR or right for RTL</li></ul>
     * @public
     */
    private function addBiomedicalSignature($examId, $examBIomedicalId, $contentLong, &$counter, $examsTotal, &$index)
    {
        $biomedical = Biomedical::firstWhere('user_id', $examBIomedicalId);

        if ($biomedical->signature) {
            $signature = public_path("storage/images/users/signature/{$biomedical->signature}");
            $signature = <<<EOD
                <img src="{$signature}" width="80" /> 
            EOD;
        } else {
            $signature = '';
        }

        PDF::SetFont('courier', '', 8);

        $toolcopy = <<<EOP
            CONFERIDO E LIBERADO POR: {$signature}
        EOP;

        $ln = true;
        $fill = false;
        $reseth = true;
        $cell = true;
        $align = 'R';

        PDF::writeHTML($toolcopy, $ln, $fill, $reseth, $cell, $align);

        if (in_array($examId, $contentLong) && ($examsTotal !== ($index + 1))) {
            $counter = 0;
            PDF::writeHTML('<br pagebreak="true"/>', true, false, true, false, '');
        }

        if ($counter >= 4 && ($examsTotal !== ($index + 1))) {
            $counter = 0;
            PDF::writeHTML('<br pagebreak="true"/>', true, false, true, false, '');
        }
    }

    private function generateContent(
        $appointment, $exam, $patient, string $html, $examId, $examBIomedicalId, $contentLong, 
        &$counter, $examsTotal, &$index, &$biomedicalSignId, &$biomedicalSignContent, array &$retests, array $examsIds = []
    ) {
        $content = $html;

        // CASO NO ARRAY TENHA COLESTEROL TOTAL E FRACOES E SE O EXAME ATUAL FOR TRIGLICERIDES E SE O TRIGLICERIDES FOR STATUS CANCELADO
        if (in_array(186, $examsIds) && $exam->id === 698 && $exam->pivot->status == '2') {
            // CONTINUE

        } elseif (($exam->pivot->status == '1' || $exam->pivot->re_test == '1' || $exam->pivot->status == '2') && $exam->filters->isEmpty()) {
            
            if ($exam->pivot->re_test == '0' && $exam->pivot->status != '2') {
                if (preg_match('/{{(.*?)}}/', $content, $match) == 1) {
                    $content = str_replace($match, '', $content);
                }

                $results = Result::with(['parameter'])
                    ->where('appointment_id', $appointment->id)
                    ->where('exam_id', $exam->id)
                    ->get();

                foreach ($results as $result) {
                    $input = "{$result->result}";

                    if (str_contains($input, '<')) {
                        $input = str_replace('<', '&lt;', $input);
                    }

                    if (str_contains($input, '>')) {
                        $input = str_replace('>', '&gt;', $input);
                    }

                    $content = str_replace($result->parameter->parameter, $input, $content);
                }

                PDF::writeHTMLCell(w: 0, h: 0, x: '', y: '', html: $content, border: 0, ln: 1, fill: false, reseth: true, align: '', autopadding: true);
            } elseif ($exam->pivot->re_test == '0' && $exam->pivot->status == '2') {
                $motive = $exam->pivot->observation ?: 'Não informado';
                $content = "<br><br><strong>Exame Cancelado</strong><br><strong>Motivo: {$motive}</strong>";

                $retests[$exam->id]['content'] = $content;
            } else {
                $motive = $exam->pivot->observation ?: 'Não informado';
                $content = "<br><br><strong>Reteste Solicitado</strong><br><strong>Motivo: {$motive}</strong>";

                $retests[$exam->id]['content'] = $content;
            }

            // RESULTADOS ANTERIORES

            $previousResults = [];

            foreach ($exam->parameters as $parameter) {
                if ($parameter->with_previous_result == '1') {
                    $previousResultsList = DB::select(
                        "SELECT 
                            appointments.checked_at,
                            results.result,
                            new_parameter.parameter

                        FROM results

                        INNER JOIN appointments
                        ON results.appointment_id = appointments.id
                        INNER JOIN new_parameter
                        ON results.parameter_id = new_parameter.id
                        INNER JOIN users
                        ON appointments.appointment_for = users.id

                        WHERE users.id = ? AND new_parameter.id = ?
                        AND appointments.checked_at < ?
                        ORDER BY appointments.checked_at DESC LIMIT 4", [
                            $appointment->appointment_for,
                            $parameter->id,
                            $appointment->checked_at,
                    ]);

                    foreach ($previousResultsList as $key => $value) {
                        $previousResults[$value->checked_at][] = [
                            'result' => $value->result,
                            'parameter' => $value->parameter,
                        ];
                    }
                }
            }

            if (count($previousResults) > 0 && $exam->pivot->re_test == '0' && $exam->pivot->status != '2') {
                $contentPrevioulsResult = <<<EOD
                    <div style="margin-bottom: 8pt;">
                        <div style="background-color: #e7e7e7; font-weight: bold; font-size: 8pt;">Resultados anteriores:</div>
                EOD;

                $indexResult = 1;

                foreach ($previousResults as $checkedAt => $items) {
                    $checkedAtFormatted = date('d/m/Y', strtotime($checkedAt));

                    $contentPrevioulsResult .= <<<EOD
                        <span style="margin-bottom: 5pt; font-size: 8pt;"><strong>{$checkedAtFormatted}:</strong></span>
                    EOD;

                    foreach ($items as $item) {
                        $newParameter = explode('##', $item['parameter'])[1];
                        $result = $item['result'];

                        $contentPrevioulsResult .= <<<EOD
                            <span style="font-size: 8pt;">{$newParameter} => {$result}&nbsp;|&nbsp;</span>
                        EOD;
                    }

                    if ($indexResult == 2) {
                        $contentPrevioulsResult .= <<<EOD
                            <br />
                        EOD;
                    }

                    $indexResult++;
                }

                $contentPrevioulsResult .= <<<EOD
                    </div>
                EOD;

                PDF::writeHTMLCell(w: 0, h: 0, x: '', y: '', html: $contentPrevioulsResult, border: 0, ln: 1, fill: false, reseth: true, align: '', autopadding: true);
            }

        } elseif (($exam->pivot->status == '1' || $exam->pivot->re_test == '1' || $exam->pivot->status == '2') && $exam->filters->isNotEmpty()) {

            if ($exam->pivot->re_test == '0' && $exam->pivot->status != '2') {
                $this->getFilter($exam->filters, $patient, $appointment->appointment_date, $content);

                if (preg_match('/{{(.*?)}}/', $content, $match) == 1) {
                    $content = str_replace($match[0], '', $content);
                }

                $results = Result::with(['parameter'])
                    ->where('appointment_id', $appointment->id)
                    ->where('exam_id', $exam->id)
                    ->get();

                foreach ($results as $result) {
                    $input = "{$result->result}";

                    if (str_contains($input, '<')) {
                        $input = str_replace('<', '&lt;', $input);
                    }

                    if (str_contains($input, '>')) {
                        $input = str_replace('>', '&gt;', $input);
                    }

                    $content = str_replace($result->parameter->parameter, $input, $content);
                }

                PDF::writeHTMLCell(w: 0, h: 0, x: '', y: '', html: $content, border: 0, ln: 1, fill: false, reseth: true, align: '', autopadding: true);
            } elseif ($exam->pivot->re_test == '0' && $exam->pivot->status == '2') {
                $motive = $exam->pivot->observation ?: 'Não informado';
                $content = "<br><br><strong>Exame Cancelado</strong><br><strong>Motivo: {$motive}</strong>";

                $retests[$exam->id]['content'] = $content;
            } else {
                $motive = $exam->pivot->observation ?: 'Não informado';
                $content = "<br><br><strong>Reteste Solicitado</strong><br><strong>Motivo: {$motive}</strong>";

                $retests[$exam->id]['content'] = $content;
            }

            // RESULTADOS ANTERIORES
            $previousResults = [];

            foreach ($exam->parameters as $parameter) {
                if ($parameter->with_previous_result == '1') {
                    $previousResultsList = DB::select(
                        "SELECT 
                            appointments.checked_at,
                            results.result,
                            new_parameter.parameter

                        FROM results

                        INNER JOIN appointments
                        ON results.appointment_id = appointments.id
                        INNER JOIN new_parameter
                        ON results.parameter_id = new_parameter.id
                        INNER JOIN users
                        ON appointments.appointment_for = users.id

                        WHERE users.id = ? AND new_parameter.id = ?
                        AND appointments.checked_at < ?
                        ORDER BY appointments.checked_at DESC LIMIT 4", [
                            $appointment->appointment_for,
                            $parameter->id,
                            $appointment->checked_at,
                    ]);

                    foreach ($previousResultsList as $key => $value) {
                        $previousResults[$value->checked_at][] = [
                            'result' => $value->result,
                            'parameter' => $value->parameter,
                        ];
                    }
                }
            }
  
            if (count($previousResults) > 0 && $exam->pivot->re_test == '0' && $exam->pivot->status != '2') {
                $contentPrevioulsResult = <<<EOD
                    <div style="margin-bottom: 8pt;">
                        <div style="background-color: #e7e7e7; font-weight: bold; font-size: 8pt;">Resultados anteriores:</div>
                EOD;

                $indexResult = 1;

                foreach ($previousResults as $checkedAt => $items) {
                    $checkedAtFormatted = date('d/m/Y', strtotime($checkedAt));

                    $contentPrevioulsResult .= <<<EOD
                        <span style="margin-bottom: 5pt; font-size: 8pt;"><strong>{$checkedAtFormatted}:</strong></span>
                    EOD;

                    foreach ($items as $item) {
                        $newParameter = explode('##', $item['parameter'])[1];
                        $result = $item['result'];

                        $contentPrevioulsResult .= <<<EOD
                            <span style="font-size: 8pt;">{$newParameter} => {$result}&nbsp;|&nbsp;</span>
                        EOD;
                    }

                    if ($indexResult == 2) {
                        $contentPrevioulsResult .= <<<EOD
                            <br />
                        EOD;
                    }

                    $indexResult++;
                }

                $contentPrevioulsResult .= <<<EOD
                    </div>
                EOD;

                PDF::writeHTMLCell(w: 0, h: 0, x: '', y: '', html: $contentPrevioulsResult, border: 0, ln: 1, fill: false, reseth: true, align: '', autopadding: true);
            }
        }

        $biomedical = Biomedical::firstWhere('user_id', $examBIomedicalId);
        $signature = $this->getSignature($biomedical->signature);

        if ($exam->pivot->re_test == '0' && $exam->pivot->status != '2') {
            if (! in_array($examBIomedicalId, $biomedicalSignId)) {
                $biomedicalSignId[] = $examBIomedicalId;
                $biomedicalSignContent[] = [
                    'signature' => $signature,
                    'name' => $biomedical->user->first_name,
                    'council_sigla' => $biomedical->council->sigla,
                    'state' => $biomedical->state->uf,
                    'counsil_number' => $biomedical->counsil_number,
                    'professional_type' => $biomedical->professional?->name,
                ];
            }

        // CASO NO ARRAY TENHA COLESTEROL TOTAL E FRACOES E SEJA IGUAL AO EXAME TRIGLICERIDES
        } else if (in_array(186, $examsIds) && $exam->id === 698 && $exam->pivot->status == '2') {
            // CONTINUE
        } else {
            $retests[$exam->id]['biomedical'] = [
                'signature' => $signature,
                'name' => $biomedical->user->first_name,
                'council_sigla' => $biomedical->council->sigla,
                'state' => $biomedical->state->uf,
                'counsil_number' => $biomedical->counsil_number,
                'professional_type' => $biomedical->professional?->name,
            ];
        }

        $this->renderCheckedBy($biomedical->user->first_name, $exam, $retests, $examsIds);

        if (($exam->pivot->re_test == '0' && $exam->pivot->status != '2') && in_array($examId, $contentLong) && ($examsTotal === ($index))) {
            $this->renderSignaturesPageCurrent($biomedicalSignContent, $biomedicalSignId, $exam);
            $counter = 5;
        }

        if (($exam->pivot->re_test == '0' && $exam->pivot->status != '2') && in_array($examId, $contentLong) && ($examsTotal !== ($index))) {
            $this->renderSignaturesPageCurrent($biomedicalSignContent, $biomedicalSignId, $exam);

            $counter = 0;
            PDF::writeHTML(html: '<br pagebreak="true"/>', ln: true, fill: false, reseth: true, cell: false, align: '');
        }

        if (($exam->pivot->re_test == '0' && $exam->pivot->status != '2') && $counter >= 4 && ($examsTotal !== ($index))) {
            $this->renderSignaturesPageCurrent($biomedicalSignContent, $biomedicalSignId, $exam);

            $counter = 0;
            PDF::writeHTML('<br pagebreak="true"/>', true, false, true, false, '');
        }
        
        if (($exam->pivot->re_test == '0' && $exam->pivot->status != '2') && $counter <= 4 && ($examsTotal === ($index))) {
            $this->renderSignaturesPageCurrent($biomedicalSignContent, $biomedicalSignId, $exam);
        }
    }

    private function renderRetests(array &$retests)
    {
        $examsTotalRetest = count($retests);
        $counterRetest = 0;
        $lsitBiomedicals = [];

        if ($examsTotalRetest > 0) {

            PDF::AddPage();

            foreach ($retests as $examId => $exam) {
                $counterRetest++;
    
                PDF::SetFont('courier', 'B', 10);
                PDF::SetFillColor(239, 242, 247);
    
                $width = 0;
                $height = 0;
                $border = 0;
                $align = 'L';
                $fill = true;
                $ln = 1;
    
                PDF::MultiCell($width, $height, $exam['name'], $border, $align, $fill, $ln, '', '', true, 0, true); 
                
                PDF::SetFont('courier', '', 9);

                $checkedBy = key_exists('checked_by', $exam) ? $exam['checked_by'] : '';
                
                PDF::writeHTMLCell(w: 0, h: 0, x: '', y: '', html: $exam['content'], border: 0, ln: 1, fill: false, reseth: true, align: '', autopadding: true);
                PDF::writeHTML(html: $checkedBy, ln: true, fill: false, reseth: true, cell: true, align: 'R');
            }
    
            $content = <<<EOP
                <table>
                    <tr>
            EOP;
    
            foreach (array_column($retests, 'biomedical') as $key => $biomedical) {
                if (! in_array($biomedical['name'], $lsitBiomedicals)) {
                    $lsitBiomedicals[] = $biomedical['name'];
    
                    $content .= <<<EOP
                        <td>
                            {$biomedical['signature']}<br>
                            <strong>{$biomedical['name']}</strong><br>
                            {$biomedical['professional_type']}<br>
                            {$biomedical['council_sigla']}/{$biomedical['state']} {$biomedical['counsil_number']}
                        </td>
                    EOP;
                }
            }
    
            $content .= <<<EOP
                    </tr>
                </table>
            EOP;
    
            PDF::writeHTML(html: $content, ln: true, fill: false, reseth: true, cell: true, align: 'C');
        }
    }

    private function generateContentAppointmentByDay(
        $appointment, $patient, $exam, string $html, $examBIomedicalId, &$biomedicalSignId, 
        &$biomedicalSignContent, $examId, $contentLong, $examsTotal, &$index, &$counter, $reTest = '', $reTestMotive = ''
    ) {
        $content = $html;

        if ($exam->filters->isEmpty()) {

            if ($reTest == '0') {
                if (preg_match('/{{(.*?)}}/', $content, $match) == 1) {
                    $content = str_replace($match, '', $content);
                }

                $results = Result::with(['parameter'])
                    ->where('appointment_id', $appointment['protocol'])
                    ->where('exam_id', $exam->id)
                    ->get();

                foreach ($results as $result) {
                    $input = "{$result->result}";

                    if (str_contains($input, '<')) {
                        $input = str_replace('<', '&lt;', $input);
                    }

                    if (str_contains($input, '>')) {
                        $input = str_replace('>', '&gt;', $input);
                    }

                    $content = str_replace($result->parameter->parameter, $input, $content);
                }

            } else {
                $motive = $reTestMotive ? $reTestMotive : 'Não informado';
                $content = "<br><br><strong>Reteste Solicitado</strong><br><strong>Motivo: {$motive}</strong>";
            }

            // Indicates if borders must be drawn around the cell. 
            // The value can be a number:0: no border (default) 1: frame</li></ul> or a string containing some or all of the following characters (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B: bottom</li></ul> or an array of line styles for each border group - for example: array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)))
            $border = 0;
            // Indicates where the current position should go after the call. Possible values are:<ul><li>0: to the right (or left for RTL language)</li><li>1: to the beginning of the next line</li><li>2: below</li></ul>
            // Putting 1 is equivalent to putting 0 and calling Ln() just after. Default value: 0.
            $line = 1;
            // Indicates if the cell background must be painted (true) or transparent (false).
            $fill = false;
            // if true reset the last cell height (default true).
            $reseth = true;
            // Allows to center or align the text. 
            // Possible values are:L : left align C : center R : right align '' : empty string : left for LTR or right for RTL
            $align = '';
            // if true, uses internal padding and automatically adjust it to account for line width.
            $autopadding = true;

            PDF::writeHTMLCell(0, 0, '', '', $content, $border, $line, $fill, $reseth, $align, $autopadding);

            // RESULTADOS ANTERIORES

            $previousResults = [];

            foreach ($exam->parameters as $parameter) {
                if ($parameter->with_previous_result == '1') {
                    $previousResultsList = DB::select(
                        "SELECT 
                            appointments.checked_at,
                            results.result,
                            new_parameter.parameter

                        FROM results

                        INNER JOIN appointments
                        ON results.appointment_id = appointments.id
                        INNER JOIN new_parameter
                        ON results.parameter_id = new_parameter.id
                        INNER JOIN users
                        ON appointments.appointment_for = users.id

                        WHERE users.id = ? AND new_parameter.id = ?
                        AND appointments.checked_at < ?
                        ORDER BY appointments.checked_at DESC LIMIT 4", [
                            $appointment['patient_id'],
                            $parameter->id,
                            $appointment['checked_at'],
                    ]);

                    foreach ($previousResultsList as $key => $value) {
                        $previousResults[$value->checked_at][] = [
                            'result' => $value->result,
                            'parameter' => $value->parameter,
                        ];
                    }
                }
            }

            if (count($previousResults) > 0) {
                $contentPrevioulsResult = <<<EOD
                    <div style="margin-bottom: 8pt;">
                        <div style="background-color: #e7e7e7; font-weight: bold; font-size: 8pt;">Resultados anteriores:</div>
                EOD;

                $indexResult = 1;

                foreach ($previousResults as $checkedAt => $items) {
                    $checkedAtFormatted = date('d/m/Y', strtotime($checkedAt));

                    $contentPrevioulsResult .= <<<EOD
                        <span style="margin-bottom: 5pt; font-size: 8pt;"><strong>{$checkedAtFormatted}:</strong></span>
                    EOD;

                    foreach ($items as $item) {
                        $newParameter = explode('##', $item['parameter'])[1];
                        $result = $item['result'];

                        $contentPrevioulsResult .= <<<EOD
                            <span style="font-size: 8pt;">{$newParameter} => {$result}&nbsp;|&nbsp;</span>
                        EOD;
                    }

                    if ($indexResult === 2) {
                        $contentPrevioulsResult .= <<<EOD
                            <br />
                        EOD;
                    }

                    $indexResult++;
                }

                $contentPrevioulsResult .= <<<EOD
                    </div>
                EOD;

                // Indicates if borders must be drawn around the cell. 
                // The value can be a number:0: no border (default) 1: frame</li></ul> or a string containing some or all of the following characters (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B: bottom</li></ul> or an array of line styles for each border group - for example: array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)))
                $border = 0;
                // Indicates where the current position should go after the call. Possible values are:<ul><li>0: to the right (or left for RTL language)</li><li>1: to the beginning of the next line</li><li>2: below</li></ul>
                // Putting 1 is equivalent to putting 0 and calling Ln() just after. Default value: 0.
                $line = 1;
                // Indicates if the cell background must be painted (true) or transparent (false).
                $fill = false;
                // if true reset the last cell height (default true).
                $reseth = true;
                // Allows to center or align the text. 
                // Possible values are:L : left align C : center R : right align '' : empty string : left for LTR or right for RTL
                $align = '';
                // if true, uses internal padding and automatically adjust it to account for line width.
                $autopadding = true;

                PDF::writeHTMLCell(0, 0, '', '', $contentPrevioulsResult, $border, $line, $fill, $reseth, $align, $autopadding);
            }

        } elseif ($exam->filters->isNotEmpty()) {

            if ($reTest == '0') {

                [$day, $month, $year] = explode('/', $appointment['registered_at']);
                $this->getFilter($exam->filters, $patient, "{$year}-{$month}-{$day}", $content);

                if (preg_match('/{{(.*?)}}/', $content, $match) == 1) {
                    $content = str_replace($match[0], '', $content);
                }

                $results = Result::with(['parameter'])
                    ->where('appointment_id', $appointment['protocol'])
                    ->where('exam_id', $exam->id)
                    ->get();

                foreach ($results as $result) {
                    $input = "{$result->result}";

                    if (str_contains($input, '<')) {
                        $input = str_replace('<', '&lt;', $input);
                    }

                    if (str_contains($input, '>')) {
                        $input = str_replace('>', '&gt;', $input);
                    }

                    $content = str_replace($result->parameter->parameter, $input, $content);
                }

            } else {
                $motive = $reTestMotive ? $reTestMotive : 'Não informado';
                $content = "<br><br><strong>Reteste Solicitado</strong><br><strong>Motivo: {$motive}</strong>";
            }

            // Indicates if borders must be drawn around the cell. 
            // The value can be a number:0: no border (default) 1: frame</li></ul> or a string containing some or all of the following characters (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B: bottom</li></ul> or an array of line styles for each border group - for example: array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)))
            $border = 0;
            // Indicates where the current position should go after the call. Possible values are:<ul><li>0: to the right (or left for RTL language)</li><li>1: to the beginning of the next line</li><li>2: below</li></ul>
            // Putting 1 is equivalent to putting 0 and calling Ln() just after. Default value: 0.
            $line = 1;
            // Indicates if the cell background must be painted (true) or transparent (false).
            $fill = false;
            // if true reset the last cell height (default true).
            $reseth = true;
            // Allows to center or align the text. 
            // Possible values are:L : left align C : center R : right align '' : empty string : left for LTR or right for RTL
            $align = '';
            // if true, uses internal padding and automatically adjust it to account for line width.
            $autopadding = true;

            PDF::writeHTMLCell(0, 0, '', '', $content, $border, $line, $fill, $reseth, $align, $autopadding);

            // RESULTADOS ANTERIORES
            $previousResults = [];

            foreach ($exam->parameters as $parameter) {
                if ($parameter->with_previous_result == '1') {
                    $previousResultsList = DB::select(
                        "SELECT 
                            appointments.checked_at,
                            results.result,
                            new_parameter.parameter

                        FROM results

                        INNER JOIN appointments
                        ON results.appointment_id = appointments.id
                        INNER JOIN new_parameter
                        ON results.parameter_id = new_parameter.id
                        INNER JOIN users
                        ON appointments.appointment_for = users.id

                        WHERE users.id = ? AND new_parameter.id = ?
                        AND appointments.checked_at < ?
                        ORDER BY appointments.checked_at DESC LIMIT 4", [
                            $appointment['patient_id'],
                            $parameter->id,
                            $appointment['checked_at'],
                    ]);

                    foreach ($previousResultsList as $key => $value) {
                        $previousResults[$value->checked_at][] = [
                            'result' => $value->result,
                            'parameter' => $value->parameter,
                        ];
                    }
                }
            }

            if (count($previousResults) > 0) {
                $contentPrevioulsResult = <<<EOD
                    <div style="margin-bottom: 8pt;">
                        <div style="background-color: #e7e7e7; font-weight: bold; font-size: 8pt;">Resultados anteriores:</div>
                EOD;

                $indexResult = 1;

                foreach ($previousResults as $checkedAt => $items) {
                    $checkedAtFormatted = date('d/m/Y', strtotime($checkedAt));

                    $contentPrevioulsResult .= <<<EOD
                        <span style="margin-bottom: 5pt; font-size: 8pt;"><strong>{$checkedAtFormatted}:</strong></span>
                    EOD;

                    foreach ($items as $item) {
                        $newParameter = explode('##', $item['parameter'])[1];
                        $result = $item['result'];

                        $contentPrevioulsResult .= <<<EOD
                            <span style="font-size: 8pt;">{$newParameter} => {$result}&nbsp;|&nbsp;</span>
                        EOD;
                    }

                    if ($indexResult === 2) {
                        $contentPrevioulsResult .= <<<EOD
                            <br />
                        EOD;
                    }

                    $indexResult++;
                }

                $contentPrevioulsResult .= <<<EOD
                    </div>
                EOD;

                // Indicates if borders must be drawn around the cell. 
                // The value can be a number:0: no border (default) 1: frame</li></ul> or a string containing some or all of the following characters (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B: bottom</li></ul> or an array of line styles for each border group - for example: array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)))
                $border = 0;
                // Indicates where the current position should go after the call. Possible values are:<ul><li>0: to the right (or left for RTL language)</li><li>1: to the beginning of the next line</li><li>2: below</li></ul>
                // Putting 1 is equivalent to putting 0 and calling Ln() just after. Default value: 0.
                $line = 1;
                // Indicates if the cell background must be painted (true) or transparent (false).
                $fill = false;
                // if true reset the last cell height (default true).
                $reseth = true;
                // Allows to center or align the text. 
                // Possible values are:L : left align C : center R : right align '' : empty string : left for LTR or right for RTL
                $align = '';
                // if true, uses internal padding and automatically adjust it to account for line width.
                $autopadding = true;

                PDF::writeHTMLCell(0, 0, '', '', $contentPrevioulsResult, $border, $line, $fill, $reseth, $align, $autopadding);
            }

        }    
        
        $biomedical = Biomedical::firstWhere('user_id', $examBIomedicalId);
        $signature = $this->getSignature($biomedical->signature);

        if (! in_array($examBIomedicalId, $biomedicalSignId)) {
            $biomedicalSignId[] = $examBIomedicalId;
            $biomedicalSignContent[] = [
                'signature' => $signature,
                'name' => $biomedical->user->first_name,
                'council_sigla' => $biomedical->council->sigla,
                'state' => $biomedical->state->uf,
                'counsil_number' => $biomedical->counsil_number,
                'professional_type' => $biomedical->professional?->name,
            ];
        }

        $this->renderCheckedByRoutine($biomedical->user->first_name);

        if (in_array($examId, $contentLong) && ($examsTotal === ($index + 1))) {
            $this->renderSignaturesPageCurrentRoutine($biomedicalSignContent, $biomedicalSignId);
            $counter = 5;
        }

        if (in_array($examId, $contentLong) && ($examsTotal !== ($index + 1))) {
            $this->renderSignaturesPageCurrentRoutine($biomedicalSignContent, $biomedicalSignId);

            $counter = 0;
            PDF::writeHTML(html: '<br pagebreak="true"/>', ln: true, fill: false, reseth: true, cell: false, align: '');
        }

        if ($counter >= 4 && ($examsTotal !== ($index + 1))) {
            $this->renderSignaturesPageCurrentRoutine($biomedicalSignContent, $biomedicalSignId);

            $counter = 0;
            PDF::writeHTML('<br pagebreak="true"/>', true, false, true, false, '');
        }
        
        if ($counter <= 4 && ($examsTotal === ($index + 1))) {
            $this->renderSignaturesPageCurrentRoutine($biomedicalSignContent, $biomedicalSignId);
        }
                     
    }

    private function getSignature($uriSignature)
    {
        if ($uriSignature) {
            $signature = public_path("storage/images/users/signature/{$uriSignature}");

            return <<<EOD
                <img src="{$signature}" width="85" height="45" /> 
            EOD;
        }

        $signature = public_path("assets/images/users/no-signature.png");

        return <<<EOD
            <img src="{$signature}" width="85" height="45" /> 
        EOD;
    }

    private function renderCheckedBy(string $biomedicalName, Exam $exam, array &$retests, array $examsIds = [])
    {
        if ($exam->pivot->re_test == '0' && $exam->pivot->status != '2') {
            PDF::SetFont('courier', '', 8);

            $checkedBy = <<<EOP
                CONFERIDO E LIBERADO POR: {$biomedicalName} <br>
            EOP;
    
            PDF::writeHTML(html: $checkedBy, ln: true, fill: false, reseth: true, cell: true, align: 'R');

        // CASO NO ARRAY TENHA COLESTEROL TOTAL E FRACOES E SEJA IGUAL AO EXAME TRIGLICERIDES
        } else if (in_array(186, $examsIds) && $exam->id === 698) {
            // CONTINUE
        } else {
            $checkedBy = <<<EOP
                CONFERIDO E LIBERADO POR: {$biomedicalName} <br>
            EOP;

            $retests[$exam->id]['checked_by'] = $checkedBy;
        }
    }

    private function renderCheckedByRoutine(string $biomedicalName)
    {
        PDF::SetFont('courier', '', 8);

        $checkedBy = <<<EOP
            CONFERIDO E LIBERADO POR: {$biomedicalName} <br>
        EOP;

        PDF::writeHTML(html: $checkedBy, ln: true, fill: false, reseth: true, cell: true, align: 'R');
    }

    private function renderSignaturesPageCurrent(&$biomedicalSignContent, &$biomedicalSignId, $exam)
    {
        if ($exam->pivot->re_test == '0' && $exam->pivot->status != '2') {
            $content = <<<EOP
                <table>
                    <tr>
            EOP;

            foreach ($biomedicalSignContent as $biomedical) {
                $content .= <<<EOP
                    <td>
                        {$biomedical['signature']}<br>
                        <strong>{$biomedical['name']}</strong><br>
                        {$biomedical['professional_type']}<br>
                        {$biomedical['council_sigla']}/{$biomedical['state']} {$biomedical['counsil_number']}
                    </td>
                EOP;
            }

            $content .= <<<EOP
                    </tr>
                </table>
            EOP;

            $biomedicalSignContent = [];
            $biomedicalSignId = [];
        
            PDF::writeHTML(html: $content, ln: true, fill: false, reseth: true, cell: true, align: 'C');
        }
    }

    private function renderSignaturesPageCurrentRoutine(&$biomedicalSignContent, &$biomedicalSignId)
    {
        $content = <<<EOP
            <table>
                <tr>
        EOP;

        foreach ($biomedicalSignContent as $biomedical) {
            $content .= <<<EOP
                <td>
                    {$biomedical['signature']}<br>
                    <strong>{$biomedical['name']}</strong><br>
                    {$biomedical['professional_type']}<br>
                    {$biomedical['council_sigla']}/{$biomedical['state']} {$biomedical['counsil_number']}
                </td>
            EOP;
        }

        $content .= <<<EOP
                </tr>
            </table>
        EOP;

        $biomedicalSignContent = [];
        $biomedicalSignId = [];
    
        PDF::writeHTML(html: $content, ln: true, fill: false, reseth: true, cell: true, align: 'C');
    }

    private function getFilter($filters, $patient, $registeredAt, &$content) 
    {
        $patientAgeYear = $patient->ageYear($registeredAt);
        $patientAgeMonth = $patient->ageMonth($registeredAt);
        $patientAgeDay = $patient->ageDay($registeredAt);

        foreach ($filters as $filter) {

            // SEXO FEMININO (RECÉM-NASCIDO)
            if (
                ($patientAgeYear <= 0) && ($patientAgeMonth <= 0) &&
                ($patientAgeDay >= $filter->intial_age_day && $patientAgeDay <= $filter->final_age_day) &&
                (($patient->gender == 'Female' && $filter->gender == 'F') || ($patient->gender == 'Female' && $filter->gender == 'A'))
            ) {
                $content = $filter->exam_editor;
                break;

            // SEXO MASCULINO (RECÉM-NASCIDO)
            } elseif (
                (($patientAgeYear <= 0) && ($patientAgeMonth <= 0)) &&
                (($patientAgeDay >= $filter->intial_age_day) && ($patientAgeDay <= $filter->final_age_day)) &&
                (($patient->gender == 'Male' && $filter->gender == 'M') || ($patient->gender == 'Male' && $filter->gender == 'A'))
            ) {
                $content = $filter->exam_editor;
                break;
                
            // CRIANÇAS, JOVENS E ADULTOS - SEXO FEMININO
            } elseif (
                ($patientAgeYear >= $filter->intial_age_year && $patientAgeYear <= $filter->final_age_year) &&
                ( ((($patientAgeYear * 12) + $patientAgeMonth) >= (($filter->intial_age_year * 12) + $filter->intial_age_month)) && ((($patientAgeYear * 12) + $patientAgeMonth) <= (($filter->final_age_year * 12) + $filter->final_age_month)) ) &&
                (($patient->gender == 'Female' && $filter->gender == 'F') || ($patient->gender == 'Female' && $filter->gender == 'A'))
            ) {
                $content = $filter->exam_editor;
                break;

            // CRIANÇAS, JOVENS E ADULTOS - SEXO MASCULINO
            } elseif (
                ($patientAgeYear >= $filter->intial_age_year && $patientAgeYear <= $filter->final_age_year) &&
                ( ((($patientAgeYear * 12) + $patientAgeMonth) >= (($filter->intial_age_year * 12) + $filter->intial_age_month)) && ((($patientAgeYear * 12) + $patientAgeMonth) <= (($filter->final_age_year * 12) + $filter->final_age_month)) ) &&
                (($patient->gender == 'Male' && $filter->gender == 'M') || ($patient->gender == 'Male' && $filter->gender == 'A'))
            ) {
                $content = $filter->exam_editor;
                break;
            }
            
        }
    }

}
