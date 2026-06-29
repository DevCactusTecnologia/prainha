<?php

namespace App\Repositories\Appointment\Pipes\Create;

use App\Enums\Routine\StageEnum;
use App\Enums\Shared\BooleanEnum;
use App\Models\Appointment\Appointment;
use App\Models\Exam\Exam;
use Illuminate\Support\Facades\DB;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

class ExamPipe
{
    public function handle(array $pipe, Closure $next): mixed
    {
        foreach ($pipe['request']->exam_ids as $index => $examId) {
            $this->storeTraceabilities($pipe['appointment'], $examId);
            $this->storeExams($pipe['appointment'], $examId, $index);
            $this->checkCytology($pipe['appointment'], $examId);
        }

        return $next($pipe);
    }

    private function storeTraceabilities(Appointment $appointment, int|string $examId)
    {
        $appointment->traceabilities()->create([
            'exam_id' => $examId,
            'stage_id' => StageEnum::REGISTER->value,
            'user_id' => Sentinel::getUser()->id,
            'registered_at' => $appointment->registered_at . ' ' . date('H:i:s'),
        ]);
    }

    private function storeExams(Appointment $appointment, int|string $examId, string $index)
    {
        $exam = Exam::find($examId);

        DB::table('appointment_exams')->insert([
            'appointment_id' => $appointment->id,
            'exam_id' => $examId,
            'model_id' => $exam->model_id,
            'biomedical_id' => request()->exam_biomedicals[$index],
            'collected_at' => request()->exam_collected_at[$index],
            'status' => 0,
            're_test' => 0,
            'price' => request()->exam_prices[$index],
            'destiny_id' => request()->destiny_ids[$index],
            'user_id' => Sentinel::getUser()->id,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if (request()->destinys[$index] == 'external') {
            DB::table('appointment_exam_externals')->insert([
                'appointment_id' => $appointment->id,
                'exam_id' => $examId,
            ]);

            $appointment->update([
                'has_exam_external' => BooleanEnum::YES
            ]);
        }

        if (request()->destinys[$index] == 'internal' || request()->destinys[$index] == 'seted') {
            $appointment->update([
                'has_exam_internal' => BooleanEnum::YES
            ]);
        }
    }

    private function checkCytology(Appointment $appointment, int|string $examId)
    {
        if ($examId == '168') {
            $appointment->update([
                'has_exam_cytology' => BooleanEnum::YES
            ]);

            $appointment->anamnese()->create(request()->all());
        }
    }
}
