<?php

namespace App\Http\Controllers\Routine;

use App\Models\Appointment\Appointment;
use App\Http\Controllers\Controller;
use App\Models\Unity;
use App\Services\Routine\TagService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use PDF;

class TagController extends Controller
{
    public function __construct(
        private readonly TagService $service
    ) {}

    public function index(): View
    {
        return view('routine.tag.index', [
            'unitys' => Unity::active()->get()
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json([
            'tags' => $this->service->total($request)
        ]);
    }

    public function print(Appointment $appointment)
    {
        $this->config();

        PDF::setHeaderCallback(function ($pdf) use ($appointment) {
            $this->writePatientName($pdf, $appointment);
            $this->writePatientComplement($pdf, $appointment);
            $this->drawBarcode($pdf, $appointment);
        });
        
        foreach ($this->abbreviations($appointment) as $item) {
            $dimension = [49, 30];
            PDF::AddPage('L', $dimension, false, false);
            PDF::SetXY(1, 25);

            $results = implode(' ', $item['abreviation']);
            PDF::Cell(0, 0, $results, 0, $ln = 0, 'L', 0, '', 0, false, 'T', 'C');
        }
        
        PDF::Output("etiqueta-protocolo-{$appointment->id}.pdf", 'I');
    }

    private function config(): void
    {
        PDF::SetCreator('TCPDF');
        PDF::SetAuthor('SisLac');
        PDF::SetTitle('Etiquetas');
        PDF::SetSubject('Etiquetas dos exames');
        PDF::SetKeywords('etiqueta, exame, atendimento, sislac');
        PDF::SetFont('courier', '', 7);
        PDF::SetAutoPageBreak(true, 1);
    }

    private function writePatientName(&$pdf, Appointment $appointment): void
    {
        $pdf->SetFont('courier', '', 7);
        $patient = Str::limit($appointment->patient?->first_name, 28);
        $pdf->MultiCell(w: 49, h: 0, txt: $patient, border: 0, align: 'L', fill: false, ln: 1, x: 1, y: 0, reseth: false, stretch: 0, ishtml: false);
    }

    private function writePatientComplement(&$pdf, Appointment $appointment): void
    {
        $complement = $this->complement($appointment);
        $pdf->MultiCell(w: 49, h: 0, txt: $complement, border: 0, align: 'L', fill: false, ln: 1, x: 1, y: 2, reseth: false, stretch: 0, ishtml: false);
    }

    private function complement(Appointment $appointment)
    {
        $shortDate = $appointment->created_at->format('d/m/y - H:i\H\r\s');
        $shortUnity = "UN:{$appointment->unity?->sigla}";

        $shortAge = '';
        $patientDob = $appointment->patient?->patient?->dob ?: '';

        if ($patientDob && str_contains($patientDob, '-')) {
            [$year, $month, $day] = explode('-', $patientDob);

            $shortAge = Carbon::createFromDate($year, $month, $day)
                ->diff($appointment->created_at->format('Y-m-d H:i:s'))
                ->format('%y');

            if ((int) $shortAge >= 1) {
                $shortAge = Carbon::createFromDate($year, $month, $day)
                    ->diff($appointment->created_at->format('Y-m-d H:i:s'))
                    ->format('%yA');
            } else {
                $shortAge = Carbon::createFromDate($year, $month, $day)
                    ->diff($appointment->created_at->format('Y-m-d H:i:s'))
                    ->format('%mM %dD');
            }
        }

        return "{$shortDate} {$shortAge} {$shortUnity}";
    }

    private function drawBarcode(&$pdf, Appointment $appointment): void
    {
        $protocol = $appointment->id;
        $style = array(
            'position' => 'C',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => false,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false,
            'text' => false,
            'font' => 'courier',
            'fontsize' => 7,
            'stretchtext' => 0
        );

        $pdf->write1DBarcode(code: $protocol, type: 'CODABAR', x: 0, y: 6, w: 49, h: 15, xres: 0.4, style: $style, align: 'N');
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 8);
        $pdf->MultiCell(w: 49, h: 0, txt: $protocol, border: 0, align: 'C', fill: false, ln: 0, x: 1, y: 19, reseth: false, stretch: 0, ishtml: true);
        $pdf->SetFont('courier', '', 7);
    }

    private function abbreviations(Appointment $appointment): array
    {
        $registers = [];

        foreach ($appointment->exams as $exam) {
            $group = $exam->label_group ?: 0;
            $registers[$group]['abreviation'][] = $exam->abbreviation;
        }

        return $registers;
    }

    public function printByUnity(Unity $unity, string $dateStart, string $dateEnd)
    {
        $this->config();

        $registers = $this->service->getItems($unity, $dateStart, $dateEnd);

        foreach ($registers as $register) {
            
            foreach ($register['tag_group'] as $key => $abbreviation) {

                $dimension = [49, 30];
                PDF::AddPage('L', $dimension, false, false);
                
                $patient = Str::limit($register['patient']['name'], 28);
                PDF::MultiCell(w: 49, h: 0, txt: $patient, border: 0, align: 'L', fill: false, ln: 0, x: 1, y: 1, reseth: false, stretch: 0, ishtml: false);

                $shortDate = date('d/m/y - H:i\H\r\s', strtotime($register['registered_at']));
                $shortUnity = 'UN:' . $register['unity_sigla'];

                $shortAge = '';
                $patientDob = $register['patient']['dob'] ?: '';

                if ($patientDob && str_contains($patientDob, '-')) {
                    [$year, $month, $day] = explode('-', $patientDob);

                    $shortAge = Carbon::createFromDate($year, $month, $day)
                        ->diff($register['registered_at'])
                        ->format('%y');

                    if ((int) $shortAge >= 1) {
                        $shortAge = Carbon::createFromDate($year, $month, $day)
                            ->diff($register['registered_at'])
                            ->format('%yA');
                    } else {
                        $shortAge = Carbon::createFromDate($year, $month, $day)
                            ->diff($register['registered_at'])
                            ->format('%mM %dD');
                    }
                }

                $complement = "{$shortDate} {$shortAge} {$shortUnity}";
                PDF::MultiCell(w: 49, h: 0, txt: $complement, border: 0, align: 'L', fill: false, ln: 0, x: 1, y: 3, reseth: false, stretch: 0, ishtml: false);

                $protocol = $register['protocol'];
                $style = array(
                    'position' => 'C',
                    'align' => 'C',
                    'stretch' => false,
                    'fitwidth' => false,
                    'cellfitalign' => '',
                    'border' => false,
                    'hpadding' => 'auto',
                    'vpadding' => 'auto',
                    'fgcolor' => array(0,0,0),
                    'bgcolor' => false,
                    'text' => true,
                    'font' => 'helvetica',
                    'fontsize' => 8,
                    'stretchtext' => 0
                );

                PDF::write1DBarcode(code: $protocol, type: 'CODABAR', x: 0, y: 8, w: 49, h: 18, xres: 0.4, style: $style, align: 'N');
                PDF::Ln();

                $listAbbreviation = implode(', ', $abbreviation);
                PDF::SetXY(1, 25);
                PDF::Cell(0, 0, $listAbbreviation, 0, $ln = 0, 'L', 0, '', 0, false, 'T', 'C');
            }

        }
        
        PDF::Output("etiquetas-por-unidade.pdf", 'I');
    }
}
