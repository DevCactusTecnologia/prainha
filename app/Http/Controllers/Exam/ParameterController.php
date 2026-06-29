<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exam\ParameterRequest;
use App\Models\Exam\NewParameter as Parameter;

class ParameterController extends Controller
{
    public function store(ParameterRequest $request) 
    {
        try {
            if (! $request->id) {
                $parameter = Parameter::firstWhere('parameter', $request->parameter);

                if ($parameter) {
                    return response()->json(['parameter_exist' => true]);
                }
            }

            if ($request->id) {
                $parameter = Parameter::find($request->id);
                $parameter->update($request->all());
            } else {
                $parameter = Parameter::create($request->all());
            }
                
            return response()->json([
                'message' => $request->message,
                'data' => $parameter
            ]);

        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Algo deu errado!!! ' . $error->getMessage(),
                'data' => ''
            ]);
        }
    }

    public function show(Parameter $parameter) 
    {
        return response()->json([
            'parameter' => $parameter
        ]);
    }
}
