<?php

namespace App\Services\Routine;

use App\Models\Unity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagService
{
    public function total(Request $request): int
    {
        $tags = DB::select(
            "SELECT
                IFNULL(exams.label_group, 0) AS exam_tag,
                exams.abbreviation AS exam_abbreviation,
                appointments.id AS protocol
            FROM appointment_exams
            INNER JOIN exams
            ON appointment_exams.exam_id = exams.id  
            INNER JOIN appointments
            ON appointment_exams.appointment_id = appointments.id
            INNER JOIN users AS users_patients
            ON appointments.appointment_for = users_patients.id
            INNER JOIN patients
            ON appointments.appointment_for = patients.user_id
            WHERE appointment_exams.collected_at BETWEEN ? AND ?
            AND (appointment_exams.status = 1) AND (appointments.unity_id = ?)", [
                $request->date_start,
                $request->date_end,
                $request->unity_id,
            ]
        );

        if (count($tags) <= 0) {
            return 0;
        }

        $items = [];
        foreach ($tags as $tag) {
            $items[$tag->protocol]['tag_group'][$tag->exam_tag][$tag->exam_abbreviation] = $tag->exam_abbreviation;
        }

        $total = 0;
        foreach ($items as $item) {
            $total += count($item['tag_group']);
        }

        return $total;
    }

    public function getItems(Unity $unity, string $dateStart, string $dateEnd): array
    {
        $tags = DB::select(
            "SELECT
                IFNULL(exams.label_group, 0) AS exam_tag,
                exams.abbreviation AS exam_abbreviation,
                users_patients.first_name AS partient_name,
                patients.dob AS patient_dob, 
                appointments.created_at,
                unitys.sigla,
                appointments.id AS protocol

            FROM appointment_exams
            INNER JOIN exams
            ON appointment_exams.exam_id = exams.id  
            INNER JOIN appointments
            ON appointment_exams.appointment_id = appointments.id
            INNER JOIN unitys
            ON appointments.unity_id = unitys.id
            INNER JOIN users AS users_patients
            ON appointments.appointment_for = users_patients.id
            INNER JOIN patients
            ON appointments.appointment_for = patients.user_id

            WHERE appointment_exams.collected_at BETWEEN ? AND ?
            AND (appointment_exams.status = 1) AND (appointments.unity_id = ?)", [
                $dateStart,
                $dateEnd,
                $unity->id,
            ]
        );

        $items = [];
        foreach ($tags as $tag) {
            $items[$tag->protocol]['tag_group'][$tag->exam_tag][$tag->exam_abbreviation] = $tag->exam_abbreviation;
            $items[$tag->protocol]['patient']['name'] = $tag->partient_name;
            $items[$tag->protocol]['patient']['dob'] = $tag->patient_dob;
            $items[$tag->protocol]['registered_at'] = $tag->created_at;
            $items[$tag->protocol]['unity_sigla'] = $tag->sigla;
            $items[$tag->protocol]['protocol'] = $tag->protocol;
        }

        return $items;
    }
}
