<?php

namespace App\Http\Controllers;

use App\Models\Appointment\Appointment;
use App\Models\User;
use App\Models\Receptionist;
use App\Helpers\Pagination;
use App\Models\ReceptionListDoctor;
use App\Http\Requests\ReceptionistRequest;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class ReceptionistController extends Controller
{
    public function index()
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        $limit = Pagination::getLimit();
        $receptionists = Receptionist::getUsersPaginate($limit);

        if ($role == 'doctor') {
            $receptionistDoctor = ReceptionListDoctor::where('doctor_id', $user->id)->pluck('reception_id');
            $receptionists = User::with('roles')->whereIn('id', $receptionistDoctor)->paginate($limit);
        }

        return view('receptionists.index', 
            compact('user', 'role', 'receptionists', 'limit')
        );
    }

    public function create()
    {
        return view('receptionists.create');
    }

    public function store(ReceptionistRequest $request)
    {
        try {
            $request->merge(['profile_photo' => Receptionist::saveImage($request)]);

            $userId = User::createUserReceptionist($request);
            Receptionist::create($request->all() + ['user_id' => $userId]);
            session()->put('success', $request->message);

            return redirect()->route('receptionists.index');
        } catch (\Exception $error) {
            return redirect()
                ->name('receptionists.index')
                ->withError('Algo deu errado! ' . $error->getMessage());
        }
    }

    public function show(User $receptionist)
    {
        $receptionist = User::findByReceptionistId($receptionist->id);

        if (! $receptionist) {
            return redirect('/')->withError('Recepcionista não encontrado');
        }

        $limit = Pagination::getLimit();
        $appointments = Appointment::byReceptionistId($receptionist->id, $limit);
        $data = Appointment::totalByReceptionist($receptionist->id);
            
        return view('receptionists.show', 
            compact('receptionist', 'appointments', 'data', 'limit')
        );
    }

    public function edit(User $receptionist)
    {
        $receptionist = User::findByReceptionistId($receptionist->id);
        $receptionist_info = Receptionist::firstWhere('user_id', $receptionist->id);

        if (! $receptionist) {
            return redirect('/')->withError('Recepcionista não encontrado');     
        }

        return view('receptionists.edit', 
            compact('receptionist', 'receptionist_info')
        );
    }

    public function update(ReceptionistRequest $request, User $receptionist)
    {
        try {
            $user = Sentinel::getUser();
            $role = $user->roles[0]->slug;

            Receptionist::updateDataToUser($receptionist, $request, $user->id);
            Receptionist::updateData($receptionist, $request);
            session()->put('success', $request->message);
                    
            if ($role == 'receptionist') {
                return redirect('/')->withSuccess('Recepcionista alterado com sucesso!');
            }
                
            return redirect()->route('receptionists.index');
        } catch (\Exception $error) {
            return redirect()
                ->name('receptionists.index')
                ->withError('Algo deu errado! ' . $error->getMessage());
        }
    }

}
