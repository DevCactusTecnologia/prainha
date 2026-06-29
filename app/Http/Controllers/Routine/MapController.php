<?php

namespace App\Http\Controllers\Routine;

use App\Models\Appointment\Appointment;
use App\Models\Biomedical;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MapController extends Controller
{
    public function patientIndex()
    {   
        return view('routine.map.patient.index');
    }

    public function patientSearch(Request $request)
    {   
        return response()->json([
            'patients' => Patient::searchMap($request)
        ]);
    }

    public function patientPrint(Appointment $appointment)
    {
        return view('routine.map.patient.print', 
            compact('appointment')
        );
    }

    public function biomedicalIndex()
    {   
        return view('routine.map.biomedical.index');
    }

    public function biomedicalSearch(Request $request)
    {   
        return response()->json([
            'biomedicals' => Biomedical::searchMap($request)
        ]);
    }

    public function biomedicalPrint(int $id, string $date)
    {
        $registers = Biomedical::getMap($id, $date);

        return view('routine.map.biomedical.print', 
            compact('registers')
        );
    }

}
