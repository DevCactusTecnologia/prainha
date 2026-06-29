<?php

namespace App\Http\Controllers\Routine;

use App\Http\Controllers\Controller;
use App\Models\Routine\Traceability;
use Illuminate\Http\Request;

class TraceabilityController extends Controller
{
    public function index()
    {   
        return view('routine.traceability.index');
    }

    public function search(Request $request)
    {
        return response()->json([
            'appointment' => Traceability::searchByProtocol($request->protocol)
        ]);
    }

    public function historic(Request $request)
    {
        return response()->json([
            'traceabilities' => Traceability::searchHistoric($request)
        ]);
    }

}
