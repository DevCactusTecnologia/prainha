<?php

namespace App\Console\Commands;

use App\Models\Appointment\Appointment;
use App\Models\Exam\Exam;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ModelCommand extends Command
{
    protected $signature = 'fill:model';
    protected $description = 'Preencher novo modelo dos exames';

    public function handle()
    {
        $exams = Exam::all();

        foreach ($exams as $exam) {
            $model = $exam->models()->create([
                'name' => 'Modelo 01',
                'exam_editor' => ! is_null($exam->exam_editor) ? $exam->exam_editor : '',
            ]);

            $exam->update([
                'model_id' => $model->id
            ]);
        }

        $appointments = Appointment::all();

        foreach ($appointments as $appointment) {
            foreach ($appointment->exams as $exam) {
                DB::table('appointment_exams')
                    ->where('exam_id', $exam->id)
                    ->update([
                        'model_id' => $exam->model_id
                    ]);
            }
        }

        $this->info('Modelo preenchido com sucesso!');

        return Command::SUCCESS;
    }
}
