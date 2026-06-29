<?php

namespace App\Repositories\Appointment\Pipes\Cancel;

use App\Enums\Routine\StageEnum as Stage;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\DB;
use Closure;

class CreateTraceabilityPipe
{
    public function handle(array $pipe, Closure $next): mixed
    {
        foreach ($pipe['appointment']->exams as $exam) {
            DB::table('routine_traceabilities')
                ->insert([
                    'appointment_id' => $pipe['appointment']->id,
                    'exam_id' => $exam->id,
                    'stage_id' => Stage::CANCELED->value,
                    'user_id' => Sentinel::getUser()->id,
                    'result' => $pipe['request']->motive,
                    'registered_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return $next($pipe);
    }
}
