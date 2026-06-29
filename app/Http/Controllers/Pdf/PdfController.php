<?php

namespace App\Http\Controllers\Pdf;

use App\Enums\Routine\StageEnum;
use App\Models\Appointment\Appointment;
use App\Http\Controllers\Controller;
use App\Models\Exam\Exam;
use App\Models\Exam\Model;
use App\Models\Patient;
use App\Traits\ResultExam\ConfigPdf;
use App\Traits\ResultExam\ContentPdf;
use App\Traits\ResultExam\FooterPdf;
use App\Traits\ResultExam\HeaderPdf;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\DB;
use PDF;

class PdfController extends Controller
{
    use ConfigPdf, HeaderPdf, FooterPdf, ContentPdf;

    public function generate(int|null $id)
    {
        $appointment = Appointment::with(['exams', 'results'])->firstWhere('id', $id);

        foreach ($appointment->exams as $exam) {
            if ($exam->pivot->status == '1') {
                DB::table('routine_traceabilities')
                    ->insert([
                        'appointment_id' => $id,
                        'exam_id' => $exam->id,
                        'stage_id' => StageEnum::PRINTER->value,
                        'user_id' => Sentinel::getUser()->id,
                        'registered_at' => date('Y-m-d H:i:s'),
                    ]);
            }
        }

        if (! $appointment) {
            return abort(404);
        }

        $patient = Patient::firstWhere('user_id', $appointment->appointment_for);

        $this->config();
        $this->header($appointment, $patient);
        $this->footer();

        PDF::AddPage();

        $contentLong = Exam::listContentLong();
        [$examsContentLong, $examsContentSmall] = $appointment->exams
            ->filter(fn ($exam) => 
                ($exam->pivot->status == '1') || 
                ($exam->pivot->re_test == '1' && $exam->pivot->status == '1') || 
                ($exam->pivot->re_test == '1' && $exam->pivot->status == '2') ||
                ($exam->pivot->re_test == '0' && $exam->pivot->status == '2')
            )
            ->partition(fn ($exam) => in_array($exam->id, $contentLong));

        $exams = $examsContentLong->merge($examsContentSmall);
        $examsIds = $exams->pluck('id')->toArray();

        $examsTotal = 0;
        foreach ($exams as $index => $exam) {
            if ($exam->pivot->re_test == '0' && $exam->pivot->status != '2') {
                $examsTotal++;
            }
        }

        $counter = 0;
        $biomedicalSignId = [];
        $biomedicalSignContent = [];
        $retests = [];
        $indexExam = 0;

        foreach ($exams as $index => $exam) {
            if ($exam->pivot->re_test == '0' && $exam->pivot->status != '2') {
                $counter++;
                $indexExam++;
            }

            $this->addHeaderExam($exam, $retests, $examsIds);

            PDF::SetFont('courier', '', 9);
            $examEditor = Model::find($exam->pivot->model_id)->exam_editor;

            $this->generateContent(
                $appointment, 
                $exam, 
                $patient, 
                $examEditor, 
                $exam->id, 
                $exam->pivot->biomedical_id, 
                $contentLong, 
                $counter, 
                $examsTotal, 
                $indexExam,
                $biomedicalSignId, 
                $biomedicalSignContent,
                $retests,
                $examsIds,
            );

            // $this->addBiomedicalSignature($exam->id, $exam->pivot->biomedical_id, $contentLong, $counter, $examsTotal, $index);
        }

        $this->renderRetests($retests);

        PDF::Output("resultado-exame-protocolo-{$id}.pdf", 'I');
    }

    public function single(Appointment $appointment, Exam $exam)
    {
        $patient = Patient::firstWhere('user_id', $appointment->appointment_for);

        DB::table('routine_traceabilities')
            ->insert([
                'appointment_id' => $appointment->id,
                'exam_id' => $exam->id,
                'stage_id' => StageEnum::PRINTER->value,
                'user_id' => Sentinel::getUser()->id,
                'registered_at' => date('Y-m-d H:i:s'),
            ]);

        $this->config();
        $this->header($appointment, $patient);
        $this->footer();

        PDF::AddPage();
    
        $contentLong = Exam::listContentLong();
        [$examsContentLong, $examsContentSmall] = $appointment->exams
            ->filter(function ($examCurrent) use ($exam) {
                return $examCurrent->id == $exam->id;
            })
            ->partition(fn ($exam) => in_array($exam->id, $contentLong));

        $exams = $examsContentLong->merge($examsContentSmall);
        $examsTotal = $exams->count();
        $counter = 0;
        $biomedicalSignId = [];
        $biomedicalSignContent = [];
        $retests = [];
        $indexExam = 0;

        foreach ($exams as $index => $exam) {
            $counter++;
            $indexExam++;
            $this->addHeaderExam($exam, $retests);

            PDF::SetFont('courier', '', 9);
            $examEditor = Model::find($exam->pivot->model_id)->exam_editor;

            $this->generateContent(
                $appointment, 
                $exam, 
                $patient, 
                $examEditor,
                $exam->id, 
                $exam->pivot->biomedical_id, 
                $contentLong, 
                $counter, 
                $examsTotal, 
                $indexExam, 
                $biomedicalSignId, 
                $biomedicalSignContent,
                $retests,
            );
        }

        PDF::Output("resultado-protocolo-{$appointment->id}-exame-{$exam->abbreviation}.pdf", 'I');
    }
}
