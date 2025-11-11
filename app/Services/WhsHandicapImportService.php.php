<?php

namespace App\Services;


use App\Models\Tournament;
use App\Models\WhsHandicapImport;
use App\Models\WhsHandicapIndex;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

/**
 * Service for importing participants into tournaments.
 */
class WhsHandicapImportService
{
    protected Tournament $tournament;
    protected WhsHandicapImport $whsHandicapImport;

    public function import($request)
    {

        ini_set('max_execution_time', 300); // 300 seconds = 5 minutes



        try {

            $this->tournament = Tournament::where('tournament_id', $request->input('tournament_id'))->where('status', 'active')->first();


            // Parse and validate file structure
            $fileData = $this->parseImportFile($request->file('whs_import_file'));
            if (!$fileData['success']) {
                return $fileData;
            }



            // Validate all rows and collect valid data
            $validationResult = $this->validateImportRows($fileData['data'], $fileData['columnMap']);
            if (!$validationResult['success']) {
                return $validationResult;
            }

            DB::beginTransaction();

            $this->whsHandicapImport = $this->createWhsHandicapImport($request);

            // Perform bulk insertion
            $insertResult = $this->bulkInsertWhsIndexes($validationResult['validRows']);
            $this->tournament->whs_handicap_import_id = $this->whsHandicapImport->whs_handicap_import_id;
            $this->tournament->save();


            DB::commit();
            return [
                'success' => true,
                'message' => "Import completed. {$insertResult['imported']} players imported successfully.",
                'imported' => count($validationResult['validRows']),
                'errors' => $validationResult['errors']
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Import failed. Please try again later.',
            ];
        }
    }

    private function createWhsHandicapImport($request)
    {

        $whsHandicapImport = new WhsHandicapImport();

        $whsHandicapImport->tournament_id = $this->tournament->tournament_id;
        $whsHandicapImport->orig_filename = $request->file('whs_import_file')->getClientOriginalName();
        $whsHandicapImport->stored_filename = $request->file('whs_import_file')->store(date('Y-m-d') . '/whs_handicap_imports');
        $whsHandicapImport->file_path = storage_path('app/' . $whsHandicapImport->stored_filename);
        $whsHandicapImport->created_by = Auth::id();
        $whsHandicapImport->save();

        return $whsHandicapImport;
    }



    /**
     * Parse the import file and validate structure
     */
    private function parseImportFile($file)
    {
        $data = Excel::toArray([], $file)[0];

        // Check if file has data
        if (empty($data) || count($data) < 2) {
            return [
                'success' => false,
                'message' => 'File is empty or has no data rows.'
            ];
        }

        // Extract header and validate required columns
        $header = array_map('strtolower', array_map('trim', $data[0]));

        $requiredColumns = ['whs_no', 'whs_handicap_index'];

        foreach ($requiredColumns as $column) {
            if (!in_array($column, $header)) {
                return [
                    'success' => false,
                    'message' => "Missing required column: {$column}. Required columns: " . implode(', ', $requiredColumns)
                ];
            }
        }

        return [
            'success' => true,
            'data' => $data,
            'columnMap' => array_flip($header)
        ];
    }

    /**
     * Validate all import rows and collect valid data
     */
    private function validateImportRows($data, $columnMap)
    {
        $errors = [];
        $validRows = [];


        for ($i = 1; $i < count($data); $i++) {
            $row = $data[$i];


            $rowValidation = $this->validateSingleRow($row, $columnMap, $i + 1, $validRows);

            if ($rowValidation['success']) {
                $validRows[] = $rowValidation['data'];
            } else {
                $errors = array_merge($errors, $rowValidation['errors']);
            }
        }

        // If no valid rows, return with errors
        if (empty($validRows)) {
            return [
                'success' => false,
                'message' => 'No valid rows found for import.',
                'errors' => $errors
            ];
        }

        return [
            'success' => empty($errors),
            'validRows' => $validRows,
            'errors' => $errors
        ];
    }



    private function validateSingleRow($row, $columnMap, $rowNumber, $validRows)
    {
        // Extract and clean row data
        $rowData = [
            'whs_no' => isset($row[$columnMap['whs_no']]) ? trim($row[$columnMap['whs_no']]) : '',
            'whs_handicap_index' => isset($row[$columnMap['whs_handicap_index']])
                ? (trim($row[$columnMap['whs_handicap_index']]) === 'Pending' ? 0 : trim($row[$columnMap['whs_handicap_index']]))
                : 0,
            'name' => isset($row[$columnMap['name'] ?? null]) ? trim($row[$columnMap['name']]) : '',
            'sex' => isset($row[$columnMap['sex'] ?? null]) ? trim($row[$columnMap['sex']]) : ''
        ];

        $rowValidator = Validator::make($rowData, [
            'whs_no' => 'required|string|max:50',
            'whs_handicap_index' => 'required',
            'name' => 'nullable|string|max:100',
            'sex' => 'nullable|in:M,F'
        ]);

        if ($rowValidator->fails()) {
            return [
                'success' => false,
                'errors' => ["Row {$rowNumber}: " . implode(', ', $rowValidator->errors()->all())]
            ];
        }

        // Check for duplicates
        $duplicateCheck = $this->checkForDuplicates($rowData, $validRows, $rowNumber);
        if (!$duplicateCheck['success']) {
            return $duplicateCheck;
        }

        $handicap = $this->formatHandicapIndex($rowData['whs_handicap_index']);
        $whsNo = str_replace(['=', '"'], '', $rowData['whs_no']); // Result: "+1"


        return [
            'success' => true,
            'data' => [
                'whs_no' => $whsNo, // Result: "+1"
                'whs_handicap_index' => $handicap['handicap_index'],
                'handicap_type' => $handicap['handicap_type'],
                'name' => $rowData['name'],
                'sex' => $rowData['sex'],
                'row_number' => $rowNumber
            ]
        ];
    }



    /**
     * Format the raw Excel handicap value into numeric index and type.
     *
     * Examples:
     *  '="+1"'  → ['handicap_index' => 1.0, 'handicap_type' => 'plus']
     *  '="2"'   → ['handicap_index' => 2.0, 'handicap_type' => 'reg']
     *  '="-1"'  → ['handicap_index' => 1.0, 'handicap_type' => 'reg']
     *  null or empty → ['handicap_index' => null, 'handicap_type' => null]
     */
    private function formatHandicapIndex($value): array
    {
        if (empty($value)) {
            return [
                'handicap_index' => 0,
                'handicap_type' => 'none',
            ];
        }

        // Step 1: Clean up value (remove =, quotes, and spaces)
        $clean = trim(str_replace(['=', '"'], '', $value));

        // Step 2: Determine type (plus if + found)
        $handicapType = str_contains($clean, '+') ? 'plus' : 'reg';

        // Step 3: Remove + sign and convert to float
        $handicapIndex = (float) str_replace('+', '', $clean);

        return [
            'handicap_index' => $handicapIndex,
            'handicap_type' => $handicapType,
        ];
    }




    private function checkForDuplicates($rowData, $validRows, $rowNumber)
    {
        $errors = [];

        // Check duplicates within current import batch
        $batchWHSNumbers = array_column($validRows, 'whs_no');


        $whsNo = str_replace(['=', '"'], '', $rowData['whs_no']); // Result: "+1"


        if (in_array($whsNo, $batchWHSNumbers)) {
            $errors[] = "Row {$rowNumber}: Duplicate WHS No {$whsNo} found in import file.";
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        return ['success' => true];
    }

    private function bulkInsertWhsIndexes($validRows)
    {


        $whsData = $this->prepareWhsIndexesData($validRows);

        WhsHandicapIndex::insert($whsData);
    }

    /**
     * Prepare WHS handicap index data for bulk insertion.
     * @param array $validRows
     * @param \Carbon\Carbon|string $now
     * @return array
     */
    private function prepareWhsIndexesData($validRows): array
    {

        $now = now();


        $whsHandicaps = [];

        foreach ($validRows as $rowData) {

            $whsHandicaps[] = [
                'tournament_id' => $this->tournament->tournament_id,
                'whs_handicap_import_id' => $this->whsHandicapImport->whs_handicap_import_id,
                'whs_no' => $rowData['whs_no'],
                'whs_handicap_index' => $rowData['whs_handicap_index'],
                'final_whs_handicap_index' => $rowData['whs_handicap_index'],
                'handicap_type' => $rowData['handicap_type'],
                'is_adjusted' => false,
                'name' => $rowData['name'] ?? null,
                'sex' => $rowData['sex'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => Auth::id()
            ];
        }


        return $whsHandicaps;
    }
}
