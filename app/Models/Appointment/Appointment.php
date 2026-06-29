<?php

namespace App\Models\Appointment;

use App\Enums\Appointment\PriorityEnum;
use App\Enums\Routine\StageEnum;
use App\Helpers\Fill;
use App\Helpers\Pagination;
use App\Http\Requests\Appointment\SaveResultRequest;
use App\Models\Appointment\Result;
use App\Models\BiomedicalListDoctor;
use App\Models\Company;
use App\Models\DoctorAvailableSlot;
use App\Models\Exam\Exam;
use App\Models\Invoice;
use App\Models\Prescription;
use App\Models\ReceptionListDoctor;
use App\Models\Routine\Traceability;
use App\Models\Unity;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Appointment extends Model
{
    protected $table = 'appointments';

    protected $fillable = [
        'appointment_for',
        'appointment_with',
        'unity_id',
        'company_id',
        'appointment_date',
        'appointment_time',
        'booked_by',
        'delivery_date',
        'fast',
        'priority_id',
        'dum',
        'guide_number',
        'status',
        'is_deleted',
        'checked_at',
        'access_key',
        'observation',
    ];

    protected $casts = [
        'priority_id' => PriorityEnum::class,
    ];

    // ACCESSORS

    public function getRegisteredAtFormattedAttribute()
    {
        return date('d/m/Y', strtotime($this->appointment_date));
    }

    public function getCheckedAtFormattedAttribute()
    {
        return date('d/m/Y', strtotime($this->checked_at));
    }

    // METHODS

    public static function totalByDoctor($doctorId)
    {
        $total = Appointment::where(function ($query) use ($doctorId) {
            $query->orWhere('appointment_with', $doctorId);
            $query->orWhere('booked_by', $doctorId);
        })->get();

        return [
            'total_appointment' => $total->count(),
            'revenue' => 0,
            'pending_bill' => $total->count(),
        ];
    }

    public static function totalByBiomedical(int|null $biomedicalId)
    {
        $biomedicals_doctor_id = BiomedicalListDoctor::where('biomedical_id', $biomedicalId)->pluck('doctor_id');

        $total = Appointment::where(function ($query) use ($biomedicalId, $biomedicals_doctor_id) {
            $query->whereIn('appointment_with', $biomedicals_doctor_id);
            $query->orWhereIn('booked_by', $biomedicals_doctor_id);
            $query->orWhere('booked_by', $biomedicalId);
        })->get();

        return [
            'total_appointment' => $total->count(),
            'revenue' => 0,
            'pending_bill' => $total->count(),
        ];
    }

    public static function totalByReceptionist($receptionistId)
    {
        $receptionists_doctor_id = ReceptionListDoctor::where('reception_id', $receptionistId)->pluck('doctor_id');

        $total = Appointment::where(function ($query) use ($receptionistId, $receptionists_doctor_id) {
            $query->whereIn('appointment_with', $receptionists_doctor_id);
            $query->orWhereIn('booked_by', $receptionists_doctor_id);
            $query->orWhere('booked_by', $receptionistId);
        })->get();

        return [
            'total_appointment' => $total->count(),
            'revenue' => 0,
            'pending_bill' => $total->count(),
        ];
    }

    public static function byDoctorId($doctorId, $limit)
    {
        return Appointment::where(function ($query) use ($doctorId) {
            $query->orWhere('appointment_with', $doctorId);
            $query->orWhere('booked_by', $doctorId);
        })
            ->orderByDesc('id')
            ->paginate($limit, '*', 'appointment');
    }

    public static function byBiomedicalId($biomedicalId, $limit)
    {
        $biomedicals_doctor_id = BiomedicalListDoctor::where('biomedical_id', $biomedicalId)->pluck('doctor_id');

        return Appointment::where(function ($query) use ($biomedicalId, $biomedicals_doctor_id) {
                $query->whereIn('appointment_with', $biomedicals_doctor_id);
                $query->orWhereIn('booked_by', $biomedicals_doctor_id);
                $query->orWhere('booked_by', $biomedicalId);
            })
            ->orderByDesc('id')
            ->paginate($limit, '*', 'appointments');
    }

    public static function byReceptionistId($receptionistId, $limit)
    {
        $receptionists_doctor_id = ReceptionListDoctor::where('reception_id', $receptionistId)->pluck('doctor_id');

        return Appointment::where(function ($query) use ($receptionistId, $receptionists_doctor_id) {
                $query->whereIn('appointment_with', $receptionists_doctor_id);
                $query->orWhereIn('booked_by', $receptionists_doctor_id);
                $query->orWhere('booked_by', $receptionistId);
            })
            ->orderByDesc('id')
            ->paginate($limit, '*', 'appointments');
    }

    public static function byPatientId($patientId, $limit)
    {
        return Appointment::with('doctor')
            ->where('appointment_for', $patientId)
            ->orderByDesc('id')
            ->paginate($limit, '*', 'appointment');
    }

    public static function totalByPatient($patientId)
    {
        $total = Appointment::where('appointment_for', $patientId)->get();
        $invoice = Invoice::where('patient_id', $patientId)->pluck('id');

        return [
            'total_appointment' => $total->count(),
            'revenue' => 0,
            'pending_bill' => $total->count(),
        ];
    }

    public static function getByStatus(string $status)
    {
        $limit = Pagination::getLimit();
        $statusCode = match ($status) {
            'pending' => 0,
            'completed' => 1,
            'canceled' => 2,
            default => 0,
        };
        
        return DB::table('appointments')
            ->join('users AS user_doctor', 'appointments.appointment_with', '=', 'user_doctor.id')
            ->join('users AS user_patient', 'appointments.appointment_for', '=', 'user_patient.id')
            ->join('patients', 'patients.user_id', '=', 'user_patient.id')
            ->select([
                'appointments.id AS protocol',
                'user_patient.id AS patient_id',
                'user_patient.first_name AS patient_name',
                'patients.patient_social_name AS patient_name_social',
                'user_doctor.first_name AS doctor_name',
                'appointments.status',
                'appointments.priority_id',
                'appointments.created_at'
            ])
            ->where('appointments.status', $statusCode)
            ->whereRaw("appointments.appointment_date NOT BETWEEN ? AND ?", ['2024-04-16', '2024-04-19'])
            ->orderByDesc('appointments.id')
            ->paginate($limit);
    }

    public static function searchBy(string $type, string|null $value)
    {
        $query = '';

        if ($type === 'date') {
            $query = "DATE(appointments.created_at) = '{$value}'";
        }

        if ($type === 'protocol') {
            $query = "appointments.id = '{$value}'";
        }

        if ($type === 'patient') {
            $query = "users_patients.first_name LIKE '%{$value}%' LIMIT 100";
        }

        $appointmentsList = DB::select(
            "SELECT
                appointments.id AS protocol,
                users_doctors.first_name AS user_doctor,
                users_patients.first_name AS user_patient,
                patients.patient_cpf AS user_patient_cpf,
                patients.cns AS user_patient_cns,
                patients.dob AS user_patient_date_of_birth,
                appointments.created_at,
                appointments.status AS appointment_status,
                appointment_exams.status AS exam_status,
                exams.name AS exam_name
            FROM appointments

            INNER JOIN appointment_exams
            ON appointments.id = appointment_exams.appointment_id

            INNER JOIN exams
            ON appointment_exams.exam_id = exams.id

            INNER JOIN users AS users_doctors
            ON appointments.appointment_with = users_doctors.id

            INNER JOIN users AS users_patients
            ON appointments.appointment_for = users_patients.id

            INNER JOIN patients
            ON appointments.appointment_for = patients.user_id

            WHERE " . $query
        );

        if (count($appointmentsList) <= 0) {
            return [];
        }

        $appointments = [];
        $protocols = [];
        foreach ($appointmentsList as $appointment) {

            if (! in_array($appointment->protocol, $protocols)) {
                $protocols[] = $appointment->protocol;

                [$year, $month, $day] = explode('-', $appointment->user_patient_date_of_birth);
                $age = Carbon::createFromDate($year, $month, $day)
                    ->diff(Carbon::now())
                    ->format('%y anos, %m meses, %d dias');

                $appointments[$appointment->protocol]['protocol'] = $appointment->protocol;
                $appointments[$appointment->protocol]['doctor_name'] = $appointment->user_doctor;
                $appointments[$appointment->protocol]['patient_name'] = $appointment->user_patient;
                $appointments[$appointment->protocol]['patient_cpf'] = Fill::maskCpf($appointment->user_patient_cpf);
                $appointments[$appointment->protocol]['patient_cns'] = Fill::maskCns($appointment->user_patient_cns);
                $appointments[$appointment->protocol]['patient_age'] = $age;
                $appointments[$appointment->protocol]['date'] = date('d/m/Y H:i:s', strtotime($appointment->created_at));
                $appointments[$appointment->protocol]['status'] = $appointment->appointment_status;

                // $appointments[$appointment->protocol]['exams'] = "{$appointment->exam_name}|{$appointment->exam_status};";

                $appointments[$appointment->protocol]['exams'][] = [
                    'name' => $appointment->exam_name,
                    'status' => $appointment->exam_status
                ];

            } else {
                // $appointments[$appointment->protocol]['exams'] .= "{$appointment->exam_name}|{$appointment->exam_status};";

                $appointments[$appointment->protocol]['exams'][] = [
                    'name' => $appointment->exam_name,
                    'status' => $appointment->exam_status
                ];

            }
        }

        return array_values($appointments);
    }

    public static function getDateDelivery()
    {
        $dateCurrent = Carbon::now();
        $dateCurrent->addWeekdays(2);

        return $dateCurrent->format('Y-m-d');
    }

    public static function resultIsNotInserted(SaveResultRequest $request)
    {
        return DB::table('appointment_exams')
            ->where('appointment_id', $request->appointment_id)
            ->where('exam_id', $request->exam_id)
            ->where('status','<>', 1)
            ->get()
            ->isNotEmpty();
    }

    public static function saveResult(SaveResultRequest $request)
    {
        $results = [];

        foreach ($request->parameter_id as $index => $parameterId) {
            DB::table('results')->insert([
                'appointment_id' => $request->appointment_id,
                'exam_id' => $request->exam_id,
                'parameter_id' => $parameterId,
                'result' => $request->parameter_value[$index],
                'result_status' => 'saved',
            ]);

            $results[] = $request->parameter_value[$index];
        }

        DB::table('appointment_exams')
            ->where('appointment_id', $request->appointment_id)
            ->where('exam_id', $request->exam_id)
            ->update(['status' => 1]);

        DB::table('appointments')
            ->where('id', $request->appointment_id)
            ->update(['checked_at' => date('Y-m-d')]);

        DB::table('routine_traceabilities')
            ->insert([
                'appointment_id' => $request->appointment_id,
                'exam_id' => $request->exam_id,
                'stage_id' => StageEnum::INSERT_RESULT->value,
                'user_id' => Sentinel::getUser()->id,
                'result' => implode(',', $results),
                'registered_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function updateResult(SaveResultRequest $request)
    {
        $results = [];

        foreach ($request->parameter_id as $index => $parameterId) {
            DB::table('results')
                ->where('appointment_id', $request->appointment_id)
                ->where('exam_id', $request->exam_id)
                ->where('parameter_id', $parameterId)
                ->update([
                    'result' => $request->parameter_value[$index],
                    'result_status' => 'saved'
                ]);

            $results[] = $request->parameter_value[$index];
        }

        DB::table('appointment_exams')
            ->where('appointment_id', $request->appointment_id)
            ->where('exam_id', $request->exam_id)
            ->update(['status' => 1]);

        DB::table('appointments')
            ->where('id', $request->appointment_id)
            ->update(['checked_at' => date('Y-m-d')]);

        DB::table('routine_traceabilities')
            ->insert([
                'appointment_id' => $request->appointment_id,
                'exam_id' => $request->exam_id,
                'stage_id' => StageEnum::EDIT_RESULT->value,
                'user_id' => Sentinel::getUser()->id,
                'result' => implode(',', $results),
                'registered_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public static function routineBy(string|null $date, int|null $unityId)
    {
        return DB::select(
            "SELECT
                COUNT(DISTINCT user_patients.first_name) AS patients_total,
                SUM(IF(appointment_exams.status = 1, 1, 0)) AS exams_total,
                SUM(IF(appointment_exams.re_test = 1, 1, 0)) AS retests_total
            FROM appointments
            INNER JOIN users AS user_patients
            ON appointments.appointment_for = user_patients.id
            INNER JOIN appointment_exams
            ON appointments.id = appointment_exams.appointment_id
            WHERE appointments.appointment_date = ? 
            AND appointments.unity_id = ?", [$date, $unityId]
        );
    }

    public static function getTodayData(string|null $date, int|null $unityId, string|null $status)
    {
        $sufixQuery = $status == 'success'
            ? "(appointment_exams.status = 1)"
            : "(appointment_exams.re_test = 1)";

        $appointmentList = DB::select(
            "SELECT
                appointments.appointment_for AS patient_id,
                users_patients.first_name AS patient_name,
                patients.dob AS patient_date_of_birth,
                patients.gender AS patient_gender,
                appointments.id AS protocol,
                companies.name AS company_name,
                unitys.short_name AS unity_short_name,
                appointments.guide_number,
                appointments.appointment_date AS registered_at,
                appointments.checked_at,
                users_doctors.first_name AS doctor_name,
                appointment_exams.exam_id,
                appointment_exams.model_id,
                users_biomedicals.id AS biomedical_id,
                appointment_exams.re_test,
                appointment_exams.observation AS re_test_motive,
                appointment_exams.status
            FROM appointments

            INNER JOIN companies
            ON appointments.company_id = companies.id
            INNER JOIN unitys
            ON appointments.unity_id = unitys.id
            INNER JOIN users AS users_patients
            ON appointments.appointment_for = users_patients.id
            INNER JOIN patients
            ON appointments.appointment_for = patients.user_id
            INNER JOIN users AS users_doctors
            ON appointments.appointment_with = users_doctors.id
            INNER JOIN appointment_exams
            ON appointments.id = appointment_exams.appointment_id
            INNER JOIN users AS users_biomedicals
            ON appointment_exams.biomedical_id = users_biomedicals.id

            WHERE appointments.appointment_date = ? AND appointments.unity_id = ? 
            AND " . $sufixQuery, [$date, $unityId]
        );

        $appointments = [];
        $protocols = [];
        // $exams = [];
        foreach ($appointmentList as $appointment) {

            if (! in_array($appointment->protocol, $protocols)) {
                $protocols[] = $appointment->protocol;

                [$year, $month, $day] = explode('-', $appointment->patient_date_of_birth);
                $ageDay = Carbon::createFromDate($year, $month, $day)->diff($appointment->registered_at)->format('%d');
                $ageMonth = Carbon::createFromDate($year, $month, $day)->diff($appointment->registered_at)->format('%m');
                $ageYear = Carbon::createFromDate($year, $month, $day)->diff($appointment->registered_at)->format('%y');

                $appointments[$appointment->protocol]['protocol'] = $appointment->protocol;
                $appointments[$appointment->protocol]['company_name'] = $appointment->company_name;
                $appointments[$appointment->protocol]['unity_short_name'] = $appointment->unity_short_name;
                $appointments[$appointment->protocol]['guide_number'] = $appointment->guide_number;
                $appointments[$appointment->protocol]['doctor_name'] = $appointment->doctor_name;
                $appointments[$appointment->protocol]['patient_id'] = $appointment->patient_id;
                $appointments[$appointment->protocol]['patient_name'] = $appointment->patient_name;
                $appointments[$appointment->protocol]['patient_gender'] = $appointment->patient_gender;
                $appointments[$appointment->protocol]['patient_gender_name'] = $appointment->patient_gender == 'Female' ? 'Feminino' : 'Masculino';
                $appointments[$appointment->protocol]['patient_age_day'] = (int) $ageDay;
                $appointments[$appointment->protocol]['patient_age_month'] = (int) $ageMonth;
                $appointments[$appointment->protocol]['patient_age_year'] = (int) $ageYear;
                $appointments[$appointment->protocol]['patient_age'] = "{$ageYear} anos, {$ageMonth} meses, {$ageDay} dias";
                $appointments[$appointment->protocol]['registered_at'] = date('d/m/Y', strtotime($appointment->registered_at));
                $appointments[$appointment->protocol]['checked_at'] = $appointment->checked_at;

                // $exams[] = $appointment->exam_id;
                $exam = Exam::select(['id', 'name', 'exam_editor'])
                    ->with(['parameters', 'filters'])
                    ->firstWhere('id', $appointment->exam_id);

                $appointments[$appointment->protocol]['exams'][] = [
                    'object' => $exam,
                    'model_id' => $appointment->model_id,
                    'biomedical_id' => $appointment->biomedical_id,
                    're_test' => $appointment->re_test,
                    're_test_motive' => $appointment->re_test_motive,
                    'status' => $appointment->status,
                ];

            } else {
                // if (! in_array($appointment->exam_id, $exams)) {
                    // $exams[] = $appointment->exam_id;
                $exam = Exam::select(['id', 'name', 'exam_editor'])
                    ->with(['parameters', 'filters'])
                    ->firstWhere('id', $appointment->exam_id);

                if (in_array($appointment->exam_id, Exam::listContentLong())) {
                    array_unshift($appointments[$appointment->protocol]['exams'], [
                        'object' => $exam,
                        'model_id' => $appointment->model_id,
                        'biomedical_id' => $appointment->biomedical_id,
                        're_test' => $appointment->re_test,
                        're_test_motive' => $appointment->re_test_motive,
                        'status' => $appointment->status,
                    ]);
                } else {
                    $appointments[$appointment->protocol]['exams'][] = [
                        'object' => $exam,
                        'model_id' => $appointment->model_id,
                        'biomedical_id' => $appointment->biomedical_id,
                        're_test' => $appointment->re_test,
                        're_test_motive' => $appointment->re_test_motive,
                        'status' => $appointment->status,
                    ];
                }
            }
           
        }

        return array_values($appointments);
    }

    // RELATIONSHIPS

    public function patient(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class, 
            foreignKey: 'appointment_for',
        );
    }

    public function BookedBy(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class, 
            foreignKey: 'booked_by',
        );
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class, 
            foreignKey: 'appointment_with',
        );
    }

    public function unity(): BelongsTo
    {
        return $this->belongsTo(
            related: Unity::class, 
            foreignKey: 'unity_id',
        );
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(
            related: Company::class, 
            foreignKey: 'company_id',
        );
    }

    public function receptionlist_doctor(): HasMany
    {
        return $this->hasMany(
            ReceptionListDoctor::class, 
            'doctor_id', 
            'appointment_with'
        );
    }
    
    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(
            Exam::class, 
            'appointment_exams', 
            'appointment_id', 
            'exam_id'
        )->withPivot([
            'model_id', 
            'biomedical_id', 
            'collected_at', 
            'status', 
            're_test', 
            'observation',
            'user_id',
            'updated_at',
        ]);
    }
    
    public function results(): HasMany
    {
        return $this->hasMany(
            related: Result::class, 
            foreignKey: 'appointment_id',
        );
    }

    public function timeSlot()
    {
        return $this->hasOne(DoctorAvailableSlot::class, 'id', 'available_slot');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class)->where('payment_status','Paid');
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class)->where('is_deleted',0);
    }

    public function traceabilities(): HasMany
    {
        return $this->hasMany(
            related: Traceability::class, 
            foreignKey: 'appointment_id',
        );
    }

    public function documents(): HasMany
    {
        return $this->hasMany(
            related: Document::class, 
            foreignKey: 'appointment_id',
        );
    }
}
