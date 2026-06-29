<?php

namespace App\Models;

use App\Models\Appointment\Appointment;
use App\Casts\MaskCns;
use App\Casts\MaskCpf;
use App\Enums\Shared\InactiveEnum;
use App\Helpers\Date;
use App\Helpers\Fill;
use App\Http\Requests\PatientRequest;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Patient extends Model
{
    protected $table = 'patients';

    protected $fillable = [
        'user_id',
        'age',
        'gender',
        'address',
        'is_deleted',
        'dob',
        'patient_cpf',
        'cns',
        'mother_name',
        'patient_social_name',
    ];

    protected $casts = [
        'cpf_masked' => MaskCpf::class.':patient_cpf',
        'cns_masked' => MaskCns::class.':cns',
        'is_deleted' => InactiveEnum::class,
    ];

    // ACCESSORS

    protected function genderName(): Attribute
    {
        return Attribute::make(
            get: fn () => 
                match ($this->gender) {
                    'Male' => 'Masculino',
                    'Female' => 'Feminino',
                    'Trans' => 'Trans',
                    'LGBT' => 'LGBT',
                    default => 'Não Declarado',
                }
        );
    }

    protected function age(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Date::age($this->dob);
            }
        );
    }

    // METHODS

    public function ageExtended(string|null $appointmentAt = null)
    {
        [$year, $month, $day] = explode('-', $this->dob);

        return Carbon::createFromDate($year, $month, $day)
            ->diff($appointmentAt)
            ->format('%y anos, %m meses, %d dias');
    }

    public function ageYear(string|null $appointmentAt)
    {
        [$year, $month, $day] = explode('-', $this->dob);

        $ageYear = Carbon::createFromDate($year, $month, $day)
            ->diff($appointmentAt)
            ->format('%y');

        return (int) $ageYear;
    }

    public function ageMonth(string|null $appointmentAt)
    {
        [$year, $month, $day] = explode('-', $this->dob);

        $ageMonth = Carbon::createFromDate($year, $month, $day)
            ->diff($appointmentAt)
            ->format('%m');

        return (int) $ageMonth;
    }

    public function ageDay(string|null $appointmentAt)
    {
        [$year, $month, $day] = explode('-', $this->dob);

        $ageDay = Carbon::createFromDate($year, $month, $day)
            ->diff($appointmentAt)
            ->format('%d');

        return (int) $ageDay;
    }

    public static function getUsersPaginate(int $limit)
    {
        $patientRole = Sentinel::findRoleBySlug('patient');

        return $patientRole->users()
            ->with('roles')
            ->orderByDesc('id')
            ->paginate($limit);
    }

    public static function saveImage(PatientRequest $request)
    {
        if (! $request->new_profile_photo) {
            return '';
        }

        $file = $request->file('new_profile_photo');
        $extention = $file->getClientOriginalExtension();;
        $imageName = time() . '.' . $extention;
        $file->move(public_path('storage/images/users'), $imageName);

        return $imageName;
    }

    private static function updateImage(User $patient, PatientRequest $request)
    {
        $pathAvatar = 'storage/images/users/.' . $patient->profile_photo;

        if (File::exists($pathAvatar)) {
            File::delete($pathAvatar);
        }

        $file = $request->file('profile_photo');
        $extention = $file->getClientOriginalExtension();
        $imageName = time() . '.' . $extention;
        $file->move(public_path('storage/images/users'), $imageName);

        return $imageName;
    }

    public static function updateDataToUser(User &$patient, PatientRequest $request, int $userId)
    {
        if ($request->hasFile('profile_photo')) {
            $patient->profile_photo = self::updateImage($patient, $request);
        }

        $patient->first_name = $request->first_name;
        $patient->mobile = $request->mobile;
        $patient->is_deleted = $request->is_deleted;

        if ($patient->password != $request->password) {
            $password = $request->password ? $request->password : config('app.DEFAULT_PASSWORD');
            $patient->password = password_hash($password, PASSWORD_DEFAULT);
        }

        $patient->email = $request->email;
        $patient->updated_by = $userId;
        $patient->save();
    }

    public static function updateData(User $patient, PatientRequest $request)
    {
        $patient_info = Patient::firstWhere('user_id', $patient->id);

        if ($patient_info == null) {
            $patient_info = new Patient();
            $patient_info->dob = $request->dob;
            $patient_info->cns = $request->cns;
            $patient_info->patient_cpf = $request->patient_cpf;
            $patient_info->mother_name = $request->mother_name;
            $patient_info->patient_social_name = $request->patient_social_name;
            $patient_info->gender = $request->gender;
            $patient_info->address = $request->address;
            $patient_info->is_deleted = $request->is_deleted;
            $patient_info->user_id = $patient->id;
            $patient_info->save();
        } else {
            $patient_info->dob = $request->dob;
            $patient_info->cns = $request->cns;
            $patient_info->patient_cpf = $request->patient_cpf;
            $patient_info->mother_name = $request->mother_name;
            $patient_info->patient_social_name = $request->patient_social_name;
            $patient_info->gender = $request->gender;
            $patient_info->address = $request->address;
            $patient_info->is_deleted = $request->is_deleted;
            $patient_info->user_id = $patient->id;
            $patient_info->save();
        }
    }

    public static function getAll()
    {
        return DB::select(
            "SELECT 
                users.id,
                users.first_name,
                users.last_name
            FROM patients
            INNER JOIN users
            ON patients.user_id = users.id
            WHERE patients.is_deleted = 0"
        );
    }

    public static function search(string|null $value, string $status)
    {
        if (! $value) {
            return [];
        }

        if ($status == 'all') {
            $patientsList = DB::select(
                "SELECT 
                    users.id, users.first_name, users.last_name, 
                    patients.dob, patients.patient_cpf, patients.patient_social_name, patients.cns, users.is_deleted
                FROM patients
                INNER JOIN users
                ON patients.user_id = users.id
                WHERE users.first_name LIKE '%{$value}%'
                OR (patients.patient_cpf LIKE '%{$value}%'
                OR patients.cns LIKE '%{$value}%')
                LIMIT 50"
            );
        } else {
            $patientsList = DB::select(
                "SELECT 
                    users.id, users.first_name, users.last_name, 
                    patients.dob, patients.patient_cpf, patients.patient_social_name, patients.cns, users.is_deleted
                FROM patients
                INNER JOIN users
                ON patients.user_id = users.id
                WHERE users.is_deleted = 0 AND users.first_name LIKE '%{$value}%'
                OR (patients.patient_cpf LIKE '%{$value}%'
                OR patients.cns LIKE '%{$value}%')
                LIMIT 50"
            );
        }

        $patients = [];
        foreach ($patientsList as $patient) {
            $patients[] = [
                'id' => $patient->id,
                'name' => "{$patient->first_name} {$patient->last_name}",
                'name_social' => $patient->patient_social_name ? "<strong>({$patient->patient_social_name})</strong>" : '',
                'date_of_birth_formatted' => date('d/m/Y', strtotime($patient->dob)),
                'cpf_masked' => Fill::maskCpf($patient->patient_cpf),
                'cns_masked' => Fill::maskCns($patient->cns),
                'is_deleted' => $patient->is_deleted,
            ];
        }

        return $patients;
    }

    public static function searchMap(Request $request)
    {
        $query = [];

        if ($request->patient) {
            $query[] = "users.first_name LIKE '%{$request->patient}%'";
        }

        if ($request->date) {
            $query[] = "appointments.appointment_date = '{$request->date}'";
        }

        $query = implode(' AND ', $query);

        $patientsList = DB::select(
            "SELECT 
                appointments.id,
                users.first_name,
                patients.patient_cpf,
                appointments.appointment_date,
                appointments.status
            FROM appointments
            INNER JOIN users
            ON appointments.appointment_for = users.id
            INNER JOIN patients
            ON users.id = patients.user_id
            WHERE " . $query . " AND appointments.status = 0;"
        );

        if (count($patientsList) <= 0) {
            return [];
        }

        $patients = [];

        foreach ($patientsList as $patient) {
            $patients[] = [
                'protocol' => $patient->id,
                'name' => $patient->first_name,
                'cpf' => Fill::maskCpf($patient->patient_cpf),
                'registered_at' => date('d/m/Y', strtotime($patient->appointment_date)),
                'status' => $patient->status
            ];
        }
        
        return $patients;
    }

    // RELATIONSHIPS

    function appointment() 
    {
        return $this->hasMany(Appointment::class,'appointment_for','id');
    }

    function user() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
