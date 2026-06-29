<?php

namespace App\Http\Controllers\Exam;

use App\Models\Category;
use App\Models\Exam\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exam\ExamRequest;

class ExamController extends Controller 
{
    public function index() 
    {
        $exams = Exam::orderByDesc('is_active')->paginate(10);

        return view('exams.index', 
            compact('exams')
        );
    }

    public function search(Request $request)
    {
        return response()->json([
            'exams' => Exam::searchByName($request->name)
        ]);
    }
	
	public function searchName(Request $request)
    {
        return response()->json([
            'exams' => Exam::searchName($request->filtro)
        ]);
    }

    public function searchAbbreviation(Request $request)
    {
        return response()->json([
            'exams' => Exam::searchAbbreviation($request->filtro)
        ]);
    }

    public function create() {
        $categories = Category::active()->get();			
            
        return view('exams.create', 
            compact('categories')
        );
    }

    public function store(ExamRequest $request) {
        Exam::create($request->validated());

        return redirect()
            ->route('exams.index')
            ->withSuccess($request->message);
    }

    public function edit(Exam $exam) 
    {		
        $exam = $exam::with(['parameters', 'filters'])->firstWhere('id', $exam->id);
        $categories = Category::active()->get();

        return view('exams.edit', 
            compact('exam', 'categories')
        );
    }

    public function update(ExamRequest $request, Exam $exam) {
        $exam->update($request->validated());

        return redirect()
            ->route('exams.index')
            ->withSuccess($request->message);
    }
	
	public function gealltexam(Request $request)
    {
        return response()->json([
            'data' => DB::table('exams')->get()
        ]);
    }

    public function getexam(Request $request)
    {
	    $new_parameter = DB::table('results')
            ->where([
                'exams_id' => $request->id,
                'appointment_id' => $request->appoint_id
            ])->get();

        return response()->json([
            'data' => DB::table('new_parameter')->where('exam_id', '=', $request->id)->get(),
            'exams' => DB::table('exams')->where('id', '=', $request->id)->get(),
            'new_param' => isset($new_parameter)?$new_parameter: [],
            'cytolies' => DB::table('cytologies')->get()->toArray(),
            'cytology_subitems' => DB::table('cytology_subitems')->get()->toArray(),
        ]);
    }

}
