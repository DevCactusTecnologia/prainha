<?php

namespace App\Http\Controllers;

use App\Models\Appointment\Appointment;
use App\Models\Biomedical;
use App\Models\BiomedicalListDoctor;
use App\Models\Doctor;
use App\Models\DoctorAvailableDay;
use App\Models\DoctorAvailableTime;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\MedicalInfo;
use App\Models\ClassCouncil;
use App\Models\State;
use App\Models\Prescription;
use App\Models\ReceptionListDoctor;
use App\Models\Receptionist;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{
    protected $patient, $medical_info, $MedicalInfo, $limit;

    public function __construct()
    {
        $this->patient = new Patient();
        $this->medical_info = new MedicalInfo();

        $this->middleware(function ($request, $next) {
            if (session()->has('page_limit')) {
                $this->limit = session()->get('page_limit');
            } else {
                $this->limit = Config::get('app.page_limit');
            }

            return $next($request);
        });
    }

    public function index()
    {
        return redirect('/');
    }

    public function create()
    {
        if (Sentinel::check()) {
            return redirect('/');
        }
           
        return view('profile-details');
    }

    public function store(Request $request)
    {
        $validation = $request->validate([
            'age' => 'required|numeric',
            'address' => 'required',
            'gender' => 'required',
            'profile_photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:500',
            'height' => 'required',
            'b_group' => 'required',
            'pulse' => 'required',
            'allergy' => 'required',
            'weight' => 'required',
            'b_pressure' => 'required',
            'respiration' => 'required',
            'diet' => 'required'
        ]);

        try {
            $user = Sentinel::getUser();
            $patient = Sentinel::getUser();

            if ($request->hasFile('profile_photo')) {
                $des = 'storage/images/users/.' . $patient->profile_photo;
                if (File::exists($des)) {
                    File::delete($des);
                }
                $file = $request->file('profile_photo');
                $extention = $file->getClientOriginalExtension();
                $imageName = time() . '.' . $extention;
                $file->move(public_path('storage/images/users'), $imageName);
                $patient->profile_photo = $imageName;
            }

            $patient->save();

            // patient details save
            $patient_id = $patient->id;
            $patient_Details = new Patient();
            $patient_Details->user_id = $patient_id;
            $patient_Details->age = $request->age;
            $patient_Details->gender = $request->gender;
            $patient_Details->address = $request->address;
            $patient_Details->save();

            return $patient_Details;

            // medical info save
            $medical_info = new MedicalInfo();
            $medical_info->user_id = $patient_id;
            $medical_info->height = $request->height;
            $medical_info->b_group = $request->b_group;
            $medical_info->pulse = $request->pulse;
            $medical_info->allergy = $request->allergy;
            $medical_info->weight = $request->weight;
            $medical_info->b_pressure = $request->b_pressure;
            $medical_info->respiration = $request->respiration;
            $medical_info->diet = $request->diet;
            $medical_info->save();

            return redirect('/')->withSuccess('Registrado com sucesso');
        } catch (Exception $error) {
            return redirect()->back()->withError('Algo deu errado! ' . $error->getMessage());
        }
    }

    public function edit()
    {
        $user = Sentinel::getUser();

        if (! $user->hasAccess('profile.update')) {
            return view('error.403');
        }

        $userId = $user->id;
        $role = $user->roles[0]->slug;
        
        if ($role == 'admin') {
            return view('admin.admin-edit', 
                compact('user', 'role')
            );

        } elseif ($role == 'doctor') {
            $doctor = Sentinel::getUser();
            $doctor_info = Doctor::where('user_id', '=', $doctor->id)->first();

            if ($doctor_info) {
                $availableDay = DoctorAvailableDay::where('doctor_id', $doctor->id)->first();
                $availableTime = DoctorAvailableTime::where('doctor_id', $doctor->id)->get();

                return view('doctors.doctor-profile-edit', 
                    compact('user', 'role', 'doctor', 'doctor_info', 'availableDay', 'availableTime')
                );
            }
                
            return redirect('/')->withError('Detalhes do médico não encontrados');

        } elseif ($role == 'receptionist') {
            $receptionist = Sentinel::getUser();
            $role = $user->roles[0]->slug;
            $receptionist_info = Receptionist::where('user_id', '=', $receptionist->id)->first();

            if ($receptionist_info) {
                $doctor_role = Sentinel::findRoleBySlug('doctor');
                $doctors = $doctor_role->users()->with(['roles', 'doctor'])->where('is_deleted', 0)->get();
                $receptionist_doctor = ReceptionListDoctor::where('reception_id', $receptionist->id)->where('is_deleted', 0)->pluck('doctor_id');
                $doctor_user = User::whereIn('id', $receptionist_doctor)->pluck('id')->toArray();

                return view('receptionists.edit', 
                    compact('user', 'role', 'receptionist','receptionist_info', 'doctors', 'doctor_user')
                );
            }
                
            return redirect('/')->withError('Recepcionista não encontrada');

        } elseif ($role == 'biomedical') {
            $biomedical = Sentinel::getUser();
            $role = $user->roles[0]->slug;
            $biomedical_info = Biomedical::where('user_id', '=', $biomedical->id)->first();

            if ($biomedical_info) {
                $doctor_role = Sentinel::findRoleBySlug('doctor');
                $doctors = $doctor_role->users()->with(['roles', 'doctor'])->where('is_deleted', 0)->get();
                $biomedical_doctor = BiomedicalListDoctor::where('biomedical_id', $biomedical->id)->where('is_deleted', 0)->pluck('doctor_id');
                $doctor_user = User::whereIn('id', $biomedical_doctor)->pluck('id')->toArray();
                $classCouncils = ClassCouncil::all();
                $states = State::all();

                return view('biomedicals.edit', 
                    compact('user', 'role', 'classCouncils', 'states', 'biomedical','biomedical_info', 'doctors', 'doctor_user')
                );
            }
                
            return redirect('/')->withError('Analista não encontrado');

        } elseif ($role == 'patient') {
            $patient = Sentinel::getUser();
            $patient_info = Patient::where('user_id', $patient->id)->first();
            $medical_info = MedicalInfo::where('user_id', $patient->id)->first();
            
            return view('patients.edit', 
                compact('user', 'role', 'patient', 'patient_info', 'medical_info')
            );
        }
    }

    public function update(Request $request)
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        if (! $user->hasAccess('profile.update')) {
            return view('error.403');
        }
            
        if ($role == 'admin') {
            $validatedData = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required|alpha',
                'mobile' => 'required|numeric|digits:10',
                'email' => 'required|email',
                'profile_photo'=>'image|mimes:jpg,png,jpeg,gif,svg|max:500',
            ]);
            try {
                $userId = $user->id;
                if ($request->hasFile('profile_photo')) {
                    $des = 'storage/images/users/.' . $user->profile_photo;
                    if (File::exists($des)) {
                        File::delete($des);
                    }
                    $file = $request->file('profile_photo');
                    $extension = $file->getClientOriginalExtension();
                    $imageName = time() . '.' . $extension;
                    $file->move(public_path('storage/images/users'), $imageName);
                    $user->profile_photo = $imageName;
                }
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;
                $user->last_name = $request->last_name;
                $user->mobile = $request->mobile;
                $user->updated_by = $userId;
                $user->save();
                return redirect('/')->with('success', 'Perfil atualizado com sucesso!');
            } catch (Exception $e) {
                return redirect('/')->with('error', 'Algo deu errado!!! ' . $e->getMessage());
            }
        } elseif ($role == 'doctor') {
            $doctor = Sentinel::getUser();
            $user = Sentinel::getUser();
            $data = $request->all();
            $doctor_cns = str_replace(' ', '', $request->doctor_cns);
            $data['doctor_cns'] = str_replace('_', '', $doctor_cns);
            $doctor_cpf = str_replace('.', '', $request->doctor_cpf);
            $doctor_cpf = str_replace('_', '', $doctor_cpf);
            $doctor_cpf = str_replace('-', '', $doctor_cpf);
            $data['doctor_cpf'] = $doctor_cpf;
            $mobile = str_replace(' ', '', $request->mobile);
            $data['mobile'] = str_replace('-', '', $mobile);
            $request->merge([
                'mobile' => $data['mobile'],
                'doctor_cpf' => $doctor_cpf,
                'doctor_cns' => $data['doctor_cns'],
            ]);
            $doctorId = Doctor::where('user_id',$doctor->id)->first();
            
            $validatedData = $request->validate([
                'first_name' => 'required',
                'email' => 'required|email|unique:users,email,'.$doctor->id,
                'mobile' => 'required',
                'counsil_number' => 'required',
                'class_council' => 'required',
                'issuing_state' => 'required',
                'doctor_cpf' => 'required|unique:doctors,doctor_cpf,'.$doctorId->id,
                'doctor_cns' => 'required|unique:doctors,doctor_cns,'.$doctorId->id,
            ]);

            if ($request->profile_photo != null) {
                $request->validate([
                    'profile_photo' => 'image|mimes:jpg,png,jpeg,gif,svg|max:500'
                ]);
            }
            
            try {
                $user = Sentinel::getUser();
                $role = $user->roles[0]->slug;
                if ($request->hasFile('profile_photo')) {
                    $des = 'storage/images/users/.' . $doctor->profile_photo;
                    if (File::exists($des)) {
                        File::delete($des);
                    }
                    $file = $request->file('profile_photo');
                    $extention = $file->getClientOriginalExtension();
                    $imageName = time() . '.' . $extention;
                    $file->move(public_path('storage/images/users'), $imageName);
                    $doctor->profile_photo = $imageName;
                }
                $doctor->first_name = $validatedData['first_name'];
                $doctor->mobile = $request->mobile;
                $doctor->email = $request->email;
                if($doctor->password != $request->password){
                $password = $request->password ? $request->password : Config::get('app.DEFAULT_PASSWORD');
                $doctor->password = password_hash($password,PASSWORD_DEFAULT);
                }
                $doctor->updated_by = $user->id;
                $doctor->save();
                Doctor::where('user_id', $doctor->id)
                    ->update([
                        'doctor_cpf' => $request->doctor_cpf,
                        'doctor_cns' => $request->doctor_cns,
                        'class_council' => $request->class_council,
                        'issuing_state' => $request->issuing_state,
                        'counsil_number' => $request->counsil_number,
                    ]);
                if ($role == 'doctor') {
                    return redirect('/')->with('success', 'Perfil atualizado com sucesso!');
                } else {
                    return redirect('doctor')->with('success', 'Perfil atualizado com sucesso!');
                }
            } catch (Exception $e) {
                return redirect('doctor')->with('error', 'Algo deu errado!!! ' . $e->getMessage());
            }
        } elseif ($role == 'receptionist') {
            $receptionist = Sentinel::getUser();
            $data = $request->all();
            $cns = str_replace(' ', '', $request->cns);
            $data['cns'] = str_replace('_', '', $cns);
            $cpf = str_replace('.', '', $request->cpf);
            $cpf = str_replace('_', '', $cpf);
            $cpf = str_replace('-', '', $cpf);
            $data['cpf'] = $cpf;
            $mobile = str_replace(' ', '', $request->mobile);
            $data['mobile'] = str_replace('-', '', $mobile);
            $request->merge([
                'mobile' => $data['mobile'],
                'cpf' => $cpf,
                'cns' => $data['cns'],
            ]);
            $receptionistId = Receptionist::where('user_id',$receptionist->id)->first();
            
            $validatedData = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required|alpha',
                'email' => 'required|email|unique:users,email,'.$receptionist->id,
                'profile_photo' =>'image|mimes:jpg,png,jpeg,gif,svg|max:500',
                // 'signature' =>'image|mimes:jpg,png|max:500',
                'cpf' => 'required|unique:receptionist,cpf,'.$receptionistId->id,
                'cns' => 'required|unique:receptionist,cns,'.$receptionistId->id,
            ]);

            try {
                $user = Sentinel::getUser();
                $role = $user->roles[0]->slug;
                if ($request->hasFile('profile_photo')) {
                    $des = 'storage/images/users/.' . $receptionist->profile_photo;
                    if (File::exists($des)) {
                        File::delete($des);
                    }
                    $file = $request->file('profile_photo');
                    $extention = $file->getClientOriginalExtension();
                    $imageName = time() . '.' . $extention;
                    $file->move(public_path('storage/images/users'), $imageName);
                    $receptionist->profile_photo = $imageName;
                }
                $receptionist->first_name = $validatedData['first_name'];
                $receptionist->last_name = $validatedData['last_name'];
                $receptionist->mobile = $request->mobile;
                if($receptionist->password != $request->password){
                $password = $request->password ? $request->password : Config::get('app.DEFAULT_RECEPTIONIST_PASSWORD');
                $receptionist->password = password_hash($password,PASSWORD_DEFAULT);
                }
                $receptionist->email = $validatedData['email'];
                $receptionist->updated_by = $user->id;
                $receptionist->save();

                $signatureName = null;
                // if ($request->hasFile('signature')) {
                //     $des = 'storage/images/users/signature.' . $receptionist->signature;
                //     if (File::exists($des)) {
                //         File::delete($des);
                //     }
                //     $file = $request->file('signature');
                //     $extension = $file->getClientOriginalExtension();
                //     $signatureName = time() . '.' . $extension;
                //     $file->move(public_path('storage/images/users/signature'), $signatureName);
                // }

                $reception =  Receptionist::where('user_id', $receptionist->id)->first();
                if($reception){
                    $reception->cpf = $request->cpf;
                    $reception->cns = $request->cns;
                    if($signatureName != null){
                        $reception->signature =  $signatureName;
                    }
                    $reception->save();
                }
                    
                if ($role == 'receptionist') {
                    return redirect('/')->with('success', 'Perfil atualizado com sucesso!');
                } else {
                    return redirect('receptionist')->with('success', 'Perfil atualizado com sucesso!');
                }
            } catch (Exception $e) {
                return redirect('receptionist')->with('error', 'Algo deu errado!!! ' . $e->getMessage());
            }
        } elseif ($role == 'biomedical') {
            $biomedical = Sentinel::getUser();
            $data = $request->all();
            $cns = str_replace(' ', '', $request->cns);
            $data['cns'] = str_replace('_', '', $cns);
            $cpf = str_replace('.', '', $request->cpf);
            $cpf = str_replace('_', '', $cpf);
            $cpf = str_replace('-', '', $cpf);
            $data['cpf'] = $cpf;
            $mobile = str_replace(' ', '', $request->mobile);
            $data['mobile'] = str_replace('-', '', $mobile);
            $request->merge([
                'mobile' => $data['mobile'],
                'cpf' => $cpf,
                'cns' => $data['cns'],
            ]);
            $biomedicalId = Biomedical::where('user_id',$biomedical->id)->first();

            $validatedData = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required|alpha',
                'counsil_number' => '',
                'class_council' => 'required',
                'issuing_state' => 'required',
                'signature' =>'image|mimes:jpg,png|max:500',
                'email' => 'required|email|unique:users,email,'.$biomedical->id,
                'profile_photo' =>'image|mimes:jpg,png,jpeg,gif,svg|max:500',
                'cpf' => 'required|unique:biomedicalist,cpf,'.$biomedicalId->id,
                'cns' => 'required|unique:biomedicalist,cns,'.$biomedicalId->id,
            ]);
            try {
                $user = Sentinel::getUser();
                $role = $user->roles[0]->slug;
                if ($request->hasFile('profile_photo')) {
                    $des = 'storage/images/users/.' . $biomedical->profile_photo;
                    if (File::exists($des)) {
                        File::delete($des);
                    }
                    $file = $request->file('profile_photo');
                    $extention = $file->getClientOriginalExtension();
                    $imageName = time() . '.' . $extention;
                    $file->move(public_path('storage/images/users'), $imageName);
                    $biomedical->profile_photo = $imageName;
                }
                $biomedical->first_name = $validatedData['first_name'];
                $biomedical->last_name = $validatedData['last_name'];
                $biomedical->mobile = $request->mobile;
                
                if($biomedical->password != $request->password){
                $password = $request->password ? $request->password : Config::get('app.DEFAULT_RECEPTIONIST_PASSWORD');
                $biomedical->password = password_hash($password,PASSWORD_DEFAULT);
                }
                
                $biomedical->email = $validatedData['email'];
                $biomedical->updated_by = $user->id;
                $biomedical->save();
                
                
                    $signatureName = null;
                if ($request->hasFile('signature')) {
                    $des = 'storage/images/users/signature.' . $biomedical->signature;
                    if (File::exists($des)) {
                        File::delete($des);
                    }
                    $file = $request->file('signature');
                    $extension = $file->getClientOriginalExtension();
                    $signatureName = time() . '.' . $extension;
                    $file->move(public_path('storage/images/users/signature'), $signatureName);
                }
                
                    $biomedicals =  Biomedical::where('user_id', $biomedical->id)->first();
                    if($biomedicals){
                        $biomedicals->cpf = $request->cpf;
                        $biomedicals->cns = $request->cns;
                        $biomedicals->issuing_state = $request->issuing_state;
                        $biomedicals->counsil_number = $request->counsil_number;
                        $biomedicals->class_council = $request->class_council;
                        if($signatureName != null){
                            $biomedicals->signature =  $signatureName;
                        }
                        $biomedicals->save();
                    }
                    
                if ($role == 'biomedical') {
                    return redirect('/')->with('success', 'Perfil atualizado com sucesso!');
                } else {
                    return redirect('biomedical')->with('success', 'Perfil atualizado com sucesso!');
                }
            } catch (Exception $e) {
                return redirect('biomedical')->with('error', 'Algo deu errado!!! ' . $e->getMessage());
            }
        } elseif ($role == 'patient') {
            $patient = Sentinel::getUser();
            $data = $request->all();
            $cns = str_replace(' ', '', $request->cns);
            $data['cns'] = str_replace('_', '', $cns);
            $patient_cpf = str_replace('.', '', $request->patient_cpf);
            $patient_cpf = str_replace('_', '', $patient_cpf);
            $patient_cpf = str_replace('-', '', $patient_cpf);
            $data['patient_cpf'] = $patient_cpf;
            $mobile = str_replace(' ', '', $request->mobile);
            $data['mobile'] = str_replace('-', '', $mobile);
            $request->merge([
                'mobile' => $data['mobile'],
                'patient_cpf' => $patient_cpf,
                'cns' => $data['cns'],
            ]);
            $patientId = Patient::where('user_id',$patient->id)->first();
            $validatedData = $request->validate([
                'first_name' => 'required',
                'dob' => 'required',
                'gender' => 'required',
                'email' => 'required|email|unique:users,email,' . $patient->id,
                'patient_cpf' => 'required|unique:patients,patient_cpf,'.$patientId->id,
                'cns' => 'required|unique:patients,cns,'.$patientId->id,
            ]);

            if ($request->profile_photo != null) {
                $request->validate([
                    'profile_photo' => 'image|mimes:jpg,png,jpeg,gif,svg|max:500'
                ]);
            }

            try {
                $user = Sentinel::getUser();
                $role = $user->roles[0]->slug;

                if ($request->hasFile('profile_photo')) {
                    $des = 'storage/images/users/.' . $patient->profile_photo;
                    if (File::exists($des)) {
                        File::delete($des);
                    }
                    $file = $request->file('profile_photo');
                    $extention = $file->getClientOriginalExtension();
                    $imageName = time() . '.' . $extention;
                    $file->move(public_path('storage/images/users'), $imageName);
                    $patient->profile_photo = $imageName;
                }

                $patient->first_name = $validatedData['first_name'];
                $patient->mobile = $request->$mobile;
                
                if ($patient->password != $request->password) {
                    $password = $request->password ? $request->password : Config::get('app.DEFAULT_PASSWORD');
                    $patient->password = password_hash($password,PASSWORD_DEFAULT);
                }
                
                $patient->email = $request->email;
                $patient->updated_by = $user->id;
                $patient->save();
                $patient_info = Patient::where('user_id', '=', $user->id)->first();
            
                if ($patient_info == null) {
                    $patient_info = new Patient();
                    $patient_info->dob = $request->dob;
                    $patient_info->cns = $request->cns ;
                    $patient_info->patient_cpf = $request->patient_cpf;
                    $patient_info->patient_social_name = $request->patient_social_name;
                    $patient_info->gender = $request->gender;
                    $patient_info->address = $request->address;
                    $patient_info->user_id = $patient->id;
                    $patient_info->save();
                } else {
                    $patient_info->dob = $request->dob;
                    $patient_info->cns = $request->cns;
                    $patient_info->patient_cpf = $request->patient_cpf;
                    $patient_info->patient_social_name = $request->patient_social_name;
                    $patient_info->gender = $request->gender;
                    $patient_info->address = $request->address;
                    $patient_info->user_id = $patient->id;
                    $patient_info->save();
                }
                
                if ($role == 'patient') {
                    return redirect('/')->withSuccess('Perfil atualizado com sucesso!');
                }
                    
                return redirect('patient')->withSuccess('Perfil atualizado com sucesso!');
                
            } catch (Exception $error) {
                return redirect('patient')->withError('Algo deu errado! ' . $error->getMessage());
            }
        }
    }

    public function profile_view()
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        if ($role == 'patient') {
            $patient = Sentinel::getUser();
            $patient_info = Patient::where('user_id', '=', $patient->id)->first();

            if ($patient) {
                $medical_Info = MedicalInfo::where('user_id', '=', $patient->id)->first();
                $patient_role = Sentinel::findRoleBySlug('patient');
                $patients = $patient_role->users()->with('roles')->get();
                $appointments = Appointment::with('doctor')->where('appointment_for', $patient->id)->orderBy('id', 'desc')->paginate($this->limit, '*', 'appointment');
                $prescriptions = Prescription::with('doctor')->where('patient_id', $patient->id)->orderBy('id', 'desc')->paginate($this->limit, '*', 'prescription');
                $invoices = Invoice::where('patient_id', $patient->id)->orderBy('id', 'desc')->paginate($this->limit, '*', 'invoice');
                $tot_appointment = Appointment::where('appointment_for', $patient->id)->get();
                $invoice = Invoice::withCount(['invoice_detail as total' => function ($re) {
                    $re->select(DB::raw('SUM(amount)'));
                }])->where('patient_id', $patient->id)->pluck('id');
                $revenue = InvoiceDetail::whereIn('invoice_id', $invoice)->sum('amount');
                $pending_bill = Invoice::where(['patient_id' => $patient->id, 'payment_status' => 'Unpaid'])->count();
                $data = [
                    'total_appointment' => $tot_appointment->count(),
                    'revenue' => $revenue,
                    'pending_bill' => $pending_bill
                ];

                return view('patients.show', 
                    compact('user', 'role', 'patient', 'patient_info', 'medical_Info', 'data', 'appointments', 'prescriptions', 'invoices')
                );
            }
                
            return redirect('/')->withError('Paciente não encontrado');
            
        } elseif ($role == 'doctor') {
            $doctor = Sentinel::getUser();
            $doctor_id = $doctor->id;
            $role = $user->roles[0]->slug;
            $doctor_info = Doctor::where('user_id', '=', $doctor->id)->first();

            if ($doctor_info) {
                $appointments = Appointment::where(function ($re) use ($doctor_id) {
                    $re->orWhere('appointment_with', $doctor_id);
                    $re->orWhere('booked_by', $doctor_id);
                })->orderBy('id', 'DESC')->paginate($this->limit, '*', 'appointments');
                $prescriptions = Prescription::with('patient')->where('created_by', $doctor->id)->orderby('id', 'desc')->paginate($this->limit, '*', 'prescriptions');
                $invoices = Invoice::with('user')->where('invoices.created_by', '=', $doctor->id)->orderby('id', 'desc')->get();
                // $receptionists_doctor_id = ReceptionListDoctor::where('doctor_id', $doctor_id)->pluck('reception_id');
                $invoices = Invoice::with('user')->where('doctor_id', $doctor_id)->paginate($this->limit, '*', 'invoices');
                $tot_appointment = Appointment::where(function ($re) use ($doctor_id) {
                    $re->orWhere('appointment_with', $doctor_id);
                    $re->orWhere('booked_by', $doctor_id);
                })->get();
                $invoice = Invoice::withCount(['invoice_detail as total' => function ($re) {
                    $re->select(DB::raw('SUM(amount)'));
                }])->where('doctor_id', $doctor_id)->pluck('id');
                $revenue = InvoiceDetail::whereIn('invoice_id', $invoice)->sum('amount');

                $pending_bill = Invoice::where(['doctor_id' => $doctor_id, 'payment_status' => 'Unpaid'])->count();

                $data = [
                    'total_appointment' => $tot_appointment->count(),
                    'revenue' => $revenue,
                    'pending_bill' => $pending_bill
                ];
                $availableDay = DoctorAvailableDay::where('doctor_id', $doctor->id)->first();
                $availableTime = DoctorAvailableTime::where('doctor_id', $doctor->id)->where('is_deleted', 0)->get();

                return view('doctors.doctor-profile-view', 
                    compact('user', 'role', 'doctor', 'doctor_info', 'data', 'appointments', 'availableTime', 'prescriptions', 'invoices', 'availableDay')
                );
            }
                
            return redirect('/')->withError('Detalhes médicos não encontrados');
            
        } elseif ($role == 'receptionist') {
            $receptionist = Sentinel::getUser();
            $user_id = $receptionist->id;
            $role = $user->roles[0]->slug;
            $doctor_role = Sentinel::findRoleBySlug('doctor');
            $doctor_id = $doctor_role->users()->with(['roles', 'doctor'])->where('is_deleted', 0)->pluck('id');
            
            $tot_appointment = Appointment::where(function ($re) use ($user_id, $doctor_id) {
                $re->whereIN('appointment_with', $doctor_id);
                $re->orWhereIN('booked_by', $doctor_id);
                $re->orWhere('booked_by', $user_id);
            })->get();

            $invoice = Invoice::withCount(['invoice_detail as total' => function ($re) {
                $re->select(DB::raw('SUM(amount)'));
            }])->where(function ($re) use ($user_id, $doctor_id) {
                $re->orWhereIN('created_by', $doctor_id);
                $re->orWhere('created_by', $user_id);
            })->pluck('id');
            $revenue = InvoiceDetail::whereIn('invoice_id', $invoice)->sum('amount');

            $pending_bill = Invoice::where(['payment_status' => 'Unpaid'])
                ->where(function ($re) use ($user_id, $doctor_id) {
                    $re->whereIN('doctor_id', $doctor_id);
                    $re->orWhere('created_by', $user_id);
                })->count();
            $data = [
                'total_appointment' => $tot_appointment->count(),
                'revenue' => $revenue,
                'pending_bill' => $pending_bill
            ];
            $appointments = Appointment::where(function ($re) use ($user_id, $doctor_id) {
                $re->whereIN('appointment_with', $doctor_id);
                $re->orWhereIN('booked_by', $doctor_id);
                $re->orWhere('booked_by', $user_id);
            })->orderBy('id', 'DESC')->paginate($this->limit, '*', 'appointments');
            $invoices = Invoice::with('user')
                ->where(function ($re) use ($user_id, $doctor_id) {
                    $re->whereIN('doctor_id', $doctor_id);
                    $re->orWhere('created_by', $user_id);
                })->paginate($this->limit, '*', 'invoice');
            $doctor_role = Sentinel::findRoleBySlug('doctor');
            $doctors = $doctor_role->users()->with(['roles', 'doctor'])->where('is_deleted', 0)->get();
            $receptionist_doctor = ReceptionListDoctor::where('reception_id', $receptionist->id)->where('is_deleted', 0)->pluck('doctor_id');
            $doctor_user = User::whereIn('id', $receptionist_doctor)->get();

            $limit = $this->limit;

            return view('receptionists.show', 
                compact('user', 'role', 'receptionist', 'data', 'appointments', 'invoices', 'doctor_user', 'limit')
            );
        }elseif ($role == 'biomedical') {
            $biomedical = Sentinel::getUser();
            $user_id = $biomedical->id;
            $role = $user->roles[0]->slug;
            $doctor_role = Sentinel::findRoleBySlug('doctor');
            $doctor_id = $doctor_role->users()->with(['roles', 'doctor'])->where('is_deleted', 0)->pluck('id');
            
            $tot_appointment = Appointment::where(function ($re) use ($user_id, $doctor_id) {
                $re->whereIN('appointment_with', $doctor_id);
                $re->orWhereIN('booked_by', $doctor_id);
                $re->orWhere('booked_by', $user_id);
            })->get();

            $invoice = Invoice::withCount(['invoice_detail as total' => function ($re) {
                $re->select(DB::raw('SUM(amount)'));
            }])->where(function ($re) use ($user_id, $doctor_id) {
                $re->orWhereIN('created_by', $doctor_id);
                $re->orWhere('created_by', $user_id);
            })->pluck('id');
            $revenue = InvoiceDetail::whereIn('invoice_id', $invoice)->sum('amount');

            $pending_bill = Invoice::where(['payment_status' => 'Unpaid'])
                ->where(function ($re) use ($user_id, $doctor_id) {
                    $re->whereIN('doctor_id', $doctor_id);
                    $re->orWhere('created_by', $user_id);
                })->count();

            $data = [
                'total_appointment' => $tot_appointment->count(),
                'revenue' => $revenue,
                'pending_bill' => $pending_bill
            ];
            $appointments = Appointment::where(function ($re) use ($user_id, $doctor_id) {
                $re->whereIN('appointment_with', $doctor_id);
                $re->orWhereIN('booked_by', $doctor_id);
                $re->orWhere('booked_by', $user_id);
            })->orderBy('id', 'DESC')->paginate($this->limit, '*', 'appointments');
            $invoices = Invoice::with('user')
                ->where(function ($re) use ($user_id, $doctor_id) {
                    $re->whereIN('doctor_id', $doctor_id);
                    $re->orWhere('created_by', $user_id);
                })->paginate($this->limit, '*', 'invoice');

            $doctor_role = Sentinel::findRoleBySlug('doctor');
            $doctors = $doctor_role->users()->with(['roles', 'doctor'])->where('is_deleted', 0)->get();
            $biomedical_doctor = Biomedical::where('user_id', $biomedical->id)->where('is_deleted', 0)->pluck('id');
            $doctor_user = User::whereIn('id', $biomedical_doctor)->get();

            $limit = $this->limit;
            
            return view('biomedicals.show', 
                compact('user', 'role', 'biomedical', 'data', 'appointments', 'invoices', 'doctor_user', 'limit')
            );
        } else {
            return redirect('/')->withError('Função não encontrada');
        }
    }
}
