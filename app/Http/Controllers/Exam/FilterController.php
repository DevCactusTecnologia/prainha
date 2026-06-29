<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exam\FilterRequest;
use App\Models\Exam\Filter;

class FilterController extends Controller
{
    public function store(FilterRequest $request) 
    {
        try {
            $filter = Filter::where($request->validated())->count();

            if ($filter > 0) {
                return response()->json(['filter_exist' => true]);
            }

            if ($request->id) {
                $filter = Filter::find($request->id);
                $filter->update($request->all());
            } else {
                $filter = Filter::create($request->all());
            }
                
            return response()->json([
                'message' => $request->message,
                'data' => $filter,
            ]);

        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Algo deu errado!!! ' . $error->getMessage(),
                'data' => $filter,
            ]);
        }
    }

    public function show(Filter $filter) 
    {
        return response()->json([
            'filter' => $filter
        ]);
    }

    public function destroy(Filter $filter) {
        $filter->delete();

        return response()->json([
            'message' => 'Filtro deletado com sucesso!'
        ]);
    }

}
