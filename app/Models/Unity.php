<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Shared\ActiveEnum as Status;
use Illuminate\Support\Facades\DB;

class Unity extends Model
{
    protected $table = 'unitys';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    // METHODS

    public static function getProductionAll(string $dateStart, string $dateEnd)
    {
        $results = [];
        $unitysList = DB::select(
            "SELECT
                unitys.id AS unity_id,
                unitys.name AS unity_name,
                appointment_exams.re_test
            FROM appointment_exams
            INNER JOIN exams
            ON appointment_exams.exam_id = exams.id  
            INNER JOIN appointments
            ON appointment_exams.appointment_id = appointments.id
            INNER JOIN unitys
            ON appointments.unity_id = unitys.id
            WHERE appointment_exams.collected_at BETWEEN ? AND ?
            AND (appointment_exams.status = 1 OR appointment_exams.status = 0)", 
            [$dateStart, $dateEnd]
        );

        if (count($unitysList) <= 0) {
            return [];
        }

        foreach ($unitysList as $item) {
            if (array_key_exists($item->unity_name, $results)) { 
                if ($item->re_test == '0') {
                    $results[$item->unity_name]['exams_total'] += 1;
                } else {
                    $results[$item->unity_name]['exams_total'] += 2;
                }
            } else {
                if ($item->re_test == '0') {
                    $results[$item->unity_name]['exams_total'] = 1;
                } else {
                    $results[$item->unity_name]['exams_total'] = 2;
                }

                $results[$item->unity_name]['id'] = $item->unity_id;
            }
        }

        return $results;
    }

    public static function getProductionByUnity(Unity $unity, string $dateStart, string $dateEnd)
    {
        $unitysList = DB::select(
            "SELECT
                exams.id AS exam_id,
                exams.name AS exam_name,
                appointment_exams.collected_at,
                appointment_exams.re_test
            FROM appointments
            
            INNER JOIN unitys
            ON appointments.unity_id = unitys.id
            INNER JOIN appointment_exams
            ON appointments.id = appointment_exams.appointment_id
            INNER JOIN exams
            ON appointment_exams.exam_id = exams.id  

            WHERE appointment_exams.collected_at BETWEEN ? AND ?
            AND unitys.id = ?
            AND (appointment_exams.status = 1 OR appointment_exams.status = 0)", 
            [$dateStart, $dateEnd, $unity->id]
        );

        if (count($unitysList) <= 0) {
            return [];
        }

        $registers['name'] = $unity->name;
        $registers['exams_analyzeds'] = [];
        foreach ($unitysList as $index => $item) {
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

    // SCOPES

    public function scopeActive($query)
    {
        return $query->where('is_active', Status::ACTIVE->value);
    }

}
