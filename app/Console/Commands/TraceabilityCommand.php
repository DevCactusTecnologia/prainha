<?php

namespace App\Console\Commands;

use App\Enums\Routine\StageEnum;
use App\Models\Appointment\Appointment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TraceabilityCommand extends Command
{
    protected $signature = 'trace:appointment';
    protected $description = 'Rastrear atendimento';

    public function handle()
    {
        $appointments = Appointment::all();

        foreach ($appointments as $appointment) {
            foreach ($appointment->exams as $exam) {
                DB::table('routine_traceabilities')
                    ->insert([
                        'appointment_id' => $appointment->id,
                        'exam_id' => $exam->id,
                        'stage_id' => StageEnum::REGISTER->value,
                        'user_id' => $appointment->booked_by,
                        'registered_at' => $appointment->appointment_date . ' ' . date('H:i:s'),
                    ]);
            }
        }

        $this->info('Rastreabilidade registrada com sucesso!');

        return Command::SUCCESS;
    }
}
