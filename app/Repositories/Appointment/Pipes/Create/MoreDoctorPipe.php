<?php

namespace App\Repositories\Appointment\Pipes\Create;

use Illuminate\Support\Facades\DB;
use Closure;

class MoreDoctorPipe
{
    public function handle(array $pipe, Closure $next): mixed
    {
        if ($pipe['request']->more_doctors) {
            $registers = array_filter(
                explode('|', $pipe['request']->more_doctors)
            );
    
            $data = [];
            foreach ($registers as $key => $register) {
                [$doctorId, $examId] = explode('-', $register);
    
                $data[] = [
                    'appointment_id' => $pipe['appointment']->id,
                    'doctor_id' => $doctorId,
                    'exam_id' => $examId,
                ];
            }

            DB::table('appointment_more_doctor')->insert($data);
        }

        return $next($pipe);
    }
}
