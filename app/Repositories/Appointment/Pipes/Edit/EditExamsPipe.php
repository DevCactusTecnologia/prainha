<?php

namespace App\Repositories\Appointment\Pipes\Edit;

use App\Enums\Exam\ExamTypeEnum;
use App\Models\Exam\Exam;
use Closure;
use Illuminate\Support\Facades\DB;

class EditExamsPipe
{
    public function handle(array $pipe, Closure $next): mixed
    {
        foreach ($pipe['request']->exam_ids as $index => $examId) {
            $exam = Exam::find($examId);

            DB::table('appointment_exams')
                ->updateOrInsert(
                    ['appointment_id' => $pipe['appointment']->id, 'exam_id' => $examId],
                    [
                        'model_id' => $exam->model_id,
                        'biomedical_id' => $pipe['request']->exam_biomedicals[$index],
                        'collected_at' => $pipe['request']->exam_collected_at[$index],
                        'status' => $pipe['request']->exam_status[$index],
                        'price' => $pipe['request']->exam_prices[$index],
                    ]
                );

            // CYTOLOGY
            if ($examId == ExamTypeEnum::CYTOLOGY->value) {
                $pipe['appointment']->anamnese()->update([
                    'motive_id' => $pipe['request']->motive_id,
                    'has_exam_preventive' => $pipe['request']->has_exam_preventive,
                    'exam_preventive_at' => $pipe['request']->exam_preventive_at,
                    'has_diu' => $pipe['request']->has_diu,
                    'is_pregnant' => $pipe['request']->is_pregnant,
                    'has_contraceptive' => $pipe['request']->has_contraceptive,
                    'has_hormone' => $pipe['request']->has_hormone,
                    'has_radiotherapy' => $pipe['request']->has_radiotherapy,
                    'dum_at' => $pipe['request']->dum_at,
                    'dum_not_apply' => $pipe['request']->dum_not_apply,
                    'has_bleeding_relationship' => $pipe['request']->has_bleeding_relationship,
                    'has_bleeding_menopause' => $pipe['request']->has_bleeding_menopause,
                    'colo_id' => $pipe['request']->colo_id,
                    'has_sign_illness_id' => $pipe['request']->has_sign_illness_id,
                ]);
            }
        }

        return $next($pipe);
    }
}
