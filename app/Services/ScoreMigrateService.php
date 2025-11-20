<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use App\Models\PlayerProfile;
use App\Models\Score;
use App\Models\Tee;
use App\Models\Tournament;
use Exception;
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
    protected $tournaments;
    protected $players;
    protected $courses;
    protected $teeMap;
    protected $authId;

    public function migrate($request = null)
    {
        ini_set('max_execution_time', 300);

        try {
            $this->authId = Auth::id();
            $this->loadTournamentData();
            $this->loadLookupData();

            $fileValidation = $this->validateImportFile($request);
            if (!$fileValidation['success']) {
                return $fileValidation;
            }

            $fileData = $this->parseImportFile($request->file('import_file'));
            if (!$fileData['success']) {
                return $fileData;
            }

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
    private function loadTournamentData(): void
    {
        $tournaments = Tournament::with(
            'tournamentCourses.scorecard.scoreDifferentialFormula',
            'tournamentCourses.scorecard.ratings'
        )
            ->get()
            ->keyBy('tournament_name');

        $tournaments->each(function ($tournament) {
            $tournament->setRelation(
                'tournamentCourses',
                $tournament->tournamentCourses->keyBy('course_id')
            );

            $tournament->tournamentCourses->each(function ($course) {
                if ($course->scorecard?->ratings) {
                    $course->scorecard->setRelation(
                        'ratings',
                        $course->scorecard->ratings->keyBy('tee_id')
                    );
                }
            });
        });

        $this->tournaments = $tournaments;
        Log::debug('Tournaments loaded', ['count' => $tournaments->count()]);
    }

    /**
     * Pre-load lookup data to minimize queries during import
     */
    private function loadLookupData(): void
    {
        $this->players = PlayerProfile::all()->keyBy('account_no');
        $this->courses = Course::get()->keyBy('course_code');

        $this->teeMap = [];
        Tee::with('course')->get()->each(function ($tee) {
            $courseCode = $tee->course->course_code;
            $this->teeMap[$courseCode][$tee->tee_code] = $tee->tee_id;
        });
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
        $requiredColumns = ['account_no', 'adjusted_gross_score', 'holes_played', 'date_played', 'tee', 'course', 'tournament_name'];

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
            $rowValidation = $this->validateSingleRow($data[$i], $columnMap, $i + 1);

            if ($rowValidation['success']) {
                $validRows[] = $rowValidation['data'];
            } else {
                $errors = array_merge($errors, $rowValidation['errors']);
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
            'success' => empty($errors),
            'validRows' => $validRows,
            'errors' => $errors,
            'message' => empty($errors)
                ? "All " . count($validRows) . " rows valid."
                : count($validRows) . " valid, " . count($errors) . ' invalid.'
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
    private function validateSingleRow($row, $columnMap, $rowNumber)
    {
        $rowData = [
            'account_no' => isset($row[$columnMap['account_no']]) ? trim($row[$columnMap['account_no']]) : '',
            'adjusted_gross_score' => isset($row[$columnMap['adjusted_gross_score']]) ? trim($row[$columnMap['adjusted_gross_score']]) : '',
            'holes_played' => isset($row[$columnMap['holes_played']]) ? trim($row[$columnMap['holes_played']]) : '',
            'date_played' => isset($row[$columnMap['date_played']]) ?
                (is_numeric($row[$columnMap['date_played']]) ?
                    ExcelDate::excelToDateTimeObject($row[$columnMap['date_played']])->format('Y-m-d') :
                    Carbon::parse(trim($row[$columnMap['date_played']]))->format('Y-m-d')) : '',
            'tee' => isset($row[$columnMap['tee']]) ? trim($row[$columnMap['tee']]) : '',
            'course' => isset($row[$columnMap['course']]) ? trim($row[$columnMap['course']]) : '',
            'tournament_name' => isset($row[$columnMap['tournament_name']]) ? trim($row[$columnMap['tournament_name']]) : '',
        ];

        $rowValidator = Validator::make($rowData, [
            'account_no' => 'required|string|max:50',
            'adjusted_gross_score' => 'required|integer|min:1|max:200',
            'holes_played' => 'required|string|in:F9,B9,18',
            'date_played' => 'required|date',
            'tee' => 'required|in:R,B,W,G',
            'course' => 'required|in:NORTH,SOUTH',
            'tournament_name' => 'required|string|max:100',
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
                'holes_played' => $rowData['holes_played'],
                'date_played' => Carbon::parse($rowData['date_played'])->format('Y-m-d'),
                'course' => $rowData['course'],
                'tee' => $rowData['tee'],
                'row_number' => $rowNumber,
                'tournament_name' => $rowData['tournament_name'],
            ]
        ];
    }

    /**
     * Perform bulk insertion of validated score data
     */
    private function bulkInsertScores($validRows)
    {
        DB::beginTransaction();

        try {
            $now = now();
            $scoresData = $this->prepareScoreData($validRows, $now);
            $imported = 0;

            foreach (array_chunk($scoresData, 500) as $chunk) {
                Score::insert($chunk);
                $imported += count($chunk);
            }

            DB::commit();

            return [
                'success' => true,
                'imported' => $imported
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
        $scoresData = [];

        foreach ($validRows as $rowData) {
            $courseId = $this->courses[$rowData['course']]->course_id;
            $teeId = $this->teeMap[$rowData['course']][$rowData['tee']];


            if (!isset($this->tournaments[$rowData['tournament_name']])) {
                throw new Exception("Tournament not found: {$rowData['tournament_name']}");
            }

            $scoreDifferential = $this->getScoreDifferential($rowData, $courseId, $teeId);

            $scoresData[] = [
                'player_profile_id' => $this->players[$rowData['account_no']]->player_profile_id,
                'user_profile_id' => $this->players[$rowData['account_no']]->user_profile_id,
                'user_id' => $this->players[$rowData['account_no']]->user_id,
                'participant_id' => null,
                'tournament_id' => $this->tournaments[$rowData['tournament_name']]->tournament_id,
                'tournament_course_id' => $this->tournaments[$rowData['tournament_name']]->tournamentCourses[$courseId]->tournament_course_id,
                'division_id' => null,
                'course_id' => $courseId,
                'tee_id' => $teeId,
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
                'score_differential' => $scoreDifferential['score_differential'],
                'course_rating' => $scoreDifferential['course_rating'],
                'slope_rating' => $scoreDifferential['slope_rating'],
                'is_verified' => true,
                'verified_by' => $this->authId,
                'verified_at' => $now,
                'created_at' => $now,
                'created_by' => $this->authId,
                'updated_at' => $now,
                'updated_by' => $this->authId
            ];
        }

        return $scoresData;
    }


    /**
     * Get score differential using formula expression and ratings
     */
    private function getScoreDifferential($rowData, $courseId, $teeId)
    {
        $tournament = $this->tournaments[$rowData['tournament_name']];
        $ratings = $this->extractRatingsByHoles(
            $rowData['holes_played'],
            $courseId,
            $teeId,
            $rowData['tournament_name']
        );

        $formulaExpression = $tournament->tournamentCourses[$courseId]
            ->scorecard->scoreDifferentialFormula->formula_expression;

        if (empty($formulaExpression)) {
            throw new Exception("Missing score differential formula for course_id: {$courseId}");
        }

        $scoreDifferential = $this->calculateScoreDifferential([
            'ADJUSTED_GROSS_SCORE' => $rowData['adjusted_gross_score'],
            'COURSE_RATING' => $ratings['courseRating'],
            'SLOPE_RATING' => $ratings['slopeRating'],
        ], $formulaExpression);

        return array(
            'score_differential' => $scoreDifferential,
            'course_rating' => $ratings['courseRating'],
            'slope_rating' => $ratings['slopeRating'],
        );
    }

    /**
     * Extract course and slope ratings based on holes played
     */
    private function extractRatingsByHoles($holesPlayed, $courseId, $teeId, $tournamentName): array
    {
        $rating = $this->tournaments[$tournamentName]->tournamentCourses[$courseId]->scorecard->ratings[$teeId];

        $result = match ($holesPlayed) {
            'F9' => ['courseRating' => $rating->f9_course_rating, 'slopeRating' => $rating->f9_slope_rating],
            'B9' => ['courseRating' => $rating->b9_course_rating, 'slopeRating' => $rating->b9_slope_rating],
            '18' => ['courseRating' => $rating->course_rating, 'slopeRating' => $rating->slope_rating],
            default => throw new Exception('Invalid holes played: ' . $holesPlayed),
        };

        if (empty($result['courseRating']) || empty($result['slopeRating'])) {
            throw new Exception("Missing ratings: course_id={$courseId}, tee_id={$teeId}, holes={$holesPlayed}");
        }

        return $result;
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

        return (int) $executor->execute($formulaExpression);
    }
}
