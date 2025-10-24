<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Formula;
use Illuminate\Http\Request;
use App\Models\FormulaType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\FormulaService;

class FormulaController extends Controller
{

    protected $formulaService;

    public function __construct(FormulaService $formulaService)
    {
        $this->formulaService = $formulaService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formulas = Formula::with('course', 'formulaType')->get();



        return view('admin.formulas.formulas', [
            'formulas' => $formulas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


        $courses = Course::where('active', true)->orderBy('course_name')->get();
        $formulaTypes = FormulaType::where('active', true)->orderBy('formula_type_name')->get();
        $title = 'Create New Formula';
        return view('admin.formulas.create-formula-form', compact('courses', 'formulaTypes', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {




        $request->validate([
            'formula_name' => 'required|string|max:100|unique:formulas,formula_name',
            'formula_code' => 'required|string|max:10|unique:formulas,formula_code',
            'formula_desc' => 'nullable|string|max:500',
            'formula_type_id' => 'required|exists:formula_types,formula_type_id',
            'formula_expression' => 'required|string|max:1000',
            'course_id' => 'required|exists:courses,course_id',
            'active' => 'sometimes|boolean',
        ]);

        return $this->formulaService->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
