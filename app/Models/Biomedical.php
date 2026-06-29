<?php

namespace App\Models;

use App\Casts\MaskCns;
use App\Casts\MaskCpf;
use App\Enums\Shared\InactiveEnum;
use App\Helpers\Fill;
use App\Http\Requests\BiomedicalRequest;
use App\Models\ClassCouncil;
use App\Models\Exam\Exam;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Biomedical extends Model
{
    protected $table = 'biomedicalist';

    protected $fillable = [
        'user_id',
        'cpf',
        'cns',
        'class_council_id',
        'issuing_state_id',
        'counsil_number',
        'signature',
        'professional_type_id',
        'is_deleted',
    ];

    protected $casts = [
        'cpf_masked' => MaskCpf::class.':cpf',
        'cns_masked' => MaskCns::class.':cns',
        'is_deleted' => InactiveEnum::class,
    ];

    // METHODS

    public static function getUsersPaginate(int $limit)
    {
        $biomedicalRole = Sentinel::findRoleBySlug('biomedical');
        
        return $biomedicalRole->users()
            ->with(['roles'])
            ->where('is_deleted', 0)
            ->orderByDesc('id')
            ->paginate($limit);
    }

    public static function saveProfileImage(BiomedicalRequest $request)
    {
        if (! $request->new_profile_photo) {
            return '';
        }

        $file = $request->file('new_profile_photo');
        $extention = $file->getClientOriginalExtension();
        $profileImageName = time() . '.' . $extention;
        $file->move(public_path('storage/images/users/'), $profileImageName);

        return $profileImageName;
    }

    public static function saveSignatureImage(BiomedicalRequest $request)
    {
        if (! $request->new_signature) {
            return '';
        }

        $file = $request->file('new_signature');
        $extention = $file->getClientOriginalExtension();
        $signatureImageName = time() . '.' . $extention;
        $file->move(public_path('storage/images/users/signature'), $signatureImageName);

        return $signatureImageName;
    }

    private static function updateImage(User $biomedical, BiomedicalRequest $request)
    {
        $pathAvatar = 'storage/images/users/.' . $biomedical->profile_photo;

        if (File::exists($pathAvatar)) {
            File::delete($pathAvatar);
        }

        $file = $request->file('profile_photo');
        $extention = $file->getClientOriginalExtension();
        $imageName = time() . '.' . $extention;
        $file->move(public_path('storage/images/users'), $imageName);

        return $imageName;
    }

    public static function updateDataToUser(User &$biomedical, BiomedicalRequest $request, int $userId)
    {
        if ($request->hasFile('profile_photo')) {
            $biomedical->profile_photo = self::updateImage($biomedical, $request);
        }

        $biomedical->first_name = $request->first_name;
        $biomedical->mobile = $request->mobile;
        $biomedical->email = $request->email;
        $biomedical->updated_by = $userId;
        $biomedical->is_deleted = $request->is_deleted;
            
        if ($biomedical->password != $request->password) {
            $password = $request->password ? $request->password : config('app.DEFAULT_PASSWORD');
            $biomedical->password = password_hash($password, PASSWORD_DEFAULT);
        }
        
        $biomedical->save();
    }

    public static function updateData(User $biomedical, BiomedicalRequest $request)
    {
        $signatureName = null;
        if ($request->hasFile('signature')) {
            $path = 'storage/images/users/signature.' . $biomedical->signature;

            if (File::exists($path)) {
                File::delete($path);
            }

            $file = $request->file('signature');
            $extension = $file->getClientOriginalExtension();
            $signatureName = time() . '.' . $extension;
            $file->move(public_path('storage/images/users/signature'), $signatureName);
        }
            
        $biomedicals = Biomedical::firstWhere('user_id', $biomedical->id);

        if ($biomedicals) {
            $biomedicals->cpf = $request->cpf;
            $biomedicals->cns = $request->cns;
            $biomedicals->issuing_state_id = $request->issuing_state_id;
            $biomedicals->counsil_number = $request->counsil_number;
            $biomedicals->class_council_id = $request->class_council_id;
            $biomedicals->professional_type_id = $request->professional_type_id;
            $biomedicals->is_deleted = $request->is_deleted;

            if ($signatureName != null) {
                $biomedicals->signature =  $signatureName;
            }

            $biomedicals->save();
        }
    }

    public static function getAll()
    {
        return DB::select(
            "SELECT 
                users.id,
                users.first_name,
                users.last_name
            FROM biomedicalist AS biomedicals
            INNER JOIN users
            ON biomedicals.user_id = users.id
            WHERE biomedicals.is_deleted = 0"
        );
    }

    public static function searchMap(Request $request)
    {
        $biomedicalsIds = DB::select(
            "SELECT appointment_exams.biomedical_id AS id
            FROM appointments
            INNER JOIN appointment_exams
            ON appointments.id = appointment_exams.appointment_id 
            WHERE appointments.appointment_date = ? AND appointment_exams.status = 0
            GROUP BY appointment_exams.biomedical_id", [$request->date]
        );

        if (count($biomedicalsIds) <= 0) {
            return [];
        }

        $biomedicalsList = [];
        foreach ($biomedicalsIds as $biomedical) {
            $biomedicalsList[] = DB::select(
                "SELECT
                    users_biomedicals.id AS biomedical_id,
                    users_biomedicals.first_name AS biomedical_name,
                    exams.id AS exam_id,
                    exams.name AS exam_name
                FROM appointments
               
                INNER JOIN appointment_exams
                ON appointments.id = appointment_exams.appointment_id
                INNER JOIN users AS users_biomedicals
                ON appointment_exams.biomedical_id = users_biomedicals.id
                INNER JOIN exams
                ON appointment_exams.exam_id = exams.id  
    
                WHERE appointments.appointment_date = ? AND appointment_exams.biomedical_id = ?
                AND appointment_exams.status = 0", [$request->date, $biomedical->id]
            );
        }

        $biomedicalsList = json_decode(json_encode($biomedicalsList), true);
        $biomedicals = collect($biomedicalsList)->collapse()->groupBy('biomedical_name')->toArray();

        $registers = [];
        foreach ($biomedicals as $biomedical => $register) {
            $examsNames = [];

            foreach ($register as $item) {
                $examsNames[] = $item['exam_name'];
            }

            $examsTotal = [];
            foreach (array_count_values($examsNames) as $examName => $total) {
                $examsTotal[] = "{$total}x {$examName}";
            }

            $registers[] = [
                'id' => $register[0]['biomedical_id'],
                'name' => $biomedical,
                'date' => $request->date,
                'date_formatted' => date('d/m/Y', strtotime($request->date)),
                'exams' => $examsTotal
            ];
        }

        return $registers;
    }

    public static function getMap(int $id, string $date)
    {
        $biomedicalsList = DB::select(
            "SELECT
                appointments.id AS protocol,
                appointments.guide_number,
                users_biomedicals.id AS biomedical_id,
                users_biomedicals.first_name AS biomedical_name,
                users_patients.first_name AS patient_name,
                patients.patient_cpf AS patient_cpf,
                patients.dob AS patient_date_of_birth,
                patients.gender AS patient_gender,
                exams.id AS exam_id,
                exams.name AS exam_name

            FROM appointments
           
            INNER JOIN appointment_exams
            ON appointments.id = appointment_exams.appointment_id
            INNER JOIN biomedicalist AS biomedicals
            ON appointment_exams.biomedical_id = biomedicals.user_id
            INNER JOIN users AS users_biomedicals
            ON biomedicals.user_id = users_biomedicals.id
            INNER JOIN patients AS patients
            ON appointments.appointment_for = patients.user_id
            INNER JOIN users AS users_patients
            ON patients.user_id = users_patients.id
            INNER JOIN exams
            ON appointment_exams.exam_id = exams.id  

            WHERE appointments.appointment_date = ? AND appointment_exams.biomedical_id = ? 
            AND appointment_exams.status = 0 ORDER BY appointments.guide_number * 1", [$date,  $id]
        );

        if (count($biomedicalsList) <= 0) {
            return [];
        }

        $biomedicalsList = json_decode(json_encode($biomedicalsList), true);
        $biomedicals = collect($biomedicalsList)->groupBy('patient_name')->toArray();

        $registers['patients_map_page_separated'][0] = '';
        $exams = [];
        $examWithMapPageSeparated = [];
        $examOthers = [];
        foreach ($biomedicals as $patient => $register) {
            $exams = [];

            foreach ($register as $index => $item) {

                if ($index === 0) {
                    $registers['biomedical_name'] = $item['biomedical_name'];
                }

                if (in_array($item['exam_id'], Exam::withMapPageSeparated())) {
                    $examWithMapPageSeparated[] = '';

                    [$year, $month, $day] = explode('-', $item['patient_date_of_birth']);

                    $examWithMapPageSeparated[$item['exam_name']][] = [
                        'protocol' => $item['protocol'],
                        'guide_number' => $item['guide_number'],
                        'patient_name' => $item['patient_name'],
                        'patient_cpf' => Fill::maskCpf($item['patient_cpf']),
                        'patient_gender' => $item['patient_gender'] == 'Female' ? 'F' : 'M',
                        'patient_age' => Carbon::createFromDate($year, $month, $day)->diff(Carbon::now())->format('%y anos, %m meses, %d dias'),
                        'exam_id' => $item['exam_id'],
                        'exam_name' => $item['exam_name'],
                    ];

                } else {
                    [$year, $month, $day] = explode('-', $item['patient_date_of_birth']);

                    $examOthers[$item['patient_name']]['protocol'] = $item['protocol'];
                    $examOthers[$item['patient_name']]['guide_number'] = $item['guide_number'];
                    $examOthers[$item['patient_name']]['patient_name'] = $item['patient_name'];
                    $examOthers[$item['patient_name']]['patient_cpf'] = Fill::maskCpf($item['patient_cpf']);
                    $examOthers[$item['patient_name']]['patient_gender'] = $item['patient_gender'] == 'Female' ? 'F' : 'M';
                    $examOthers[$item['patient_name']]['patient_age'] = Carbon::createFromDate($year, $month, $day)->diff(Carbon::now())->format('%y anos, %m meses, %d dias');

                    $examOthers[$item['patient_name']]['exams'][] = 
                        // 'guide_number' => $item['guide_number'],
                        // 'patient_name' => $item['patient_name'],
                        // 'patient_cpf' => Fill::maskCpf($item['patient_cpf']),
                        // 'patient_gender' => $item['patient_gender'] == 'Female' ? 'Feminino' : 'Masculino',
                        // 'patient_age' => Carbon::createFromDate($year, $month, $day)->diff(Carbon::now())->format('%y anos, %m meses, %d dias'),
                        // 'exams' => 
                        Exam::select(['id', 'name'])
                            ->with(['parameters' => fn ($query) =>
                                $query->where('with_printed_map', true)
                            ])
                            ->firstWhere('id', $item['exam_id'])
                    ;
                }
            }

        }

        $registers['patients_map_page_separated'][0] = array_filter($examWithMapPageSeparated);

        if (count($examOthers) >= 1) {
            $registers['patients_map_normal'][] = array_filter($examOthers);
        }

        return $registers;
    }

    public static function getProductionAll(string $dateStart, string $dateEnd)
    {
        $biomedicalsIds = DB::select(
            "SELECT biomedical_id AS id
            FROM appointment_exams
            WHERE collected_at BETWEEN ? AND ? 
            AND (status = 1 OR status = 0)
            GROUP BY biomedical_id", [$dateStart, $dateEnd]
        );

        if (count($biomedicalsIds) <= 0) {
            return [];
        }
    
        $biomedicalsList = [];
        foreach ($biomedicalsIds as $biomedical) {
            $biomedicalsList[] = DB::select(
                "SELECT
                    users_biomedicals.id AS biomedical_id,
                    users_biomedicals.first_name AS biomedical_name,
                    exams.id AS exam_id,
                    exams.name AS exam_name,
                    appointment_exams.collected_at,
                    appointment_exams.re_test
                FROM appointment_exams
                INNER JOIN users AS users_biomedicals
                ON appointment_exams.biomedical_id = users_biomedicals.id
                INNER JOIN exams
                ON appointment_exams.exam_id = exams.id  
    
                WHERE appointment_exams.collected_at BETWEEN ? AND ?
                AND appointment_exams.biomedical_id = ?
                AND (appointment_exams.status = 1 OR appointment_exams.status = 0)", 
                [$dateStart, $dateEnd, $biomedical->id]
            );
        }

        $biomedicalsList = json_decode(json_encode($biomedicalsList), true);
        $biomedicals = collect($biomedicalsList)->collapse()
            ->groupBy('biomedical_name')
            ->toArray();

        $registers = [];
        foreach ($biomedicals as $biomedical => $register) {
            foreach ($register as $key => $value) {
                if (array_key_exists($biomedical, $registers)) {
                    $registers[$biomedical][0]['exams_analyzeds_total'] += $value['re_test'] == '0' ? 1 : 2;
                } else {
                    $registers[$biomedical][] = [
                        'id' => $value['biomedical_id'],
                        'name' => $biomedical,
                        'exams_analyzeds_total' => $value['re_test'] == '0' ? 1 : 2
                    ];
                }
            }
        }

        $results = [];
        foreach ($registers as $biomedicalName => $bio) {
            $results[] = [
                'id' => $bio[0]['id'],
                'name' => $bio[0]['name'],
                'exams_analyzeds_total' => $bio[0]['exams_analyzeds_total'],
            ];
        }

        return $results;
    }

    public static function getProductionByBiomedical(User $biomedical, string $dateStart, string $dateEnd)
    {
        $biomedicalsList = DB::select(
            "SELECT
                users_biomedicals.id AS biomedical_id,
                users_biomedicals.first_name AS biomedical_name,
                exams.id AS exam_id,
                exams.name AS exam_name,
                appointment_exams.collected_at,
                appointment_exams.re_test
            FROM appointments
            
            INNER JOIN appointment_exams
            ON appointments.id = appointment_exams.appointment_id
            INNER JOIN users AS users_biomedicals
            ON appointment_exams.biomedical_id = users_biomedicals.id
            INNER JOIN exams
            ON appointment_exams.exam_id = exams.id  

            WHERE appointment_exams.collected_at BETWEEN ? AND ?
            AND appointment_exams.biomedical_id = ?
            AND (appointment_exams.status = 1 OR appointment_exams.status = 0)", 
            [$dateStart, $dateEnd, $biomedical->id]
        );

        if (count($biomedicalsList) <= 0) {
            return [];
        }

        $registers['name'] = $biomedicalsList[0]->biomedical_name;
        $registers['exam_collected_at'] = [];
        foreach ($biomedicalsList as $index => $item) {

            $collectedAt = date('d/m/Y', strtotime($item->collected_at));
            
            if (array_key_exists($collectedAt, $registers['exam_collected_at'])) {
                if (array_key_exists($item->exam_name, $registers['exam_collected_at'][$collectedAt])) {
                    
                    if ($item->re_test == '0') {
                        $registers['exam_collected_at'][$collectedAt][$item->exam_name] += 1;
                    } else {
                        $registers['exam_collected_at'][$collectedAt][$item->exam_name] += 2;
                    }

                } else {
                    
                    if ($item->re_test == '0') {
                        $registers['exam_collected_at'][$collectedAt][$item->exam_name] = 1;
                    } else {
                        $registers['exam_collected_at'][$collectedAt][$item->exam_name] = 2;
                    }

                } 
            } else {

                if ($item->re_test == '0') {
                    $registers['exam_collected_at'][$collectedAt][$item->exam_name] = 1;
                } else {
                    $registers['exam_collected_at'][$collectedAt][$item->exam_name] = 2;
                }
                
            }
        }

        return $registers;
    }

    public static function getProductionByBiomedicalAmount(User $biomedical, string $dateStart, string $dateEnd)
    {
        $biomedicalsList = DB::select(
            "SELECT
                users_biomedicals.id AS biomedical_id,
                users_biomedicals.first_name AS biomedical_name,
                exams.id AS exam_id,
                exams.name AS exam_name,
                appointment_exams.collected_at,
                appointment_exams.re_test
            FROM appointments
            
            INNER JOIN appointment_exams
            ON appointments.id = appointment_exams.appointment_id
            INNER JOIN users AS users_biomedicals
            ON appointment_exams.biomedical_id = users_biomedicals.id
            INNER JOIN exams
            ON appointment_exams.exam_id = exams.id  

            WHERE appointment_exams.collected_at BETWEEN ? AND ?
            AND appointment_exams.biomedical_id = ?
            AND (appointment_exams.status = 1 OR appointment_exams.status = 0)", 
            [$dateStart, $dateEnd, $biomedical->id]
        );

        if (count($biomedicalsList) <= 0) {
            return [];
        }

        $registers['name'] = $biomedicalsList[0]->biomedical_name;
        $registers['exams_analyzeds'] = [];
        foreach ($biomedicalsList as $index => $item) {
            if (array_key_exists($item->exam_name, $registers['exams_analyzeds'])) {
                
                if ($item->re_test == '0') {
                    $registers['exams_analyzeds'][$item->exam_name] += 1;
                } else {
                    $registers['exams_analyzeds'][$item->exam_name] += 2;
                }

            } else {

                if ($item->re_test == '0') {
                    $registers['exams_analyzeds'][$item->exam_name] = 1;
                } else {
                    $registers['exams_analyzeds'][$item->exam_name] = 2;
                }
                
            }
        }

        return $registers;
    }
    
    // RELATIONSHIPS

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function council()
    {
        return $this->belongsTo(ClassCouncil::class, 'class_council_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'issuing_state_id');
    }

    public function professional()
    {
        return $this->belongsTo(ProfessionalType::class, 'professional_type_id');
    }
    
}
