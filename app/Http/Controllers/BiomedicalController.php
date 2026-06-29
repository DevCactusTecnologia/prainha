<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment\Appointment;
use App\Models\Biomedical;
use App\Models\State;
use App\Helpers\Pagination;
use App\Models\ClassCouncil;
use App\Http\Requests\BiomedicalRequest;
use App\Models\ProfessionalType;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class BiomedicalController extends Controller
{
    public function index()
    {
        $biomedicalRole = Sentinel::findRoleBySlug('biomedical');
        $biomedicals = $biomedicalRole->users()
            ->with(['roles'])
            ->orderByDesc('id')
            ->get();

        return view('biomedicals.index', 
            compact('biomedicals')
        );
    }

    public function create()
    {
        $classCouncils = ClassCouncil::all();
        $states = State::all();
        $professionals = ProfessionalType::active()->get();

        return view('biomedicals.create', 
            compact('classCouncils', 'states', 'professionals')
        );
    }

    public function store(BiomedicalRequest $request)
    {  
        try {
            $request->merge(['profile_photo' => Biomedical::saveProfileImage($request)]);
            $request->merge(['signature' => Biomedical::saveSignatureImage($request)]);

            $userId = User::createUserBiomedical($request);
            Biomedical::create($request->all() + ['user_id' => $userId]);
            session()->put('success', $request->message);
            
            return redirect()->route('biomedicals.index');
        } catch (\Exception $error) {
            return redirect()
                ->route('biomedicals.index')
                ->withError('Algo deu errado! ' . $error->getMessage());
        }
    }

    public function show(User $biomedical)
    {
        $biomedical = User::findByBiomedicalId($biomedical->id);

        if (! $biomedical) {
            return redirect('/')->withError('Analista não encontrado');
        }

        $limit = Pagination::getLimit();
        $data = Appointment::totalByBiomedical($biomedical->id);
        $appointments = Appointment::byBiomedicalId($biomedical->id, $limit);
            
        return view('biomedicals.show', 
            compact('biomedical', 'data', 'appointments', 'limit')
        );
    }

    public function edit(User $biomedical)
    {
        $biomedical = User::findByBiomedicalId($biomedical->id);

        if (! $biomedical) {
            return redirect('/')->withError('Analista não encontrado'); 
        }

        $biomedical_info = Biomedical::firstWhere('user_id', $biomedical->id);
        $classCouncils = ClassCouncil::all();
        $states = State::all();
        $professionals = ProfessionalType::active()->get();
        
        return view('biomedicals.edit', 
            compact('biomedical', 'biomedical_info', 'classCouncils', 'states', 'professionals')
        );
    }

    public function update(BiomedicalRequest $request, User $biomedical)
    {  
        try {
            $user = Sentinel::getUser();
            $role = $user->roles[0]->slug;

            Biomedical::updateDataToUser($biomedical, $request, $user->id);
            Biomedical::updateData($biomedical, $request);
            session()->put('success', $request->message);
                    
            if ($role == 'biomedical') {
                return redirect('/')->withSuccess('Perfil atualizado com sucesso!');
            }
                
            return redirect()->route('biomedicals.index');
        } catch (\Exception $error) {
            return redirect()
                ->route('biomedicals.index')
                ->withError('Algo deu errado! ' . $error->getMessage());
        }
    }

    public function search()
    {
        return response()->json([
            'biomedicals' => Biomedical::getAll()
        ]);
    }
}
