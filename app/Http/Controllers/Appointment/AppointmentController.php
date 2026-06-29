<?php

namespace App\Http\Controllers\Appointment;

use App\Enums\Appointment\DocumentTypeEnum;
use App\Enums\Appointment\MotiveEnum;
use App\Enums\Appointment\PriorityEnum;
use App\Models\Appointment\Appointment;
use App\Models\Biomedical;
use App\Models\Notification;
use App\Models\ReceptionListDoctor;
use App\Models\Doctor;
use App\Enums\Routine\StageEnum;
use App\Helpers\Pagination;
use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\AppointmentRequest;
use App\Models\Appointment\GuideNumberCounter;
use App\Models\ClassCouncil;
use App\Models\Company;
use App\Models\Exam\Exam;
use App\Models\Unity;
use App\Models\Patient;
use App\Models\State;
use App\Models\User;
use App\Repositories\Appointment\AppointmentRepository;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    public function __construct(
        private readonly AppointmentRepository $repository
    ) {}

    public function index(): View
    {
        return view('appointments.index', [
            'appointments' => Appointment::getByStatus('pending'),
            'motives' => MotiveEnum::cases(),
        ]);
    }

    public function cancel(Request $request, Appointment $appointment): RedirectResponse
    {
        DB::beginTransaction();
            $this->repository->cancel($request, $appointment);
        DB::commit();

        return redirect()
            ->route('appointments.index')
            ->withSuccess('Atendimento cancelado com sucesso!');
    }

    public function create(): View
    {
        GuideNumberCounter::reset();
        
        return view('appointments.create', [
            'doctors' => Doctor::getAll(),
            'unitys' => Unity::active()->get(),
            'companies' => Company::active()->get(),
            'biomedicals' => Biomedical::getAll(),
            'deliveredAt' => Appointment::getDateDelivery(),
            'priorities' => PriorityEnum::cases(),
            'classCouncils' => ClassCouncil::all(),
            'states' => State::all(),
            'documents' => DocumentTypeEnum::cases(),
        ]);
    }

    public function store(AppointmentRequest $request)
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        $userId = $user->id;

        try {
            $verify_mail = $user->email;
            $app_name =  config('app.name');

            DB::beginTransaction();
                $guideNumber = GuideNumberCounter::incrementGuide($request->unity_id);
                $appointment = Appointment::create($request->validated() + ['guide_number' => $guideNumber]);

                foreach ($request->exam_ids as $index => $examId) {
                    $exam = Exam::find($examId);

                    $appointment->traceabilities()->create([
                        'exam_id' => $exam->id,
                        'stage_id' => StageEnum::REGISTER->value,
                        'user_id' => $userId,
                        'registered_at' => $appointment->appointment_date . ' ' . date('H:i:s'),
                    ]);

                    DB::table('appointment_exams')->insert([
                        'appointment_id' => $appointment->id,
                        'exam_id' => $exam->id,
                        'model_id' => $exam->model_id,
                        'biomedical_id' => $request->exam_biomedicals[$index],
                        'collected_at' => $request->exam_collected_at[$index],
                        'status' => 0,
                        're_test' => 0,
                    ]);
                }

                $files = [];
                foreach (array_filter($request->documents ?: []) as $index => $file) {
                    $extention = $file->getClientOriginalExtension();
                    $fileName = Str::random() . '.' . $extention;
                    $file->move(public_path("storage/files/{$appointment->id}/"), $fileName);

                    $files[] = [
                        'type_id' => $request->documents_types[$index],
                        'path' => $fileName,
                        'appointment_id' => $appointment->id,
                    ];
                }

                if (count($files) > 0) {
                    DB::table('appointment_documents')->insert($files);
                }
            DB::commit();

            // appointment create notification send and mail send
            // Find Mail
            $MailAppointment = Appointment::with('doctor', 'patient', 'BookedBy')
                ->where('id', $appointment->id)
                ->first();

            if ($role == 'receptionist') {
                $admin_role = Sentinel::findRoleBySlug('admin');
                $admin_id = $admin_role->users()->with('roles')->pluck('id');
                $patient_id = $appointment->appointment_for;
                $doctor_id = $appointment->appointment_with;

                $fromId = collect();
                $fromId->push($patient_id);
                $fromId->push($admin_id);
                $fromId->push($doctor_id);
                $from_id =  $fromId->flatten();

                foreach ($from_id as $item) {
                    $notification = new Notification();
                    $notification->to_user = $item;
                    $notification->notification_type_id = 1;
                    $notification->title = 'Adicionado';
                    $notification->data = $appointment->id;
                    $notification->from_user = $userId;
                    $notification->save();
                }

                $admin_email = $admin_role->users()->with('roles')->pluck('email');
                $receptionists_doctor_email = User::where('id', $doctor_id)->pluck('email');
                $receptionists_patient_email = User::where('id', $patient_id)->pluck('email');
                // return $receptionists_patient_email;

                $mailSend = collect();
                $mailSend->push($receptionists_patient_email);
                $mailSend->push($receptionists_doctor_email);
                $mailSend->push($admin_email);
                $mailSend = $mailSend->flatten();
                $mailArray = $mailSend->toarray();
                // Mail::send('emails.appointment_create', ['MailAppointment' => $MailAppointment, 'email' => $verify_mail], function ($message) use ($mailArray, $app_name) {
                //     $message->to($mailArray)->subject($app_name . ' ' . 'Novo Atendimento gerado');
                // });

            } elseif ($role == 'biomedical') {
                $admin_role = Sentinel::findRoleBySlug('admin');
                $admin_id = $admin_role->users()->with('roles')->pluck('id');
                $patient_id = $appointment->appointment_for;
                $doctor_id = $appointment->appointment_with;

                $fromId = collect();
                $fromId->push($patient_id);
                $fromId->push($admin_id);
                $fromId->push($doctor_id);
                $from_id =  $fromId->flatten();

                foreach ($from_id as $item) {
                    $notification = new Notification();
                    $notification->to_user = $item;
                    $notification->notification_type_id = 1;
                    $notification->title = 'Adicionado';
                    $notification->data = $appointment->id;
                    $notification->from_user = $userId;
                    $notification->save();
                }

                $admin_email = $admin_role->users()->with('roles')->pluck('email');
                $biomedicalist_doctor_email = User::where('id', $doctor_id)->pluck('email');
                $biomedicalist_patient_email = User::where('id', $patient_id)->pluck('email');
                // return $biomedicalist_patient_email;

                $mailSend = collect();
                $mailSend->push($biomedicalist_patient_email);
                $mailSend->push($biomedicalist_doctor_email);
                $mailSend->push($admin_email);
                $mailSend = $mailSend->flatten();
                $mailArray = $mailSend->toarray();

                // Mail::send('emails.appointment_create', ['MailAppointment' => $MailAppointment, 'email' => $verify_mail], function ($message) use ($mailArray, $app_name) {
                //     $message->to($mailArray)->subject($app_name . ' ' . 'Novo Atendimento gerado');
                // });
            }  
            elseif ($role == 'doctor') {
                $receptionists_doctor_id = ReceptionListDoctor::where('appointment_with', $appointment->appointment_with)->pluck('reception_id');
                $patient_id = $appointment->appointment_for;
                $admin_role = Sentinel::findRoleBySlug('admin');
                $admin_id = $admin_role->users()->with('roles')->pluck('id');

                $fromId = collect();
                $fromId->push($patient_id);
                $fromId->push($admin_id);
                $fromId->push($receptionists_doctor_id);
                $from_id =  $fromId->flatten();

                foreach ($from_id as $item) {
                    $notification = new Notification();
                    $notification->to_user = $item;
                    $notification->notification_type_id = 1;
                    $notification->title = 'Adicionado';
                    $notification->data = $appointment->id;
                    $notification->from_user = $userId;
                    $notification->save();
                }

                $receptionists_doctor_mail = ReceptionListDoctor::where('appointment_with', $appointment->appointment_with)->pluck('reception_id');
                $reception_email = User::whereIN('id', $receptionists_doctor_mail)->pluck('email');
                $patient_email = User::where('id', $patient_id)->pluck('email');
                $admin_email = $admin_role->users()->with('roles')->pluck('email');

                $mailSend = collect();
                $mailSend->push($reception_email);
                $mailSend->push($patient_email);
                $mailSend->push($admin_email);
                $mailSend = $mailSend->flatten();

                $mailArray = $mailSend->toarray();
                // Mail::send('emails.appointment_create', ['MailAppointment' => $MailAppointment, 'email' => $verify_mail], function ($message) use ($mailArray, $app_name) {
                //     $message->to($mailArray)->subject($app_name . ' ' . 'Novo Atendimento gerado');
                // });
            }
        } catch (Exception $error) {
            return redirect()->back()->withError('Algo deu errado! ' . $error->getMessage());
        }

        session()->put('appointment_id', $appointment->id);
        session()->put('status', 'Agendamento criado com sucesso');

        return redirect()->route('appointments.create');
    }
    
    public function edit(Appointment $appointment): View
    {
        return view('appointments.edit', [
            'appointment' => $appointment,
            'patients' => Patient::getAll(),
            'doctors' => Doctor::getAll(),
            'companies' => Company::active()->get(),
            'unitys' => Unity::active()->get(),
            'biomedicals' => Biomedical::getAll(),
            'priorities' => PriorityEnum::cases(),
        ]);
    }
    
    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        $userId = $user->id;

        try {
            $verify_mail = $user->email;
            $app_name =  config('app.name');

            $appointment->update($request->validated());
            $examsAvailablesIds = $appointment->exams->pluck('id')->toArray();
            
            foreach ($request->exam_ids as $index => $examId) {
                $exam = Exam::find($examId);

                if (! in_array($examId, $examsAvailablesIds)) {
                    $appointment->traceabilities()->create([
                        'exam_id' => $exam->id,
                        'stage_id' => StageEnum::REGISTER->value,
                        'user_id' => $userId,
                        'registered_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                DB::table('appointment_exams')
                    ->updateOrInsert(
                        ['appointment_id' => $appointment->id, 'exam_id' => $examId],
                        [
                            'model_id' => $exam->model_id,
                            'biomedical_id' => $request->exam_biomedicals[$index],
                            'collected_at' => $request->exam_collected_at[$index],
                        ]
                    );
            }

            // appointment create notification send and mail send
            // Find Mail
            $MailAppointment = Appointment::with('doctor', 'patient', 'BookedBy')->where('id', $appointment->id)->first();

            if ($role == 'receptionist') {
                $admin_role = Sentinel::findRoleBySlug('admin');
                $admin_id = $admin_role->users()->with('roles')->pluck('id');
                $patient_id = $appointment->appointment_for;
                $doctor_id = $appointment->appointment_with;

                $fromId = collect();
                $fromId->push($patient_id);
                $fromId->push($admin_id);
                $fromId->push($doctor_id);
                $from_id =  $fromId->flatten();

                foreach ($from_id as $item) {
                    $notification = new Notification();
                    $notification->to_user = $item;
                    $notification->notification_type_id = 1;
                    $notification->title = 'Adicionado';
                    $notification->data = $appointment->id;
                    $notification->from_user = $userId;
                    $notification->save();
                }

                $admin_email = $admin_role->users()->with('roles')->pluck('email');
                $receptionists_doctor_email = User::where('id', $doctor_id)->pluck('email');
                $receptionists_patient_email = User::where('id', $patient_id)->pluck('email');
                // return $receptionists_patient_email;
                $mailSend = collect();
                $mailSend->push($receptionists_patient_email);
                $mailSend->push($receptionists_doctor_email);
                $mailSend->push($admin_email);
                $mailSend = $mailSend->flatten();
                $mailArray = $mailSend->toarray();
                // Mail::send('emails.appointment_create', ['MailAppointment' => $MailAppointment, 'email' => $verify_mail], function ($message) use ($mailArray, $app_name) {
                //     $message->to($mailArray)->subject($app_name . ' ' . 'Novo Atendimento gerado');
                // });

            } elseif ($role == 'biomedical') {
                $admin_role = Sentinel::findRoleBySlug('admin');
                $admin_id = $admin_role->users()->with('roles')->pluck('id');
                $patient_id = $appointment->appointment_for;
                $doctor_id = $appointment->appointment_with;

                $fromId = collect();
                $fromId->push($patient_id);
                $fromId->push($admin_id);
                $fromId->push($doctor_id);
                $from_id =  $fromId->flatten();

                foreach ($from_id as $item) {
                    $notification = new Notification();
                    $notification->to_user = $item;
                    $notification->notification_type_id = 1;
                    $notification->title = 'Adicionado';
                    $notification->data = $appointment->id;
                    $notification->from_user = $userId;
                    $notification->save();
                }

                $admin_email = $admin_role->users()->with('roles')->pluck('email');
                $biomedicalist_doctor_email = User::where('id', $doctor_id)->pluck('email');
                $biomedicalist_patient_email = User::where('id', $patient_id)->pluck('email');
                // return $biomedicalist_patient_email;
                $mailSend = collect();
                $mailSend->push($biomedicalist_patient_email);
                $mailSend->push($biomedicalist_doctor_email);
                $mailSend->push($admin_email);
                $mailSend = $mailSend->flatten();
                $mailArray = $mailSend->toarray();
                // Mail::send('emails.appointment_create', ['MailAppointment' => $MailAppointment, 'email' => $verify_mail], function ($message) use ($mailArray, $app_name) {
                //     $message->to($mailArray)->subject($app_name . ' ' . 'Novo Atendimento gerado');
                // });
            }  
            elseif ($role == 'doctor') {
                $receptionists_doctor_id = ReceptionListDoctor::where('appointment_with', $appointment->appointment_with)->pluck('reception_id');
                $patient_id = $appointment->appointment_for;
                $admin_role = Sentinel::findRoleBySlug('admin');
                $admin_id = $admin_role->users()->with('roles')->pluck('id');

                $fromId = collect();
                $fromId->push($patient_id);
                $fromId->push($admin_id);
                $fromId->push($receptionists_doctor_id);
                $from_id =  $fromId->flatten();

                foreach ($from_id as $item) {
                    $notification = new Notification();
                    $notification->to_user = $item;
                    $notification->notification_type_id = 1;
                    $notification->title = 'Adicionado';
                    $notification->data = $appointment->id;
                    $notification->from_user = $userId;
                    $notification->save();
                }

                $receptionists_doctor_mail = ReceptionListDoctor::where('appointment_with', $appointment->appointment_with)->pluck('reception_id');
                $reception_email = User::whereIN('id', $receptionists_doctor_mail)->pluck('email');
                $patient_email = User::where('id', $patient_id)->pluck('email');
                $admin_email = $admin_role->users()->with('roles')->pluck('email');

                $mailSend = collect();
                $mailSend->push($reception_email);
                $mailSend->push($patient_email);
                $mailSend->push($admin_email);
                $mailSend = $mailSend->flatten();
                $mailArray = $mailSend->toarray();
                // Mail::send('emails.appointment_create', ['MailAppointment' => $MailAppointment, 'email' => $verify_mail], function ($message) use ($mailArray, $app_name) {
                //     $message->to($mailArray)->subject($app_name . ' ' . 'Novo Atendimento gerado');
                // });
            }
        } catch (Exception $error) {
            return redirect()->back()->withError('Algo deu errado! ' . $error->getMessage());
        }

        session()->put('status', 'Atendimento alterado com sucesso.');

        return redirect()->route('appointments.edit', $appointment->id);
    }

    public function status(string $status = ''): View
    {
        return view('appointments.index', [
            'appointments' => Appointment::getByStatus($status),
            'motives' => MotiveEnum::cases(),
        ]);
    }

    public function patient_appointment()
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        $user_id = Sentinel::getUser()->id;
        $limit = Pagination::getLimit();
        $appointment = Appointment::with('doctor', 'timeSlot')
            ->where(['appointment_for' => $user_id])
            ->orderBy('id', 'DESC')
            ->paginate($limit);
        
        return view('patients.patient-appointment', 
            compact('appointment', 'user', 'role')
        );
    }

    public function print(Appointment $appointment): View
    {
        $patient = Patient::firstWhere('user_id', $appointment->appointment_for);

        if (! $patient->patient_cpf && ! $patient->cns) {
            $urlPatient = null;
        } else {
            $cpfEncoded = base64_encode($patient->patient_cpf ?: '0');
            $cnsEncoded = base64_encode($patient->cns ?: '0');
            $urlPatient = route('patient.result.index', [$cpfEncoded, $cnsEncoded]);
        }

        return view('appointments.print', 
            compact('appointment', 'urlPatient')
        );
    }
}
