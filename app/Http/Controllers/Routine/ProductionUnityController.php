<?php

namespace App\Http\Controllers\Routine;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Unity;

class ProductionUnityController extends Controller
{
    public function index()
    {   
        return view('routine.production-by-unity.index');
    }

    public function searchAll(Request $request)
    {   
        $unitys = Unity::getProductionAll(
            $request->date_start, 
            $request->date_end
        );

        return response()->json([
            'unitys' => $unitys
        ]);
    }

    public function searchByUnity(Unity $unity, string $dateStart, string $dateEnd)
    {   
        $unitys = Unity::getProductionByUnity($unity, $dateStart, $dateEnd);

        return view('routine.production-by-unity.print', 
            compact('unitys', 'dateStart', 'dateEnd')
        );
    }

}
