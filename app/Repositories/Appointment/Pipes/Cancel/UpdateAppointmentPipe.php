<?php

namespace App\Repositories\Appointment\Pipes\Cancel;

use App\Enums\Appointment\ResolutionEnum;
use App\Enums\Appointment\StatusEnum as Status;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\DB;
use Closure;

class UpdateAppointmentPipe
{
    public function handle(array $pipe, Closure $next): mixed
    {
        $pipe['appointment']->update([
            'status' => Status::CANCELED->value
        ]);

        $hasOccurrence = DB::table('appointment_occurrences')
            ->where('appointment_id', $pipe['appointment']->id)
            ->first();

        if (! $hasOccurrence) {
            DB::table('appointment_occurrences')
                ->insert([
                    'appointment_id' => $pipe['appointment']->id,
                    'motive' => $pipe['request']->motive,
                    'solution_id' => ResolutionEnum::PENDING,
                    'user_id' => Sentinel::getUser()->id,
                    'registered_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return $next($pipe);
    }
}
