<?php

namespace App\Http\Controllers\Appointment;

use App\Models\Appointment\Appointment;
use App\Helpers\Fill;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function byDate(Request $request)
    {
        return response()->json([
            'appointments' => Appointment::searchBy('date', $request->date)
        ]);
    }

    public function byProtocol(Request $request)
    {
        return response()->json([
            'appointments' => Appointment::searchBy('protocol', $request->protocol)
        ]);
    }

    public function byPatient(Request $request)
    {
        return response()->json([
            'appointments' => Appointment::searchBy('patient', $request->patient)
        ]);
    }

    public function searchPatient(Appointment $appointment)
    {
        $patientObject = Patient::firstWhere('user_id', $appointment->patient->id);

        [$year, $month, $day] = explode('-', $patientObject->dob);
        $age = Carbon::createFromDate($year, $month, $day)
            ->diff($appointment->appointment_date)
            ->format('%y anos, %m meses, %d dias');

        $patient = [];
        $patient['protocol'] = $appointment->id;
        $patient['name'] = $appointment->patient->first_name;
        $patient['cpf'] = Fill::maskCpf($patientObject->patient_cpf);
        $patient['cns'] = Fill::maskCns($patientObject->cns);
        $patient['age'] = $age;

        $appointment->exams->each(function ($exam) use (&$patient) {
            $patient['exams'][] = [
                'name' => $exam->name,
                'status' => $exam->pivot->status,
            ];
        });

        return response()->json([
            'patient' => $patient
        ]);
    }

}
