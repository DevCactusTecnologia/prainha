<?php

namespace App\Http\Controllers\Routine;

use App\Models\Biomedical;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class ProductionBiomedicalController extends Controller
{
    public function index()
    {   
        return view('routine.production-by-biomedical.index');
    }

    public function searchAll(Request $request)
    {   
        $biomedicals = Biomedical::getProductionAll(
            $request->date_start, 
            $request->date_end
        );

        return response()->json([
            'biomedicals' => $biomedicals
        ]);
    }

    public function searchByBiomedical(User $biomedical, string $dateStart, string $dateEnd)
    {   
        $biomedicals = Biomedical::getProductionByBiomedical($biomedical, $dateStart, $dateEnd);
        $biomedicalInfo = Biomedical::firstWhere('user_id', $biomedical->id);

        return view('routine.production-by-biomedical.print', 
            compact('biomedicals', 'biomedicalInfo', 'dateStart', 'dateEnd')
        );
    }

    public function searchByBiomedicalAmount(User $biomedical, string $dateStart, string $dateEnd)
    {   
        $biomedicals = Biomedical::getProductionByBiomedicalAmount($biomedical, $dateStart, $dateEnd);
        $biomedicalInfo = Biomedical::firstWhere('user_id', $biomedical->id);

        return view('routine.production-by-biomedical.amount', 
            compact('biomedicals', 'biomedicalInfo', 'dateStart', 'dateEnd')
        );
    }

}
