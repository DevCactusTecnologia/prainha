<?php

namespace App\Repositories\Appointment\Pipes\Cancel;

use App\Enums\Appointment\StatusEnum as Status;
use Illuminate\Support\Facades\DB;
use Closure;

class UpdateExamsPipe
{
    public function handle(array $pipe, Closure $next): mixed
    {
        DB::table('appointment_exams')
            ->where('appointment_id', $pipe['appointment']->id)
            ->update([
                'status' => Status::CANCELED->value,
                'observation' => $pipe['request']->motive,
            ]);

        return $next($pipe);
    }
}
