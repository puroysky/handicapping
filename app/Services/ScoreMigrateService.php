<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\PlayerProfile;
use App\Models\Score;
use App\Models\Tournament;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        ini_set('max_execution_time', 300);

        try {
            $this->loadTournamentData($request);

            $fileValidation = $this->validateImportFile($request);
            if (!$fileValidation['success']) {
                return $fileValidation;
            }

            $fileData = $this->parseImportFile($request->file('import_file'));
            if (!$fileData['success']) {
                return $fileData;
            }

            Log::debug('Column map', ['columnMap' => $fileData['columnMap']]);

            $validationResult = $this->validateImportRows($fileData['data'], $fileData['columnMap']);
            if (!$validationResult['success']) {
                return $validationResult;
            }

            $insertResult = $this->bulkInsertScores($validationResult['validRows']);
            if (!$insertResult['success']) {
                return $insertResult;
            }

            return [
                'success' => true,
                'message' => "Import completed. {$insertResult['imported']} scores imported successfully.",
                'imported' => $insertResult['imported'],
                'errors' => $validationResult['errors']
            ];
        } catch (Exception $e) {
            Log::error('Migration failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Load and setup tournament data with required relationships
     */
    private function loadTournamentData($request): void
    {
        $this->tournament = Tournament::with(
            'tournamentCourses.scorecard.scoreDifferentialFormula',
            'tournamentCourses.scorecard.ratings'
        )->find($request->tournament_id);

        $this->tournament->setRelation(
            'tournamentCourses',
            $this->tournament->tournamentCourses->keyBy('course_id')
        );

        foreach ($this->tournament->tournamentCourses as $tournamentCourse) {
            if ($tournamentCourse->scorecard?->ratings) {
                $tournamentCourse->scorecard->setRelation(
                    'ratings',
                    $tournamentCourse->scorecard->ratings->keyBy('tee_id')
                );
            }
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
        $requiredColumns = ['account_no', 'adjusted_gross_score', 'holes_completed', 'date_played', 'tee_id', 'course_id'];

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
        $rowData = [
            'account_no' => isset($row[$columnMap['account_no']]) ? trim($row[$columnMap['account_no']]) : '',
            'adjusted_gross_score' => isset($row[$columnMap['adjusted_gross_score']]) ? trim($row[$columnMap['adjusted_gross_score']]) : '',
            'holes_completed' => isset($row[$columnMap['holes_completed']]) ? trim($row[$columnMap['holes_completed']]) : '',
            'date_played' => isset($row[$columnMap['date_played']]) ?
                (is_numeric($row[$columnMap['date_played']]) ?
                    ExcelDate::excelToDateTimeObject($row[$columnMap['date_played']])->format('Y-m-d') :
                    Carbon::parse(trim($row[$columnMap['date_played']]))->format('Y-m-d')) : '',
            'tee_id' => isset($row[$columnMap['tee_id']]) ? trim($row[$columnMap['tee_id']]) : '',
            'course_id' => isset($row[$columnMap['course_id']]) ? trim($row[$columnMap['course_id']]) : '',
        ];

        Log::debug('Validating row', ['row_number' => $rowNumber, 'row_data' => $rowData]);

        $rowValidator = Validator::make($rowData, [
            'account_no' => 'required|string|max:50',
            'adjusted_gross_score' => 'required|integer|min:1|max:200',
            'holes_completed' => 'required|string|in:F9,B9,18',
            'date_played' => 'required|date',
            'tee_id' => 'required|integer|max:10',
            'course_id' => 'required|integer|max:10',
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
     * Perform bulk insertion of validated score data
     */
    private function bulkInsertScores($validRows)
    {
        Log::info('Starting bulk insert of scores', ['count' => count($validRows)]);

        DB::beginTransaction();

        try {
            $now = now();

            $scoresData = $this->prepareScoreData($validRows, $now);
            Score::insert($scoresData);

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
     * Prepare score data for bulk insertion
     */
    private function prepareScoreData($validRows, $now): array
    {
        $players = PlayerProfile::all()->keyBy('account_no');

        Log::info('Preparing score data for bulk insert', ['count' => count($validRows)]);
        $scoresData = [];

        foreach ($validRows as $rowData) {
            $ratings = $this->extractRatingsByHoles(
                $rowData['holes_played'],
                $rowData['course_id'],
                $rowData['tee_id']
            );

            $formulaExpression = $this->tournament->tournamentCourses[$rowData['course_id']]
                ->scorecard->scoreDifferentialFormula->formula_expression;

            $scoreDifferential = $this->getScoreDifferential(
                $formulaExpression,
                $rowData['adjusted_gross_score'],
                $ratings['courseRating'],
                $ratings['slopeRating']
            );

            $scoresData[] = [
                'player_profile_id' => $players[$rowData['account_no']]->player_profile_id,
                'user_profile_id' => $players[$rowData['account_no']]->user_profile_id,
                'user_id' => $players[$rowData['account_no']]->user_id,
                'participant_id' => null,
                'tournament_id' => $this->tournament->tournament_id,
                'tournament_course_id' => $this->tournament->tournamentCourses[$rowData['course_id']]->tournament_course_id,
                'division_id' => null,
                'course_id' => $rowData['course_id'],
                'tee_id' => $rowData['tee_id'],
                'date_played' => $rowData['date_played'],
                'scoring_method' => 'adj',
                'score_type' => 'tmt',
                'score_source' => 'legacy',
                'holes_played' => $rowData['holes_played'],
                'handicap_index' => null,
                'handicap_index_source' => 'legacy',
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

            Log::debug('Prepared score for bulk insert', ['score_count' => count($scoresData)]);
        }

        return $scoresData;
    }


    /**
     * Get score differential using formula expression and ratings
     */
    private function getScoreDifferential($formulaExpression, $adjustedGrossScore, $courseRating, $slopeRating): int
    {
        $params = [
            'ADJUSTED_GROSS_SCORE' => $adjustedGrossScore,
            'COURSE_RATING' => $courseRating,
            'SLOPE_RATING' => $slopeRating,
        ];
        return $this->calculateScoreDifferential($params, $formulaExpression);
    }

    /**
     * Extract course and slope ratings based on holes played
     */
    private function extractRatingsByHoles($holesPlayed, $courseId, $teeId): array
    {
        $rating = $this->tournament->tournamentCourses[$courseId]->scorecard->ratings[$teeId];

        return match ($holesPlayed) {
            'F9' => [
                'courseRating' => $rating->f9_course_rating,
                'slopeRating' => $rating->f9_slope_rating,
            ],
            'B9' => [
                'courseRating' => $rating->b9_course_rating,
                'slopeRating' => $rating->b9_slope_rating,
            ],
            '18' => [
                'courseRating' => $rating->course_rating,
                'slopeRating' => $rating->slope_rating,
            ],
            default => throw new Exception('Invalid holes played value: ' . $holesPlayed),
        };
    }

    /**
     * Calculate score differential by evaluating formula expression
     */
    private function calculateScoreDifferential($params, $formulaExpression): int
    {
        $executor = new MathExecutor();

        foreach ($params as $key => $value) {
            $executor->setVar($key, $value);
        }

        $result = $executor->execute($formulaExpression);

        return (int) $result;
    }
}
