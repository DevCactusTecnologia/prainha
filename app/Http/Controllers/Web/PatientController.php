<?php

namespace App\Http\Controllers\Web;

use App\Models\Patient;
use App\Models\Appointment\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\KeyAccessRequest;
use App\Models\Exam\Exam;
use App\Models\Exam\Model;
use Illuminate\Support\Facades\Crypt;
use App\Traits\ResultExam\ConfigPdf;
use App\Traits\ResultExam\ContentPdf;
use App\Traits\ResultExam\FooterPdf;
use App\Traits\ResultExam\HeaderPdf;
use Illuminate\Contracts\Encryption\DecryptException;
use PDF;

class PatientController extends Controller
{
    use ConfigPdf, HeaderPdf, FooterPdf, ContentPdf;

    public function index(string $cpf, string $cns)
    {
        $cpfDecoded = base64_decode($cpf);
        $cnsDecoded = base64_decode($cns);

        $patient = Patient::where('patient_cpf', $cpfDecoded)
            ->orWhere('cns', $cnsDecoded)
            ->first();

        if (! $patient) {
            return view('web.patients.not-found');
        }

        $appointments = Appointment::where('appointment_for', $patient->user_id)
            ->orderBy('appointment_date', 'DESC')
            ->get();

        if ($appointments->isEmpty()) {
            return view('web.patients.appointment-not-found');
        }

        return view('web.patients.index', 
            compact('patient', 'appointments')
        );
    }

    public function generatePdf(string $token)
    {
        try {
            $id = Crypt::decrypt($token);
        } catch (DecryptException $error) {
            return view('web.patients.token-invalid');
        }

        $appointment = Appointment::with(['exams', 'results'])->firstWhere('id', $id);
        $patient = Patient::firstWhere('user_id', $appointment->patient->id);

        $this->config();
        $this->header($appointment, $patient);
        $this->footer();

        PDF::AddPage();

        $contentLong = Exam::listContentLong();
        [$examsContentLong, $examsContentSmall] = $appointment->exams
            ->filter(fn ($exam) => $exam->pivot->status == '1')
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
            $this->addHeaderExam($exam, $retests);
            $examEditor = Model::find($exam->model_id)->exam_editor;

            PDF::SetFont('courier', '', 9);
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

            // $this->addBiomedicalSignature($exam->id, $exam->pivot->biomedical_id, $contentLong, $counter, $examsTotal, $index);
        }

        PDF::Output("resultado-exame-protocolo-{$id}.pdf", 'I');
    }

    public function searchIndex()
    {
        return view('web.patients.search');
    }

    public function search(KeyAccessRequest $request)
    {
        return redirect()->route('patient.result.show', [
            'id' => Crypt::encrypt($request->access_key)
        ]);
    }

    public function show(string|null $id)
    {
        try {
            $accessKey = Crypt::decrypt($id);
        } catch (DecryptException $error) {
            return view('web.patients.token-invalid');
        }

        $appointment = Appointment::firstWhere('access_key', $accessKey);

        if (! $appointment) {
            return redirect()
                ->route('patient.result.search.index')
                ->withInput(request()->all())
                ->withErrors('<strong>Chave de acesso</strong> não encontrada!');
        }

        $appointments = Appointment::where('appointment_for', $appointment->patient->id)
            ->orderBy('appointment_date', 'DESC')
            ->get();

        if ($appointments->isEmpty()) {
            return view('web.patients.appointment-not-found');
        }

        $patient = Patient::firstWhere('user_id', $appointment->patient->id);

        return view('web.patients.index', 
            compact('patient', 'appointments')
        );
    }
}
