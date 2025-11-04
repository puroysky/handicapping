<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\PlayerProfile;
use App\Models\Tournament;
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
use NXP\MathExecutor;

class ScoreMigrateService
{


    protected $tournament = null;

    public function migrate($request = null)
    {

        $this->tournament = Tournament::with('tournamentCourses.scorecard.scoreDifferentialFormula')->find($request->tournament_id);



        $this->tournament = Tournament::with('tournamentCourses.scorecard.scoreDifferentialFormula', 'tournamentCourses.scorecard.ratings')->find(1);
        $this->tournament->setRelation('tournamentCourses', $this->tournament->tournamentCourses->keyBy('course_id'));


        foreach ($this->tournament->tournamentCourses as $tournamentCourse) {
            if ($tournamentCourse->scorecard && $tournamentCourse->scorecard->ratings) {
                $tournamentCourse->scorecard->setRelation('ratings', $tournamentCourse->scorecard->ratings->keyBy('tee_id'));
            }
        }






        ini_set('max_execution_time', 300); // 300 seconds = 5 minutes

        try {
            // Validate the uploaded file
            $fileValidation = $this->validateImportFile($request);
            if (!$fileValidation['success']) {
                return $fileValidation;
            }

            // Parse and validate file structure
            $fileData = $this->parseImportFile($request->file('import_file'));
            // $fileData = $this->parseImportFile(storage_path('app/All Seniors 2025.xlsx'));



            // return;



            if (!$fileData['success']) {
                return $fileData;
            }



            Log::debug('Column map', ['columnMap' => $fileData['columnMap']]);

            // Validate all rows and collect valid data
            $validationResult = $this->validateImportRows($fileData['data'], $fileData['columnMap']);
            if (!$validationResult['success']) {
                // echo 'Parsed file data';
                // echo '<pre>';
                // print_r($validationResult);
                // echo '</pre>';

                return $validationResult;
            }
            // echo 'Parsed file data';
            // echo '<pre>';
            // print_r($validationResult);
            // echo '</pre>';



            // return;

            // Perform bulk insertion
            $insertResult = $this->bulkInsertScores($validationResult['validRows']);
            if (!$insertResult['success']) {
                return $insertResult;
            }

            return [
                'success' => true,
                'message' => "Import completed. {$insertResult['imported']} players imported successfully.",
                'imported' => $insertResult['imported'],
                'errors' => $validationResult['errors']
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validate the uploaded import file
     */
    private function validateImportFile($request)
    {
        $validator = Validator::make($request->all(), [
            'import_file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => 'Invalid file format. Please upload Excel or CSV file.',
                'errors' => $validator->errors()
            ];
        }

        return ['success' => true];
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
        $requiredColumns = ['account_no', 'adjusted_gross_score', 'holes_completed', 'date_played', 'tee_id', 'course_id', 'tournament_id', 'score_differential'];

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



        // Process each data row
        for ($i = 1; $i < count($data); $i++) {
            $row = $data[$i];

            try {
                $rowValidation = $this->validateSingleRow($row, $columnMap, $i + 1, $validRows);

                if ($rowValidation['success']) {
                    $validRows[] = $rowValidation['data'];
                } else {
                    $errors = array_merge($errors, $rowValidation['errors']);
                }
            } catch (Exception $e) {
                $errors[] = "Row " . ($i + 1) . ": " . $e->getMessage();
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
            'success' => true,
            'validRows' => $validRows,
            'errors' => $errors
        ];
    }

    /**
     * Get existing player data for duplicate checking
     */
    private function getExistingPlayerData()
    {
        return [
            'whs_numbers' => PlayerProfile::pluck('whs_no')->toArray(),
            'account_numbers' => PlayerProfile::pluck('account_no')->toArray(),
            'emails' => User::pluck('email')->toArray()
        ];
    }

    /**
     * Validate a single row of import data
     */
    private function validateSingleRow($row, $columnMap, $rowNumber, $validRows)
    {
        // Extract and clean row data
        $rowData = [

            'account_no' => isset($row[$columnMap['account_no']]) ? trim($row[$columnMap['account_no']]) : '',
            'adjusted_gross_score' => isset($row[$columnMap['adjusted_gross_score']]) ? trim($row[$columnMap['adjusted_gross_score']]) : '',
            // 'slope_rating' => isset($row[$columnMap['slope_rating']]) ? trim($row[$columnMap['slope_rating']]) : '',
            // 'course_rating' => isset($row[$columnMap['course_rating']]) ? trim($row[$columnMap['course_rating']]) : '',
            'holes_completed' => isset($row[$columnMap['holes_completed']]) ? trim($row[$columnMap['holes_completed']]) : '',
            'date_played' => isset($row[$columnMap['date_played']]) ?
                (is_numeric($row[$columnMap['date_played']]) ?
                    ExcelDate::excelToDateTimeObject($row[$columnMap['date_played']])->format('Y-m-d') :
                    Carbon::parse(trim($row[$columnMap['date_played']]))->format('Y-m-d')) : '',
            'tee_id' => isset($row[$columnMap['tee_id']]) ? trim($row[$columnMap['tee_id']]) : '',
            'course_id' => isset($row[$columnMap['course_id']]) ? trim($row[$columnMap['course_id']]) : '',
            'tournament_id' => isset($row[$columnMap['tournament_id']]) ? trim($row[$columnMap['tournament_id']]) : '',
            'score_differential' => isset($row[$columnMap['score_differential']]) ? trim($row[$columnMap['score_differential']]) : '',
            // 'tournament_name' => isset($row[$columnMap['tournament_name']]) ? trim($row[$columnMap['tournament_name']]) : ''

        ];



        Log::debug('Validating row', ['row_number' => $rowNumber, 'row_data' => $rowData]);
        // Validate field formats
        $rowValidator = Validator::make($rowData, [
            'account_no' => 'required|string|max:50',
            'adjusted_gross_score' => 'required|integer|min:1|max:200',
            // 'slope_rating' => 'required|integer|min:55|max:155',
            // 'course_rating' => 'required|integer|min:55|max:155',
            'holes_completed' => 'required|string|in:F9,B9,18',
            'date_played' => 'required|date',
            'tee_id' => 'required|integer|max:10',
            'course_id' => 'required|integer|max:10',
            'tournament_id' => 'required|integer|max:10',
            'score_differential' => 'required|string|min:-20|max:40',
            // 'tournament_name' => 'required|string|max:255',
        ]);


        if ($rowValidator->fails()) {
            return [
                'success' => false,
                'errors' => ["Row {$rowNumber}: " . implode(', ', $rowValidator->errors()->all())]
            ];
        }


        return [
            'success' => true,
            'data' => [
                'account_no' => $rowData['account_no'],
                'adjusted_gross_score' => $rowData['adjusted_gross_score'],
                'holes_played' => $rowData['holes_completed'],
                'date_played' => Carbon::parse($rowData['date_played'])->format('Y-m-d'),
                'course_id' => $rowData['course_id'],
                'tee_id' => $rowData['tee_id'],

                'row_number' => $rowNumber
            ]
        ];
    }



    /**
     * Perform bulk insertion of validated player data
     */
    private function bulkInsertScores($validRows)
    {

        Log::info('Starting bulk insert of players', ['count' => count($validRows)]);

        DB::beginTransaction();

        try {
            $now = now();
            $currentUserId = Auth::id();

            // Prepare and insert users
            $usersData = $this->prepareScoreData($validRows, $now);
            User::insert($usersData);


            echo '<pre>';
            print_r($usersData);
            echo '</pre>';

            DB::commit();

            return [
                'success' => true,
                'imported' => count($validRows)
            ];
        } catch (Exception $e) {

            Log::error('Bulk insert failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Bulk insert failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Prepare user data for bulk insertion
     */
    private function prepareScoreData($validRows, $now): array
    {


        // $scorecard = Scorecard::with('scoreDifferentialFormula')->find(1);
        $players = PlayerProfile::all()->keyBy('account_no');
        // echo '<pre>';
        // print_r($players->toArray());
        // echo '</pre>';

        // return [];



        Log::info('Preparing user data for bulk insert', ['count' => count($validRows)]);
        $usersData = [];


        foreach ($validRows as $rowData) {
            $formulaExpression = $this->tournament->tournamentCourses[$rowData['course_id']]->scorecard->scoreDifferentialFormula->formula_expression;


            switch ($rowData['holes_played']) {
                case 'F9':
                    $courseRating = $this->tournament->tournamentCourses[$rowData['course_id']]->scorecard->ratings[$rowData['tee_id']]->f9_course_rating;
                    $slopeRating = $this->tournament->tournamentCourses[$rowData['course_id']]->scorecard->ratings[$rowData['tee_id']]->f9_slope_rating;
                    break;
                case 'B9':
                    $courseRating = $this->tournament->tournamentCourses[$rowData['course_id']]->scorecard->ratings[$rowData['tee_id']]->b9_course_rating;
                    $slopeRating = $this->tournament->tournamentCourses[$rowData['course_id']]->scorecard->ratings[$rowData['tee_id']]->b9_slope_rating;
                    break;
                case '18':
                    $courseRating = $this->tournament->tournamentCourses[$rowData['course_id']]->scorecard->ratings[$rowData['tee_id']]->course_rating;
                    $slopeRating = $this->tournament->tournamentCourses[$rowData['course_id']]->scorecard->ratings[$rowData['tee_id']]->slope_rating;
                    break;

                default:

                    Log::error('Invalid holes played value', ['holes_played' => $rowData['holes_played'], 'row_data' => $rowData]);
                    throw new Exception('Invalid holes played value: ' . $rowData['holes_played']);
            }





            $scoreDifferential = $this->getScoreDifferential(
                $formulaExpression,
                $rowData['adjusted_gross_score'],
                $courseRating,
                $slopeRating
            );
            $usersData[] = [
                'player_profile_id' => $players[$rowData['account_no']]->player_profile_id,
                'user_profile_id' => $players[$rowData['account_no']]->user_profile_id,
                'user_id' => $players[$rowData['account_no']]->user_id,

                'participant_id' => null,
                'tournament_id' => $this->tournament->tournament_id,
                'course_id' => $rowData['course_id'],
                'tee_id' => $rowData['tee_id'],
                'date_played' => $rowData['date_played'],
                'scoring_method' => 'adj',
                'entry_type' => 'migrate',
                'holes_played' => $rowData['holes_played'],
                'tournament_handicap_index' => null,
                'handicap_index_type' => 'legacy',
                'course_handicap' => null,
                'gross_score' => null,
                'adjusted_gross_score' => $rowData['adjusted_gross_score'],
                'net_score' => null,
                'score_differential' => $scoreDifferential,
                'is_verified' => true,

                'verified_by' => Auth::id(),
                'verified_at' => $now,
                'created_at' => $now,
                'created_by' => Auth::id(),
                'updated_at' => $now,
                'updated_by' => Auth::id()
            ];

            Log::info('Prepared user for bulk insert', ['user' => end($usersData)]);
        }

        Log::info('Prepared user data for bulk insert', ['user_data' => $usersData]);

        return $usersData;
    }


    private function getScoreDifferential($formulaExpression, $adjustedGrossScore, $courseRating, $slopeRating): int
    {


        $params = [
            'ADJUSTED_GROSS_SCORE' => $adjustedGrossScore,
            'COURSE_RATING' => $courseRating,
            'SLOPE_RATING' => $slopeRating,
        ];
        return $this->calculateScoreDifferential($params, $formulaExpression);
    }

    private function calculateScoreDifferential($params, $formulaExpression)
    {

        $executor = new MathExecutor();

        foreach ($params as $key => $value) {
            $executor->setVar($key, $value);
        }

        $result = $executor->execute($formulaExpression);

        return $result;
    }
}
