<?php

namespace App\Http\Controllers\Appointment;

use App\Enums\Appointment\MotiveEnum;
use App\Enums\Appointment\ResolutionEnum;
use App\Models\Appointment\Appointment;
use App\Enums\Appointment\StatusEnum;
use App\Enums\Routine\StageEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\FinishResultRequest;
use App\Http\Requests\Appointment\SaveResultRequest;
use App\Enums\Appointment\StatusEnum as Status;
use App\Enums\Shared\ActiveEnum;
use App\Models\Exam\Exam;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function create(Appointment $appointment): View
    {
        return view('appointments.result.create', [
            'appointment' => $appointment,
            'abbreviations' => DB::select("SELECT abbreviation, code FROM abbreviations"),
            'motives' => MotiveEnum::cases(),
        ]);
    }

    public function save(SaveResultRequest $request)
    {
        DB::beginTransaction();
            $appointment = Appointment::findOrFail($request->id);
            $appointment->update(['checked_at' => date('Y-m-d')]);

            $appointment::resultIsNotInserted($request) 
                ? Appointment::saveResult($request)
                : Appointment::updateResult($request);
        DB::commit();

        $pendings = $appointment->exams->contains(fn ($exam) =>
            $exam->pivot->status == '0' || $exam->pivot->status == '2'
        );

        if (! $pendings) {
            session()->put('finished', $request->messageFinished);
            $appointment->update([
                'status' => StatusEnum::COMPLETED->value,
            ]);

            return redirect()->route('appointments.result.create', $appointment->id);
        }

        return redirect()
            ->route('appointments.result.create', $appointment->id)
            ->withErrors($request->all())
            ->with('success', $request->message);
    }

    public function check(Appointment $appointment)
    {
        $examsPendings = DB::select(
            "SELECT 
                appointment_exams.appointment_id AS id, 
                exams.id AS exam_id, 
                exams.name AS exam_name
            FROM appointment_exams
            INNER JOIN exams
            ON appointment_exams.exam_id = exams.id
            WHERE appointment_exams.appointment_id = ? 
            AND (appointment_exams.status = 0 OR appointment_exams.status = 2)", 
            [$appointment->id]
        );

        if (count($examsPendings) > 0) {
            $contentExam = <<<EOL
                <table class="table table-bordered table-centered table-sm table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>Exame</th>
                            <th>Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
            EOL;

            foreach ($examsPendings as $index => $pending) {
                $contentExam .= <<<EOL
                    <tr>
                        <td>
                            <input type="checkbox" value="{$pending->exam_id}" id="exam-retest-{$pending->exam_id}" name="exams_pendings[]"
                                onclick="this.checked ? this.parentNode.nextElementSibling.nextElementSibling.firstElementChild.disabled = false : this.parentNode.nextElementSibling.nextElementSibling.firstElementChild.disabled = true";
                            >
                        </td>
                        <td>
                            <label for="exam-retest-{$pending->exam_id}" class="mb-1">{$pending->exam_name}<label>
                        </td>
                        <td style="width: 60%">
                            <input type="text" maxlength="191" class="w-100" name="observations[]" disabled required>
                        </td>
                    </tr>
                EOL;
            }

            $contentExam .= <<<EOL
                    </tbody>
                </table> 
            EOL;

            $route = route('appointments.result.finish', $appointment->id);
            $visibility = $appointment->results->isEmpty() ? 'none' : 'block';
            $csrf = csrf_field();

            $methodPut = method_field('PUT');
            $routeRetest = route('appointments.result.update.retest', $appointment->id);
            $content = <<<EOL
                <div class="d-flex align-items-center">
                    <strong>Atenção!</strong>&nbsp; Existem exames pendentes ou cancelados, deseja &nbsp;<strong>finalizar</strong>&nbsp; mesmo assim?
                    <form method="POST" action="{$route}" style="display: {$visibility}">
                        {$csrf}
                        <div class="d-flex">
                            <button type="submit" class="btn btn-warning ml-3"
                                style="display: {$visibility}"
                            >
                                Finalizar
                            </button>
                    </form>

                        <strong class="align-self-md-center mx-2">Ou</strong>
                            <button type="button" class="btn btn-danger px-3" data-toggle="modal"data-target="#reTest">
                                Marcar reteste
                            </button>

                            <div class="modal fade" id="reTest" tabindex="-1" role="dialog" 
                                aria-labelledby="exampleModalCenterTitle" aria-hidden="true"
                            >
                                <div class="modal-dialog modal-lg" role="document">
                                    <form action="{$routeRetest}" method="POST" class="modal-content">
                                        {$csrf}
                                        {$methodPut}

                                        <div class="modal-header">
                                            <h5 class="modal-title text-primary" id="exampleModalLongTitle">
                                                Marcar reteste de exames pendentes
                                            </h5>
                                            <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>{$contentExam}</div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="submit" class="btn btn-primary rounded-pill px-3">
                                                Salvar e finalizar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    
                </div>
            EOL;

            session()->put('pending', $content);
        }

        return redirect()->route('appointments.result.create', $appointment->id);
    }

    public function finish(FinishResultRequest $request, Appointment $appointment)
    {
        DB::beginTransaction();
            $appointment->update([
                'status' => StatusEnum::COMPLETED->value,
                'checked_at' => date('Y-m-d'),
            ]);

            $appointment->exams->each(function ($exam) use ($appointment) {
                if ($exam->pivot->status == '0') {
                    DB::table('appointment_exams')
                        ->where('appointment_id', $appointment->id)
                        ->where('exam_id', $exam->id)
                        ->update(['status' => 2]);

                    DB::table('routine_traceabilities')
                        ->insert([
                            'appointment_id' => $appointment->id,
                            'exam_id' => $exam->id,
                            'stage_id' => StageEnum::CANCELED->value,
                            'user_id' => Sentinel::getUser()->id,
                            'registered_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            });

            session()->put('finished', $request->message);
        DB::commit();

        return redirect()->route('appointments.result.create', $appointment->id);
    }

    public function updateRetest(Request $request, Appointment $appointment)
    {
        DB::beginTransaction();
            $appointment->update([
                'status' => StatusEnum::COMPLETED->value,
                'checked_at' => date('Y-m-d'),
            ]);

            $appointment->exams->each(function ($exam) use ($appointment) {
                if ($exam->pivot->status == '0') {
                    DB::table('appointment_exams')
                        ->where('appointment_id', $appointment->id)
                        ->where('exam_id', $exam->id)
                        ->update(['status' => 2]);
                }
            });

            if (! is_array($request->exams_pendings)) {
                session()->put('finished', 'Atendimento finalizado com sucesso, documento pronto para ser visualizado e impresso.');
            } else {
                foreach ($request->exams_pendings as $index => $examId) {
                    DB::table('appointment_exams')
                        ->where('appointment_id', $appointment->id)
                        ->where('exam_id', $examId)
                        ->update([
                            're_test' => 1,
                            'observation' => $request->observations[$index],
                        ]);
                }

                session()->put('finished', 'Atendimento finalizado com reteste incluso no(s) exame(s) selecionado(s), documento pronto para ser visualizado e impresso.');
            }
        DB::commit();

        return redirect()->route('appointments.result.create', $appointment->id);
    }

    public function show(Appointment $appointment)
    {
        $exams = $appointment->exams->filter(fn ($exam) =>
            $exam->pivot->status == '1'
        );

        foreach ($exams as $exam) {
            DB::table('routine_traceabilities')
                ->insert([
                    'appointment_id' => $appointment->id,
                    'exam_id' => $exam->id,
                    'stage_id' => StageEnum::CHECKED,
                    'user_id' => Sentinel::getUser()->id,
                    'registered_at' => date('Y-m-d H:i:s'),
                ]);
        }

        return view('appointments.result.show', 
            compact('appointment', 'exams')
        );
    }

    public function print(Appointment $appointment)
    {
        $contentLong = Exam::listContentLong();
        [$examsContentLong, $examsContentSmall] = $appointment->exams
            ->filter(fn ($exam) => $exam->pivot->status)
            ->partition(fn ($exam) => in_array($exam->id, $contentLong));

        $exams = $examsContentLong->merge($examsContentSmall);
        
        return view('appointments.result.print', 
            compact('appointment', 'exams', 'contentLong')
        );
    }

    public function cancel(Request $request, Appointment $appointment, Exam $exam): RedirectResponse
    {
        DB::beginTransaction();
            DB::table('appointment_exams')
                ->where('appointment_id', $appointment->id)
                ->where('exam_id', $exam->id)
                ->update([
                    'status' => Status::CANCELED,
                    'observation' => $request->motive,
                    'user_id' => Sentinel::getUser()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            
            $hasOccurrence = DB::table('appointment_occurrences')
                ->where('appointment_id', $appointment->id)
                ->first();

            if (! $hasOccurrence) {
                DB::table('appointment_occurrences')
                    ->insert([
                        'appointment_id' => $appointment->id,
                        'motive' => $request->motive,
                        'solution_id' => ResolutionEnum::PENDING,
                        'user_id' => Sentinel::getUser()->id,
                        'registered_at' => date('Y-m-d H:i:s'),
                    ]);
            }

            DB::table('routine_traceabilities')
                ->insert([
                    'appointment_id' => $appointment->id,
                    'exam_id' => $exam->id,
                    'stage_id' => StageEnum::CANCELED->value,
                    'user_id' => Sentinel::getUser()->id,
                    'result' => $request->motive,
                    'registered_at' => date('Y-m-d H:i:s'),
                ]);

            $this->checkExamsCanceleds($appointment);
        DB::commit();

        return redirect()
            ->route('appointments.result.create', $appointment->id)
            ->withSuccess("Exame {$exam->name} cancelado com sucesso!");
    }

    public function retest(Request $request, Appointment $appointment, Exam $exam): RedirectResponse
    {
        DB::beginTransaction();
            DB::table('appointment_exams')
                ->where('appointment_id', $appointment->id)
                ->where('exam_id', $exam->id)
                ->update([
                    'status' => Status::CANCELED,
                    're_test' => ActiveEnum::ACTIVE->value,
                    'observation' => $request->motive,
                    'user_id' => Sentinel::getUser()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            $hasOccurrence = DB::table('appointment_occurrences')
                ->where('appointment_id', $appointment->id)
                ->first();

            if (! $hasOccurrence) {
                DB::table('appointment_occurrences')
                    ->insert([
                        'appointment_id' => $appointment->id,
                        'motive' => $request->motive,
                        'solution_id' => ResolutionEnum::PENDING,
                        'user_id' => Sentinel::getUser()->id,
                        'registered_at' => date('Y-m-d H:i:s'),
                    ]);
            }

            DB::table('routine_traceabilities')
                ->insert([
                    'appointment_id' => $appointment->id,
                    'exam_id' => $exam->id,
                    'stage_id' => StageEnum::CANCELED->value,
                    'user_id' => Sentinel::getUser()->id,
                    'result' => $request->motive,
                    'registered_at' => date('Y-m-d H:i:s'),
                ]);

            $this->checkExamsCanceleds($appointment);
        DB::commit();

        return redirect()
            ->route('appointments.result.create', $appointment->id)
            ->withSuccess("O exame {$exam->name} foi encaminhado para reteste!");
    }

    public function restore(Request $request, Appointment $appointment, Exam $exam): RedirectResponse
    {
        DB::beginTransaction();
            $appointment->update([
                'status' => Status::PENDING->value,
                'checked_at' => date('Y-m-d'),
            ]);

            DB::table('appointment_exams')
                ->where('appointment_id', $appointment->id)
                ->where('exam_id', $exam->id)
                ->update([
                    'status' => Status::PENDING->value,
                    're_test' => ActiveEnum::INACTIVE->value,
                    'observation' => '',
                    'user_id' => null,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            DB::table('appointment_occurrences')
                ->where('appointment_id', $appointment->id)
                ->delete();

            DB::table('routine_traceabilities')
                ->insert([
                    'appointment_id' => $appointment->id,
                    'exam_id' => $exam->id,
                    'stage_id' => StageEnum::RESTORED->value,
                    'user_id' => Sentinel::getUser()->id,
                    'result' => "Exame restaurado do status cancelado (motivo: {$request->old_motive}) para ativo",
                    'registered_at' => date('Y-m-d H:i:s'),
                ]);
        DB::commit();

        return redirect()
            ->route('appointments.result.create', $appointment->id)
            ->withSuccess("O exame {$exam->name} foi restaurado com sucesso!");
    }

    private function checkExamsCanceleds(Appointment $appointment): void
    {
        $appointment->update([
            'checked_at' => date('Y-m-d')
        ]);

        $examsCanceleds = 0;
        $appointment->exams->each(function ($exam) use (&$examsCanceleds) {
            if ($exam->pivot->status == '2') {
                $examsCanceleds++;
            }
        });

        if ($examsCanceleds === $appointment->exams->count()) {
            $appointment->update([
                'status' => Status::CANCELED->value,
            ]);
        }
    }
}
