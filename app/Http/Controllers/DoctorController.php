<?php

namespace App\Http\Controllers;

use App\Models\Appointment\Appointment;
use App\Models\Doctor;
use App\Helpers\Pagination;
use App\Http\Requests\DoctorRequest;
use App\Models\ClassCouncil;
use App\Models\State;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(): View
    {
        return view('doctors.index', [
            'doctors' => Doctor::getUsersPaginate()
        ]);
    }

    public function create(): View
    {
        return view('doctors.create', [
            'classCouncils' => ClassCouncil::all(),
            'states' => State::all(),
        ]);
    }

    public function store(DoctorRequest $request): RedirectResponse
    {   
        try {
            $request->merge(['profile_photo' => Doctor::saveImage($request)]);

            $userId = User::createUserDoctor($request);
            Doctor::create($request->all() + ['user_id' => $userId]);
            session()->put('success', $request->message);

            return redirect()->route('doctors.index');
        } catch (\Exception $error) {
            return redirect()
                ->route('doctors.index')
                ->withError('Algo deu errado!!! ' . $error->getMessage());
        }
    }

    public function show(User $doctor): View|RedirectResponse
    {
        $doctor = User::findByDoctorId($doctor->id);
        $doctor_info = Doctor::firstWhere('user_id', $doctor->id);

        if (! $doctor || ! $doctor_info) {
            return redirect('/')->withError('Detalhes médicos não encontrados');
        }

        $limit = Pagination::getLimit();
        $appointments = Appointment::byDoctorId($doctor->id, $limit);
        $prescriptions = Prescription::byDoctorId($doctor->id, $limit);
        $data = Appointment::totalByDoctor($doctor->id);

        return view('doctors.show', 
            compact('doctor', 'doctor_info', 'data', 'appointments', 'prescriptions', 'limit')
        );
    }

    public function edit(User $doctor): View
    {
        $doctor = User::findByDoctorId($doctor->id);
        $doctor_info = Doctor::firstWhere('user_id', $doctor->id);
        $classCouncils = ClassCouncil::all();
        $states = State::all();
        
        return view('doctors.edit', 
            compact('doctor', 'doctor_info', 'classCouncils', 'states')
        );
    }

    public function update(DoctorRequest $request, User $doctor): RedirectResponse
    {
        try {
            $user = Sentinel::getUser();
            $role = $user->roles[0]->slug;
            
            Doctor::updateDataToUser($doctor, $request, $user->id);
            Doctor::updateData($doctor, $request);
            session()->put('success', $request->message);

            if ($role == 'doctor') {
                return redirect('/')->withSuccess('Perfil atualizado com sucesso!');
            }

            return redirect()->route('doctors.index');
        } catch (\Exception $error) {
            return redirect()
                ->route('doctors.index')
                ->withError('Algo deu errado!!! ' . $error->getMessage());
        }
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json([
            'doctors' => Doctor::search($request->name)
        ]);
    }

    public function storeAppointment(DoctorRequest $request): JsonResponse
    {
        $userId = User::createUserDoctor($request);
        $doctor = Doctor::create($request->all() + ['user_id' => $userId]);

        return response()->json([
            'doctor' => [
                'id' => $doctor->user->id,
                'name' => $doctor->user->first_name,
            ],
            'message' => 'Solicitante adicionado com sucesso!',
        ]);
    }
}
