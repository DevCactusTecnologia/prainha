<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exam\ModelRequest;
use App\Models\Exam\Exam;
use App\Models\Exam\Model;

class ModelController extends Controller
{
    public function create(Exam $exam)
    {
        return view('exams.models.create', 
            compact('exam')
        );
    }

    public function store(ModelRequest $request, Exam $exam) 
    {
        $exam->models()->create($request->validated());

        return redirect()
            ->route('exams.edit', $exam->id)
            ->withSuccess($request->message);
    }

    public function edit(Exam $exam, Model $model) 
    {
        return view('exams.models.edit', 
            compact('exam', 'model')
        );
    }

    public function update(ModelRequest $request, Exam $exam, Model $model)
    {
        $model->update($request->validated());

        return redirect()
            ->route('exams.edit', $exam->id)
            ->withSuccess($request->message);
    }

}
