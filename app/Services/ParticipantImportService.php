<?php

namespace App\Services;

use App\Models\Participant;
use App\Models\ParticipantCourseHandicap;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\PlayerProfile;
use App\Models\Tee;
use App\Models\Tournament;
use App\Models\TournamentCourse;
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

class PlayerImportService
{


    protected Tournament $tournament;




    public function import($request)
    {
        $this->tournament = Tournament::find(1);

        ini_set('max_execution_time', 300); // 300 seconds = 5 minutes

        try {
            // Validate the uploaded file
            $fileValidation = $this->validateImportFile($request);
            if (!$fileValidation['success']) {
                return $fileValidation;
            }

            // Parse and validate file structure
            $fileData = $this->parseImportFile($request->file('import_file'));
            if (!$fileData['success']) {
                return $fileData;
            }


            Log::debug('Column map', ['columnMap' => $fileData['columnMap']]);

            // Validate all rows and collect valid data
            $validationResult = $this->validateImportRows($fileData['data'], $fileData['columnMap']);
            if (!$validationResult['success']) {
                return $validationResult;
            }

            // Perform bulk insertion
            $insertResult = $this->bulkInsertPlayers($validationResult['validRows']);
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
        $requiredColumns = ['account_no', 'handicp_index', 'north_tee', 'south_tee'];

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

        // Get existing data for duplicate checking
        $existingData = $this->getExistingPlayerData();

        // Process each data row
        for ($i = 1; $i < count($data); $i++) {
            $row = $data[$i];

            try {
                $rowValidation = $this->validateSingleRow($row, $columnMap, $i + 1, $existingData, $validRows);

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

            'account_numbers' => Participant::with('playerProfile')->get()->pluck('playerProfile.account_no')->toArray()
        ];
    }

    /**
     * Validate a single row of import data
     */
    private function validateSingleRow($row, $columnMap, $rowNumber, $existingData, $validRows)
    {
        // Extract and clean row data
        $rowData = [

            'account_no' => isset($row[$columnMap['account_no']]) ? trim($row[$columnMap['account_no']]) : '',
            'whs_handicap_index' => isset($row[$columnMap['whs_handicap_index']]) ? trim($row[$columnMap['whs_handicap_index']]) : '',
            'north_tee' => isset($row[$columnMap['north_tee']]) ? trim($row[$columnMap['north_tee']]) : '',
            'south_tee' => isset($row[$columnMap['south_tee']]) ? trim($row[$columnMap['south_tee']]) : '',
        ];



        Log::debug('Validating row', ['row_number' => $rowNumber, 'row_data' => $rowData]);
        // Validate field formats
        $rowValidator = Validator::make($rowData, [
            'account_no' => 'required|string|max:50',
            'whs_handicap_index' => 'required|integer',
            'north_tee' => 'required|string|max:100',
            'south_tee' => 'required|string|max:100',
        ]);

        if ($rowValidator->fails()) {
            return [
                'success' => false,
                'errors' => ["Row {$rowNumber}: " . implode(', ', $rowValidator->errors()->all())]
            ];
        }




        // Check for duplicates
        $duplicateCheck = $this->checkForDuplicates($rowData, $existingData, $validRows, $rowNumber);
        if (!$duplicateCheck['success']) {
            return $duplicateCheck;
        }

        return [
            'success' => true,
            'data' => [
                'account_no' => $rowData['account_no'],
                'whs_handicap_index' => $rowData['whs_handicap_index'],
                'north_tee' => $rowData['north_tee'],
                'south_tee' => $rowData['south_tee'],
                'row_number' => $rowNumber
            ]
        ];
    }

    /**
     * Check for duplicate data in database and import batch
     */
    private function checkForDuplicates($rowData, $existingData, $validRows, $rowNumber)
    {
        $errors = [];

        // Check duplicates in existing database

        if (in_array($rowData['account_no'], $existingData['account_numbers'])) {
            $errors[] = "Row {$rowNumber}: Account No {$rowData['account_no']} already exists in database.";
        }


        // Check duplicates within current import batch

        $batchAccountNumbers = array_column($validRows, 'account_no');


        if (in_array($rowData['account_no'], $batchAccountNumbers)) {
            $errors[] = "Row {$rowNumber}: Duplicate Account No {$rowData['account_no']} found in import file.";
        }


        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        return ['success' => true];
    }

    /**
     * Perform bulk insertion of validated player data
     */
    private function bulkInsertPlayers($validRows)
    {

        Log::info('Starting bulk insert of players', ['count' => count($validRows)]);

        DB::beginTransaction();

        try {
            $now = now();
            $currentUserId = Auth::id();

            // Prepare and insert participants
            $participantsData = $this->prepareParticipantsData($validRows, $now);
            Participant::insert($participantsData['participants']);
            ParticipantCourseHandicap::insert($participantsData['participantCourseHandicaps']);


            Log::info('Inserting user profiles and player profiles', [
                'user_profiles_count' => count($participantsData['userProfiles']),
                'player_profiles_count' => count($participantsData['playerProfiles'])
            ]);


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
     * Prepare participant data for bulk insertion
     */
    private function prepareParticipantsData($validRows, $now): array
    {



        $maxParticipantId = Participant::max('participant_id');


        Log::info('Preparing participant data for bulk insert', ['count' => count($validRows)]);
        $participantsData = [];

        $participanttCourseHandicapsData = [];

        $tees = $this->getTeesForCourse($this->tournament->tournament_id);




        $playerInfo = PlayerProfile::get()->keyBy('account_no')->toArray();






        foreach ($validRows as $rowData) {

            $maxParticipantId++;



            $playerProfileId = $playerInfo[$rowData['account_no']]['player_profile_id'] ?? null;
            $userId = $playerInfo[$rowData['account_no']]['user_id'] ?? null;


            $participantCourseHandicapsData[] = [
                'participant_id' => $maxParticipantId,
                'tournament_id' => $this->tournament->tournament_id,
                'course_id' => $tees['N']['course_id'] ?? null,
                'tee_id' => $tees['N']['tees'][$rowData['north_tee']] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => Auth::id()
            ];

            $participanttCourseHandicapsData[] = [
                'participant_id' => $maxParticipantId,
                'tournament_id' => $this->tournament->tournament_id,
                'course_id' => $tees['S']['course_id'] ?? null,
                'tee_id' => $tees['S']['tees'][$rowData['south_tee']] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => Auth::id()
            ];




            $participantsData[] = [
                'participant_id' => $maxParticipantId,
                'tournament_id' => $this->tournament->tournament_id,
                'player_profile_id' => $playerProfileId,
                'user_id' => $userId,
                'whs_handicap_index' => $rowData['whs_handicap_index'],
                'remarks' => $rowData['remarks'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => Auth::id()
            ];

            Log::info('Prepared participant for bulk insert', ['participant' => end($participantsData)]);
        }


        Log::info('Prepared participant data for bulk insert', ['participant_data' => $participantsData]);

        return $participantsData;
    }



    /**
     * Get all tees grouped by course code for a given tournament.
     *
     * @param int $tournamentId
     * @return array
     */
    private function getTeesForCourse(int $tournamentId)
    {
        $tournamentCourses = TournamentCourse::where('tournament_id', 1)->get();
        $tees = Tee::with('course')
            ->whereIn('course_id', $tournamentCourses->pluck('course_id'))
            ->get();

        $courseTees = [];
        foreach ($tees as $tee) {
            $courseCode = $tee->course->course_code;
            $teeId = $tee->tee_id;
            $teeCode = $tee->tee_code;
            $courseTees[$courseCode]['course_id'] = $tee->course->course_id;
            $courseTees[$courseCode]['tees'][$teeCode] = $teeId;
        }
        return $courseTees;
    }
}
