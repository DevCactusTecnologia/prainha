<?php

namespace App\Models\Routine;

use App\Enums\Routine\StageEnum;
use App\Helpers\Fill;
use App\Models\Appointment\Appointment;
use App\Models\Exam\Exam;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Traceability extends Model
{
    protected $table = 'routine_traceabilities';

    protected $fillable = [
        'appointment_id',
        'exam_id',
        'stage_id',
        'user_id',
        'result',
        'registered_at',
    ];

    protected $casts = [
        'stage_id' => StageEnum::class
    ];

    public $timestamps = false;

    // METHODS

    public static function searchByProtocol(int|string|null $protocol)
    {
        $results = [];
        $users = DB::select(
            "SELECT 
                appointments.id,
                patients_users.first_name AS patient_name,
                patients.dob AS patient_date_of_birth,
                patients.patient_cpf AS patient_cpf,
                patients.cns AS patient_cns,
                doctors_users.first_name AS doctor_name
            FROM appointments
            INNER JOIN users AS patients_users
            ON appointments.appointment_for = patients_users.id
            INNER JOIN patients
            ON appointments.appointment_for = patients.user_id
            INNER JOIN users AS doctors_users
            ON appointments.appointment_with = doctors_users.id
            WHERE appointments.id = ?", [$protocol]
        );

        if (count($users) <= 0) {
            return [];
        }

        self::hydrateBy('users', $users, $results);

        $exams = DB::select(
            "SELECT 
                exams.id AS exam_id,
                exams.name AS exam_name,
                biomedicals_users.first_name AS biomedical_name,
                appointment_exams.collected_at
            FROM appointments
            INNER JOIN appointment_exams 
            ON appointments.id = appointment_exams.appointment_id
            INNER JOIN exams
            ON appointment_exams.exam_id = exams.id
            INNER JOIN users AS biomedicals_users
            ON appointment_exams.biomedical_id = biomedicals_users.id
            WHERE appointments.id = ?", [$protocol]
        );

        self::hydrateBy('exams', $exams, $results);

        return $results;
    }

    private static function hydrateBy(string $type, mixed $registers, array &$results)
    {
        if ($type == 'users') {
            $results['id'] = $registers[0]->id;
            $results['doctor'] = ['name' => mb_strtoupper($registers[0]->doctor_name)];
            $results['patient'] = [
                'name' => $registers[0]->patient_name,
                'date_of_birth' => date('d/m/Y', strtotime($registers[0]->patient_date_of_birth)),
                'cpf' => Fill::maskCpf($registers[0]->patient_cpf),
                'cns' => Fill::maskCns($registers[0]->patient_cns),
            ];
        }

        if ($type == 'exams') {
            foreach ($registers as $register) {
                $results['exams'][] = [
                    'id' => $register->exam_id,
                    'name' => $register->exam_name,
                    'biomedical_name' => mb_strtoupper($register->biomedical_name),
                    'collected_at' => date('d/m/Y', strtotime($register->collected_at)),
                ];
            }
        }
    }

    public static function searchHistoric(Request $request)
    {
        $historics = DB::select(
            "SELECT 
                traceabilities.stage_id,
                traceabilities.registered_at,
                users.first_name,
                traceabilities.result
            FROM routine_traceabilities AS traceabilities
            INNER JOIN users
            ON traceabilities.user_id = users.id
            WHERE traceabilities.appointment_id = ? AND traceabilities.exam_id = ?
            ORDER BY traceabilities.registered_at ASC", [
                $request->appointment_id,
                $request->exam_id,
        ]);

        return self::hydrateHistoric($historics);
    }

    private static function hydrateHistoric(array $registers)
    {
        $results = [];

        foreach ($registers as $register) {
            $results[] = [
                'stage' => [
                    'icon' => StageEnum::tryFrom($register->stage_id)?->getIcon(),
                    'name' => StageEnum::tryFrom($register->stage_id)?->getName(),
                ],
                'registered_at' => date('d/m/Y H:i:s', strtotime($register->registered_at)),
                'user' => [
                    'name' => $register->first_name,
                ],
                'result' => $register->result ?? '',
            ];
        }

        return $results;
    }

    // RELATIONSHIPS

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
