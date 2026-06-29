<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment\Appointment;
use App\Helpers\Pagination;
use Illuminate\Http\Request;
use App\Http\Requests\PatientRequest;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{
    public function index()
    {
        $limit = Pagination::getLimit();
        $patients = Patient::getUsersPaginate($limit);
        
        return view('patients.index', 
            compact('patients', 'limit')
        );
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(PatientRequest $request)
    {
        try {
            $request->merge(['profile_photo' => Patient::saveImage($request)]);

            $userId = User::createUserPatient($request);
            Patient::create($request->all() + ['user_id' => $userId]);
            session()->put('success', $request->message);

            return redirect()->route('patients.index');
        } catch (\Exception $error) {
            return redirect()
                ->route('patients.index')
                ->withError('Algo deu errado! ' . $error->getMessage());
        }
    }

    public function show(User $patient)
    {
        $patient = User::findByPatientId($patient->id);

        if (! $patient) {
            return redirect('/')->withError('Paciente não encontrado');
        }

        $patient_info = Patient::firstWhere('user_id', $patient->id);
        $limit = Pagination::getLimit();
        $appointments = Appointment::byPatientId($patient->id, $limit);
        $data = Appointment::totalByPatient($patient->id);

        return view('patients.show', 
            compact('patient', 'patient_info', 'data', 'appointments')
        );
    }

    public function edit(User $patient)
    {
        $patient_info = Patient::firstWhere('user_id', $patient->id);

        return view('patients.edit', 
            compact('patient', 'patient_info')
        );
    }

    public function update(PatientRequest $request, User $patient)
    {
        try {
            $user = Sentinel::getUser();
            $role = $user->roles[0]->slug;

            Patient::updateDataToUser($patient, $request, $user->id);
            Patient::updateData($patient, $request);
            session()->put('success', $request->message);
                
            if ($role == 'patient') {
                return redirect('/')->with('success', 'Perfil atualizado com sucesso!');
            }
                
            return redirect()->route('patients.index');
        } catch (\Exception $error) {
            return redirect()
                ->route('patients.index')
                ->withError('Algo deu errado! ' . $error->getMessage());
        }
    }

    public function search(Request $request)
    {
        return response()->json([
            'patients' => Patient::search($request->name, 'all') 
        ]);
    }

    public function searchAppointmentPatient(Request $request)
    {
        return response()->json([
            'patients' => Patient::search($request->filter, 'active') 
        ]);
    }

    public function storeAppointment(PatientRequest $request): JsonResponse
    {
        $userId = User::createUserPatient($request);
        $patient = Patient::create($request->all() + ['user_id' => $userId]);

        return response()->json([
            'patient' => [
                'id' => $patient->user->id,
                'name' => $patient->user->first_name,
            ],
            'message' => 'Paciente adicionado com sucesso!',
        ]);
    }
}
