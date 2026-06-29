<?php

namespace App\Http\Controllers;

use App\Models\Appointment\Appointment;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Notification;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class InvoiceController extends Controller
{
    protected $invoice, $invoice_detail, $InvoiceDetail, $limit;

    public function __construct()
    {
        $this->invoice = new Invoice;

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

        if (! $user->hasAccess('invoice.list')) {
            return view('error.403'); 
        }

        if ($role == 'doctor') {
            $invoices = Invoice::with('user')
                ->where('doctor_id', $user->id)
                ->orderBy('id', 'DESC')
                ->paginate($this->limit);

        } elseif ($role == 'patient') {
            $invoices = Invoice::with('appointment', 'appointment.timeSlot')
                ->where('patient_id', $user->id)
                ->orderBy('id', 'DESC')
                ->paginate($this->limit);

        } else {
            $invoices = Invoice::with('user')->paginate($this->limit);
        }

        return view('invoice.invoices', 
            compact('user', 'role', 'invoices')
        );
    }

    public function create()
    {
        $user = Sentinel::getUser();

        if (! $user->hasAccess('invoice.create')) {
            return view('error.403');  
        } 

        $role = $user->roles[0]->slug;
        $patient_role = Sentinel::findRoleBySlug('patient');
        $patients = $patient_role->users()->with('roles')->get();
        $doctor_role = Sentinel::findRoleBySlug('doctor');
        $doctor = $doctor_role->users()->with('roles')->get();

        return view('invoice.invoice-details', 
            compact('user', 'role', 'patients', 'doctor')
        );
    }

    public function store(Request $request)
    {
        $user = Sentinel::getUser();

        if (! $user->hasAccess('invoice.create')) {
            return view('error.403');
        } 

        $request->validate([
            'patient_id' => 'required',
            'appointment_id' => 'required',
            'payment_mode' => 'required',
            'payment_status' => 'required'
        ]);

        try {
            if ($request->invoices[0]['title'] == null && $request->invoices[0]['amount'] == null) {
                return redirect()->back()->withError('Adicione pelo menos um título e valor para criar a fatura!');
            }

            $this->invoice->patient_id = $request->patient_id;
            $this->invoice->payment_mode = $request->payment_mode;
            $this->invoice->payment_status = $request->payment_status;
            $this->invoice->appointment_id = $request->appointment_id;

            if ($request->doctor_id !== Null) {
                $this->invoice->doctor_id = $request->doctor_id;
            } else {
                $this->invoice->doctor_id = $request->created_by;
            }

            if ($request->created_by == Null) {
                $this->invoice->created_by = $request->created_by;
            } else {
                $this->invoice->created_by = $request->created_by;
            }

            $this->invoice->created_by = $request->created_by;
            $this->invoice->updated_by = $user->id;
            $this->invoice->save();

            foreach ($request->invoices as $item) {
                $this->invoice_detail = new InvoiceDetail();
                $this->invoice_detail->invoice_id = $this->invoice->id;
                $this->invoice_detail->title = $item['title'];
                $this->invoice_detail->amount = $item['amount'];
                $this->invoice_detail->save();
            }
            
            $notification = new Notification();
            $notification->notification_type_id = 4;
            $notification->title = 'New invoice Generated';
            $notification->data = $this->invoice->id;
            $notification->from_user = $this->invoice->created_by;
            $notification->to_user = $this->invoice->patient_id;
            $notification->save();

            return redirect('invoice')->withSuccess('Fatura criada com sucesso!');
            
        } catch (Exception $error) {
            return redirect()->back()->withError('Algo deu errado! ' . $error->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        $user = Sentinel::getUser();

        if (! $user->hasAccess('invoice.show')) {
            return view('error.403'); 
        }

        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        $invoice_detail = Invoice::with('invoice_detail', 'patient', 'doctor', 'appointment', 'appointment.timeSlot','transaction')
            ->where('id', $invoice->id)
            ->first();
       
        return view('invoice.view-invoice', 
            compact('user', 'role', 'invoice', 'invoice_detail')
        );
    }

    public function edit($id)
    {
        $user = Sentinel::getUser();
        
        if (! $user->hasAccess('invoice.edit')) {
            return view('error.403');
        } 

        $role = $user->roles[0]->slug;
        $patient_role = Sentinel::findRoleBySlug('patient');
        $patients = $patient_role->users()->with('roles')->get();
        $invoice_detail = Invoice::with('invoice_detail','patient', 'doctor', 'appointment', 'appointment.timeSlot','appointment.doctor')
            ->where('id',$id)
            ->first();
        
        $patient_id = $invoice_detail->patient->id;
        $appointment = Appointment::where('appointment_for', $patient_id)
            ->where('is_deleted', 0)
            ->get();
        
        return view('invoice.edit-invoice', 
            compact('user', 'role', 'invoice_detail','patients','appointment')
        );
    }

    public function update(Request $request, $id)
    {
        $user = Sentinel::getUser();

        if (! $user->hasAccess('invoice.edit')) {
            return view('error.403');
        }

        $request->validate([
            'patient_id' => 'required',
            'appointment_id' => 'required',
            'payment_mode' => 'required',
            'payment_status' => 'required'
        ]);

        try {
            if ($request->invoices[0]['title'] == null && $request->invoices[0]['amount'] == null) {
                return redirect()->back()->withError('Adicione pelo menos um título e valor da fatura para criar a fatura!!!');
            } 

            $invoice = Invoice::find($id);
            $invoice->patient_id = $request->patient_id;
            $invoice->payment_mode = $request->payment_mode;
            $invoice->payment_status = $request->payment_status;
            $invoice->appointment_id = $request->appointment_id;

            if ($request->doctor_id !== Null) {
                $invoice->doctor_id = $request->doctor_id;
            } else {
                $invoice->doctor_id = $request->created_by;
            }

            $invoice->updated_by = $user->id;
            $invoice->save();
            
            $invoice_detail = InvoiceDetail::where('invoice_id', $invoice->id)->update(['is_deleted' => 1]);
            foreach ($request->invoices as $item) {
                $invoice_detail = new InvoiceDetail();
                $invoice_detail->invoice_id = $invoice->id;
                $invoice_detail->title = $item['title'];
                $invoice_detail->amount = $item['amount'];
                $invoice_detail->save();
            }
            
            return redirect('invoice')->withSuccess('Fatura atualizada com sucesso!');
            
        } catch (Exception $error) {
            return redirect()->back()->withError('error', 'Algo deu errado! ' . $error->getMessage());
        }
    }

    public function patient_by_appointment(Request $request)
    {
        $user = Sentinel::getUser();

        if (! $user->hasAccess('invoice.create')) {
            return view('error.403');
        }

        if ($request->ajax()) {
            $patient_id =  $request->patient_id;
            $user_id = $user->id;
            $role = $user->roles[0]->slug;
            $doctor_role = Sentinel::findRoleBySlug('doctor');

            if ($role == 'doctor') {
                $appointment = Appointment::where('id', $user_id)
                    ->orWhere('appointment_for', $patient_id)
                    ->where('appointment_with', $user_id)
                    ->where('status', 1)
                    ->orderBy('id', 'DESC')->pluck('appointment_date', 'id')
                    ->all();

            } elseif ($role == 'receptionist' || $role == 'biomedical') {
                $doctor_role = Sentinel::findRoleBySlug('doctor');
                $doctor_id = $doctor_role->users()->with(['roles', 'doctor'])->where('is_deleted', 0)->pluck('id');

                $appointment = Appointment::where('appointment_for', $patient_id)
                    ->whereIN('appointment_with', $doctor_id)
                    ->where('status', 1)
                    ->orderBy('id', 'DESC')
                    ->pluck("appointment_date", 'id')
                    ->all();
            }

            $options = "<option selected value=''>--- Select Appointment ---</option>";
            foreach ($appointment as $key => $value) {
                $options .= "<option value='{$key}'>{$value}</option>";
            }

            return response()->json([
                'isSuccess' => true,
                'Message' => "Atendimento selecionado por ID do paciente!",
                'options' => $options,
            ]);
        }
    }
    public function appointment_by_doctor(Request $request)
    {
        $user = Sentinel::getUser();

        if (! $user->hasAccess('invoice.create')) {
            return view('error.403');  
        }

        if ($request->ajax()) {
            $appointment_id =  $request->appointment_id;
            $role = $user->roles[0]->slug;
            $doctor_role = Sentinel::findRoleBySlug('doctor');
            $appointment = Appointment::where('id', $appointment_id)->pluck('appointment_with');
            $doctors = $doctor_role->users()->where('id', $appointment)->select('first_name', 'last_name', 'id')->get();

            return response()->json([
                'isSuccess' => true,
                'Message' => 'Atendimento selecionado por ID do paciente!',
                'data' => $doctors
            ]);
        }
    }

    public function invoice_list()
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        $invoices = Invoice::with('appointment', 'appointment.timeSlot')
            ->where('patient_id', $user->id)
            ->orderBy('id', 'DESC')
            ->paginate($this->limit);
        
        return view('patients.patient-invoices', 
            compact('user', 'role', 'invoices')
        );
    }

    public function invoice_view($id)
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        $invoice_detail = Invoice::with('invoice_detail', 'patient', 'doctor', 'appointment', 'appointment.timeSlot','transaction')
            ->where('patient_id', $user->id)
            ->where('id', $id)
            ->first();
        
        if (! $invoice_detail) {
            return redirect()->back()->withError('Detalhes da fatura não encontrados');           
        }

        return view('patients.patient-invoice-view', 
            compact('user', 'role', 'invoice_detail')
        );
    }
}
