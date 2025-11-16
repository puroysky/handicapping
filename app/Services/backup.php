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

    public function migrate($request = null)
    {
        ini_set('max_execution_time', 300);

        try {

            $this->loadTournamentData();

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
            // Re-key tournamentCourses by course_id
            $tournament->setRelation(
                'tournamentCourses',
                $tournament->tournamentCourses->keyBy('course_id')
            );

            // Loop through each tournamentCourse to re-key ratings
            $tournament->tournamentCourses->each(function ($course) {
                if ($course->scorecard && $course->scorecard->ratings) {
                    // Format: <course_id>_<tee_id>
                    $keyedRatings = $course->scorecard->ratings->keyBy(function ($rating) {
                        return $rating->tee_id;
                    });

                    $course->scorecard->setRelation('ratings', $keyedRatings);
                }
            });
        });

        $this->tournaments = $tournaments;



        Log::debug('Tournaments loaded', ['data' => $tournaments]);
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
            $row = $data[$i];

            $rowValidation = $this->validateSingleRow($row, $columnMap, $i + 1, $validRows);

            if ($rowValidation['success']) {
                $validRows[] = $rowValidation['data'];

                continue;
            }
            $errors = array_merge($errors, $rowValidation['errors']);
        }

        if (empty($validRows)) {
            return [
                'success' => false,
                'message' => 'No valid rows found for import.',
                'errors' => $errors
            ];
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'message' => 'Some rows have errors.',
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
            // Score::insert($scoresData);



            collect($scoresData)->chunk(500)->each(function ($chunk) {
                Score::insert($chunk->toArray());
            });




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

        $scoresData = [];





        $teeMap = [];
        $tees = Tee::get();





        $courses = Course::get()->keyBy('course_code');




        foreach ($tees as $tee) {
            $teeMap[$tee->course->course_code][$tee->tee_code] = $tee->tee_id;
        }



        foreach ($validRows as $rowData) {

            $courseId = $courses[$rowData['course']]->course_id;
            $teeId = $teeMap[$rowData['course']][$rowData['tee']];


            if (!isset($this->tournaments[$rowData['tournament_name']])) {
                throw new Exception("Tournament not found: {$rowData['tournament_name']}");
            }

            $scoreDifferential = $this->getScoreDifferential($rowData, $courseId, $teeId);

            $scoresData[] = [
                'player_profile_id' => $players[$rowData['account_no']]->player_profile_id,
                'user_profile_id' => $players[$rowData['account_no']]->user_profile_id,
                'user_id' => $players[$rowData['account_no']]->user_id,
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
                'score_differential' => $scoreDifferential,
                'is_verified' => true,
                'verified_by' => Auth::id(),
                'verified_at' => $now,
                'created_at' => $now,
                'created_by' => Auth::id(),
                'updated_at' => $now,
                'updated_by' => Auth::id()
            ];
        }

        return $scoresData;
    }


    /**
     * Get score differential using formula expression and ratings
     */
    private function getScoreDifferential($rowData, $courseId, $teeId): int
    {


        $ratings = $this->extractRatingsByHoles(
            $rowData['holes_played'],
            $courseId,
            $teeId,
            $rowData['tournament_name']
        );

        $formulaExpression = $this->tournaments[$rowData['tournament_name']]->tournamentCourses[$courseId]
            ->scorecard->scoreDifferentialFormula->formula_expression;

        if (empty($formulaExpression)) {
            throw new Exception("Missing score differential formula for course_id: {$courseId} in tournament: {$rowData['tournament_name']}");
        }




        $params = [
            'ADJUSTED_GROSS_SCORE' => $rowData['adjusted_gross_score'],
            'COURSE_RATING' => $ratings['courseRating'],
            'SLOPE_RATING' => $ratings['slopeRating'],
        ];
        return $this->calculateScoreDifferential($params, $formulaExpression);
    }

    /**
     * Extract course and slope ratings based on holes played
     */
    private function extractRatingsByHoles($holesPlayed, $courseId, $teeId, $tournamentName): array
    {
        Log::debug('Extracting ratings', [
            'holes_played' => $holesPlayed,
            'course_id' => $courseId,
            'tee_id' => $teeId,
            'tournament_name' => $tournamentName,

        ]);
        $rating = $this->tournaments[$tournamentName]->tournamentCourses[$courseId]->scorecard->ratings[$teeId];


        // print_r($this->tournaments[$tournamentName]->tournamentCourses[$courseId]->scorecard->ratings[$teeId]->toArray());

        $result =  match ($holesPlayed) {
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

        if (empty($result['courseRating']) || empty($result['slopeRating'])) {
            throw new Exception("Missing ratings for course_id: {$courseId}, tee_id: {$teeId}, holes_played: {$holesPlayed}");
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

        $result = $executor->execute($formulaExpression);

        return (int) $result;
    }
}




<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\PlayerProfile;
use App\Models\Score;
use App\Models\SystemSetting;
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

class LocalHandicapIndexCalculationService
{
    private $bracket;
    private $chunkSize = 1000;

    public function calculate()
    {
        try {
            // Load configuration
            $calculationTable = Tournament::find(request()->tournament_id)?->tournament_handicap_calculation_table;
            if (!$calculationTable) {
                throw new Exception('Tournament handicap calculation table not found');
            }

            $this->bracket = collect(json_decode($calculationTable, true))
                ->sortByDesc('max')
                ->toArray();

            // Process scores in chunks to minimize memory usage
            $userLocalHandicapIndexes = [];
            $processed = 0;

            Score::orderBy('date_played')
                ->chunk($this->chunkSize, function ($scores) use (&$userLocalHandicapIndexes, &$processed) {
                    $userScores = $this->groupScoresByUser($scores);
                    $calculations = $this->calculateForUsers($userScores);
                    $userLocalHandicapIndexes = array_merge($userLocalHandicapIndexes, $calculations);
                    $processed += count($scores);
                });

            Log::info("Local handicap calculation complete", [
                'total_processed' => $processed,
                'users_calculated' => count($userLocalHandicapIndexes)
            ]);

            return $userLocalHandicapIndexes;
        } catch (Exception $e) {
            Log::error('Local handicap calculation failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Group scores by user from a batch
     */
    private function groupScoresByUser($scores): array
    {
        $userScores = [];

        foreach ($scores as $score) {
            $userScores[$score->user_id][] = [
                'score_id' => $score->score_id,
                'score_differential' => $score->score_differential,
                'round' => $score->holes_played === 'F9' || $score->holes_played === 'B9' ? 0.5 : 1,
            ];
        }

        return $userScores;
    }

    /**
     * Calculate handicap index for users
     */
    private function calculateForUsers($userScores): array
    {
        $results = [];

        foreach ($userScores as $userId => $scores) {
            // Keep only latest 20 rounds
            $scores = array_slice($scores, -20);
            $roundCount = count($scores);

            $result = $this->findMatchingBracket($userId, $scores, $roundCount);
            if ($result) {
                $results[$userId] = $result;
            }
        }

        return $results;
    }

    /**
     * Find matching bracket and calculate index
     */
    private function findMatchingBracket($userId, $scores, $roundCount)
    {
        foreach ($this->bracket as $config) {
            $minRounds = (int)$config['min'];
            $maxRounds = (int)$config['max'];

            if ($roundCount >= $minRounds && $roundCount <= $maxRounds) {
                return $this->applyCalculationMethod(
                    $userId,
                    $scores,
                    $config,
                    $roundCount
                );
            }
        }

        return null;
    }

    /**
     * Apply the configured calculation method
     */
    private function applyCalculationMethod($userId, $scores, $config, $roundCount)
    {
        $count = (int)$config['count'];
        $method = $config['method'];

        // Sort scores by differential (ascending for LOWEST/AVERAGE_OF_LOWEST, descending for HIGHEST)
        $sorted = $scores;
        if ($method === 'HIGHEST') {
            usort($sorted, fn($a, $b) => $b['score_differential'] <=> $a['score_differential']);
        } else {
            usort($sorted, fn($a, $b) => $a['score_differential'] <=> $b['score_differential']);
        }

        $selected = array_slice($sorted, 0, $count);

        $scoreDiff = match ($method) {
            'LOWEST' => $selected[0]['score_differential'] ?? 0,
            'HIGHEST' => $selected[0]['score_differential'] ?? 0,
            'AVERAGE_OF_LOWEST' => array_sum(array_column($selected, 'score_differential')) / count($selected),
            default => 0,
        };

        $localHandicapIndex = $scoreDiff + (float)$config['adjustment'];

        Log::debug("Calculated handicap for user {$userId}", [
            'rounds' => $roundCount,
            'method' => $method,
            'handicap_index' => round($localHandicapIndex, 2)
        ]);

        return [
            'local_handicap_index' => round($localHandicapIndex, 2),
            'details' => [
                'rounds_considered' => $roundCount,
                'method' => $method,
                'count' => $count,
                'adjustment' => (float)$config['adjustment'],
                'selected_scores' => $selected,
            ]
        ];
    }
}
