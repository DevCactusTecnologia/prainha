<?php

namespace App\Http\Controllers;

use App\Models\Appointment\Appointment;
use App\Models\Invoice;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\TestReport;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Support\Facades\Config;

class PrescriptionController extends Controller
{
    protected $prescription, $medicine, $test_report, $TestReport, $limit;

    public function __construct()
    {
        $this->prescription = new Prescription();

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
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        if (! $user->hasAccess('prescription.list')) {
            return view('error.403'); 
        }
            
        if ($role == 'doctor') {
            $prescriptions = Prescription::with('patient', 'appointment', 'appointment.timeSlot')
                ->where('created_by', '=', $user->id)
                ->where('is_deleted', 0)
                ->orderBy('id', 'desc')
                ->paginate($this->limit);

        } elseif ($role == 'patient') {
            $prescriptions = Prescription::with('doctor', 'appointment', 'appointment.timeSlot')
                ->where('patient_id', $user->id)
                ->where('is_deleted', 0)
                ->orderBy('id', 'desc')
                ->paginate($this->limit);

        } else {
            $prescriptions = Prescription::with('patient', 'doctor', 'appointment', 'appointment.timeSlot')
                ->where('is_deleted', 0)
                ->orderBy('id', 'desc')
                ->paginate($this->limit);
        }

        return view('prescription.prescriptions', 
            compact('user', 'role', 'prescriptions')
        );
    }

    public function create()
    {
        $user = Sentinel::getUser();

        if (! $user->hasAccess('prescription.create')) {
            return view('error.403');  
        }

        $role = $user->roles[0]->slug;
        $patient_role = Sentinel::findRoleBySlug('patient');
        $patients = $patient_role->users()->with('roles')->get();

        return view('prescription.prescription-details', 
            compact('user', 'role', 'patients')
        );
    }

    public function store(Request $request)
    {
        $user = Sentinel::getUser();

        if (! $user->hasAccess('prescription.create')) {
            return view('error.403');
        }

        $request->validate([
            'patient_id' => 'required',
            'appointment_id' => 'required',
            'symptoms' => 'required',
            'diagnosis' => 'required'
        ]);
        
        try {
            if ($request->medicines[0]['medicine'] == null && $request->medicines[0]['notes'] == null) {
                return redirect()->back()->withError('Adicione pelo menos um medicamento para criar receita!!!');
            }

            $this->prescription->patient_id = $request->patient_id;
            $this->prescription->appointment_id = $request->appointment_id;
            $this->prescription->symptoms = $request->symptoms;
            $this->prescription->diagnosis = $request->diagnosis;
            $this->prescription->created_by = $request->created_by;
            $this->prescription->updated_by = $user->id;
            $this->prescription->save();

            foreach ($request->medicines as $item) {
                $this->medicine = new Medicine();
                $this->medicine->prescription_id = $this->prescription->id;
                $this->medicine->name = $item['medicine'];
                $this->medicine->notes = $item['notes'];
                $this->medicine->save();
            }

            if ($request->test_reports[0]['test_report'] != null && $request->test_reports[0]['notes'] != null) {
                foreach ($request->test_reports as $item) {
                    $this->test_report = new TestReport();
                    $this->test_report->prescription_id = $this->prescription->id;
                    $this->test_report->name = $item['test_report'];
                    $this->test_report->notes = $item['notes'];
                    $this->test_report->save();
                }
            }

            return redirect('prescription')->withSuccess('Prescrição criada com sucesso!');
            
        } catch (Exception $error) {
            return redirect()->back()->withError('Algo deu errado! ' . $error->getMessage());
        }
    }

    public function show(Prescription $prescription)
    {
        $user = Sentinel::getUser();

        if (! $user->hasAccess('prescription.show')) {
            return view('error.403');  
        }
            
        $role = $user->roles[0]->slug;
        $user_details = Prescription::with('patient', 'appointment', 'appointment.doctor')
            ->where('id', $prescription->id)
            ->where('is_deleted', 0)
            ->first();
        
        if (! $user_details) {
            return redirect('/')->withError('prescrição não encontrada'); 
        }

        $medicines = Medicine::where('prescription_id', $prescription->id)->where('is_deleted', 0)->get();
        $test_reports = TestReport::where('prescription_id', $prescription->id)->where('is_deleted', 0)->get();
        
        return view('prescription.view-prescription', 
            compact('user', 'role', 'prescription', 'medicines', 'test_reports', 'user_details')
        );
    }

    public function edit(Prescription $prescription)
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        if (! $user->hasAccess('prescription.update')) {
            return view('error.403');
        }
        
        $prescription = Prescription::with('patient', 'doctor', 'appointment')
            ->where('id', $prescription->id)
            ->where('is_deleted', 0)
            ->first();

        if (! $prescription) {
            return redirect('/')->withError('Prescrição não encontrada');  
        }
            
        $patient_role = Sentinel::findRoleBySlug('patient');
        $patients = $patient_role->users()->with('roles')->get();
        $appointment = Appointment::where('appointment_for', $prescription->patient->id)->where('is_deleted', 0)->get();
        $medicines = Medicine::where('prescription_id', $prescription->id)->where('is_deleted', 0)->get();
        $test_reports = TestReport::where('prescription_id', $prescription->id)->where('is_deleted', 0)->get();

        return view('prescription.prescription-edit', 
            compact('user', 'prescription', 'medicines', 'test_reports', 'role', 'patients', 'appointment')
        );
    }

    public function update(Request $request, Prescription $prescription)
    {
        $user = Sentinel::getUser();

        if (! $user->hasAccess('prescription.update')) {
            return view('error.403');
        }
            
        $request->validate([
            'patient_id' => 'required',
            'appointment_id' => 'required',
            'symptoms' => 'required',
            'diagnosis' => 'required'
        ]);
        
        try {
            if ($request->medicines[0]['medicine'] == null && $request->medicines[0]['notes'] == null) {
                return redirect()->back()->withError('Add at least one medicine to create prescription!!!');
            } 

            $prescription = Prescription::find($prescription->id);
            $prescription->patient_id = $request->patient_id;
            $prescription->appointment_id = $request->appointment_id;
            $prescription->symptoms = $request->symptoms;
            $prescription->diagnosis = $request->diagnosis;
            $prescription->updated_by = $user->id;
            $prescription->save();

            $medicine = Medicine::where('prescription_id', $prescription->id)->update(['is_deleted' => 1]);
            $test_report = TestReport::where('prescription_id', $prescription->id)->update(['is_deleted' => 1]);

            foreach ($request->medicines as $item) {
                $medicine = new Medicine();
                $medicine->prescription_id = $request->prescription->id;
                $medicine->name = $item['medicine'];
                $medicine->notes = $item['notes'];
                $medicine->save();
            }

            if ($request->test_reports[0]['test_report'] != null && $request->test_reports[0]['notes'] != null) {
                foreach ($request->test_reports as $item) {
                    $test_report = new TestReport();
                    $test_report->prescription_id = $request->prescription->id;
                    $test_report->name = $item['test_report'];
                    $test_report->notes = $item['notes'];
                    $test_report->save();
                }
            }
            
            return redirect('prescription')->withSuccess('Prescrição atualizada com sucesso!');
            
        } catch (Exception $error) {
            return redirect()->back()->withError('Algo deu errado!!! ' . $error->getMessage());
        } 
    }

    public function destroy(Prescription $prescription)
    {
        $user = Sentinel::getUser();

        if (! $user->hasAccess('prescription.delete')) {
            return response()->json([
                'success' => false,
                'message'=> 'Você não tem permissão para excluir médico',
                'data'=> [],
            ], 409);
        } 
            
        try {
            $prescription = Prescription::find($prescription->id);
            if (! $prescription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Receita não encontrada.',
                    'data' => [],
                ], 409);
            }

            $prescription->is_deleted = 1;
            $prescription->save();

            return response()->json([
                'success' => true,
                'message' => 'Prescrição encontrada com sucesso.',
                'data' => $prescription,
            ], 200);
             
        } catch (Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Algo deu errado!!!'.$error->getMessage(),
                'data' => [],
            ], 409);
        }
    }

    public function prescription_list()
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        $prescription = Invoice::where('payment_status','Paid')
            ->with('doctor', 'appointment','appointment.timeSlot','appointment.invoice','appointment.prescription')
            ->where('patient_id', $user->id)->where('is_deleted', 0)->orderBy('id', 'desc')
            ->paginate($this->limit);

        $prescriptions = collect();
        foreach ($prescription as $key => $value) {
            if ($value['appointment']['prescription']) {
                $prescriptions->push($value['id']);
            } else {
                $pre = $prescriptions;
            }
        }

        $prescriptions_details = Invoice::where('payment_status','Paid')
            ->with('doctor','appointment', 'appointment.timeSlot','appointment.prescription')
            ->WhereIn('id',$prescriptions)->orderBy('id', 'desc')
            ->paginate($this->limit);

        return view('patients.patient-prescriptions', 
            compact('user', 'role', 'prescriptions_details')
        );
    }

    public function prescription_view($id)
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        $user_details = Prescription::with(['patient', 'appointment', 'appointment.doctor','appointment.invoice'])
            ->where('patient_id', $user->id)
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->first();

        if (! $user_details) {
            return redirect()->back()->withError('Prescrição não encontrada');  
        }
            
        if (! $user_details->appointment->invoice) {
            return redirect()->back()->withError('Detalhes da fatura não encontrados'); 
        }

        $medicines = Medicine::where('prescription_id', $id)->where('is_deleted', 0)->get();
        $test_reports = TestReport::where('prescription_id', $id)->where('is_deleted', 0)->get();

        return view('patients.patient-prescription-view', 
            compact('user', 'role', 'medicines', 'test_reports', 'user_details')
        );
    }
}
