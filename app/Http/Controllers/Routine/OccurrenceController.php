<?php

namespace App\Http\Controllers\Routine;

use App\Enums\Appointment\OccurrenceStatusEnum;
use App\Enums\Appointment\ResolutionEnum;
use App\Enums\Appointment\StatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Routine\Occurrence;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class OccurrenceController extends Controller
{
    public function index(): View
    {
        return view('routine.occurrence.index', [
            'situations' => OccurrenceStatusEnum::cases(),
            'total' => Occurrence::total(),
        ]);
    }

    public function search(): JsonResponse
    {   
        $occurrence = Occurrence::with('appointment')
            ->select($this->columns())
            ->orderByDesc('id');
 
        return DataTables::eloquent($occurrence)
            ->filter(function ($query) { $this->executeFilters($query); })
            ->addColumn('patient', fn ($occurrence) => $occurrence->appointment->patient->first_name)
            ->addColumn('protocol', fn ($occurrence) => $occurrence->appointment_id)
            ->addColumn('registered_at', fn ($occurrence) => $occurrence->registered_at?->format('d/m/Y H:i:s'))
            ->addColumn('motive', fn ($occurrence) => $occurrence->motive)
            ->addColumn('user', fn ($occurrence) => $occurrence->user->first_name)
            ->addColumn('status', fn ($occurrence) => $this->status($occurrence))
            ->addColumn('actions', fn ($occurrence) => $this->actions($occurrence))
            ->rawColumns(['actions', 'status'])
            ->toJson();
    }

    private function status(Occurrence $occurrence): string
    {
        return <<<HTML
            <span class="{$occurrence->status->getColor()}">
                {$occurrence->status->getName()}
            </span>
        HTML;
    }

    private function actions(Occurrence $occurrence): string
    {
        $view = route('routine.occurrence.show', $occurrence->id);

        return <<<HTML
            <a href="{$view}" class="btn btn-sm btn-primary" style="border-radius: 10px;">
                <i class="mdi mdi-eye"></i>
            </a>
        HTML;
    }

    private function executeFilters(Builder $query): void
    {
        if (request()->has('filter_name')) {
            $query->whereHas('appointment', fn ($query) =>
                $query->whereHas('patient', fn ($query) =>
                    $query->where('first_name', 'like',  '%' . request('filter_name') . '%')
                )
            );
        }

        if (! request()->has('filter_status')) {
            $query->whereIn('status', [
                OccurrenceStatusEnum::PENDING, 
                OccurrenceStatusEnum::PARTIAL,
            ]);
        }

        if (request()->has('filter_protocol')) $query->where('appointment_id', request('filter_protocol'));
        if (request()->has('filter_status')) $query->where('status', request('filter_status'));
    }

    private function columns(): array
    {
        return [
            'id', 
            'appointment_id',
            'motive',
            'solution_id',
            'status',
            'user_id',
            'registered_at',
        ];
    }

    public function show(Occurrence $occurrence): View
    {
        $exams = $occurrence->appointment->exams->filter(fn ($exam) =>
            $exam->pivot->status == StatusEnum::CANCELED->value
        );

        return view('routine.occurrence.show', [
            'occurrence' => $occurrence,
            'exams' => $exams,
            'resolutions' => ResolutionEnum::cases(),
            'user' => Sentinel::getUser(),
        ]);
    }

    public function update(Request $request, Occurrence $occurrence): RedirectResponse
    {
        $procediments = [];
       
        if ($request->solution_id == '0' && count($request->procediments) > 0) {
            $occurrence->update([
                'solution_id' => ResolutionEnum::PENDING,
                'status' => OccurrenceStatusEnum::PARTIAL,
            ]);
        } else {
            $occurrence->update([
                'solution_id' => $request->solution_id,
                'status' => OccurrenceStatusEnum::RESOLVED,
            ]);
        }

        DB::table('appointment_occurrence_procediments')
            ->where('occurrence_id', $occurrence->id)
            ->delete();

        foreach ((array) $request->procediments as $index => $procediment) {
            $procediments[] = [
                'occurrence_id' => $occurrence->id,
                'procediment' => $procediment,
                'registered_at' => $request->registered_ats[$index],
                'user_id' => $request->user_ids[$index],
            ];
        }

        DB::table('appointment_occurrence_procediments')
            ->insert($procediments);

        return redirect()
            ->route('routine.occurrence.index')
            ->withSuccess('Salvo com sucesso');
    }
}
