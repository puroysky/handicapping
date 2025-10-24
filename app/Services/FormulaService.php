<?php

namespace App\Services;

use App\Models\Formula;
use App\Models\FormulaType;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\PlayerProfile;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class FormulaService
{
    public function index()
    {
        // Code to list all tests
    }

    public function store($request)
    {
        try {

            DB::beginTransaction();

            $formulaType = FormulaType::find($request->input('formula_type_id'));


            $formula = Formula::create([
                'formula_name' => $request->input('formula_name'),
                'formula_code' => $request->input('formula_code'),
                'formula_desc' => $request->input('formula_desc'),
                'formula_expression' => $request->input('formula_expression'),
                'course_id' => $request->input('course_id'),

                'formula_type_id' => $request->input('formula_type_id'),
                'active' => $request->input('active', true),
                'created_by' => Auth::id()
            ]);

            DB::commit();
            return response()->json(['message' => 'Formula created successfully.', 'data' => $formula], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating formula: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while creating the formula.' . $e->getMessage()], 500);
        }
    }
}
