<?php

namespace App\Services;

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

class PlayerImportService
{



    public function import($request)
    {


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
        $requiredColumns = ['whs_no', 'account_no', 'first_name', 'middle_name', 'last_name', 'birthdate', 'sex'];

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
            'whs_numbers' => PlayerProfile::pluck('whs_no')->toArray(),
            'account_numbers' => PlayerProfile::pluck('account_no')->toArray(),
            'emails' => User::pluck('email')->toArray()
        ];
    }

    /**
     * Validate a single row of import data
     */
    private function validateSingleRow($row, $columnMap, $rowNumber, $existingData, $validRows)
    {
        // Extract and clean row data
        $rowData = [
            'whs_no' => isset($row[$columnMap['whs_no']]) ? trim($row[$columnMap['whs_no']]) : '',
            'account_no' => isset($row[$columnMap['account_no']]) ? trim($row[$columnMap['account_no']]) : '',
            'first_name' => isset($row[$columnMap['first_name']]) ? trim($row[$columnMap['first_name']]) : '',
            'middle_name' => isset($row[$columnMap['middle_name']]) ? trim($row[$columnMap['middle_name']]) : '',
            'last_name' => isset($row[$columnMap['last_name']]) ? trim($row[$columnMap['last_name']]) : '',
            'birthdate' => isset($row[$columnMap['birthdate']]) ? Carbon::instance(ExcelDate::excelToDateTimeObject($row[$columnMap['birthdate']])) : '',
            'sex' => isset($row[$columnMap['sex']]) ? trim(strtoupper($row[$columnMap['sex']])) : '',
        ];



        Log::debug('Validating row', ['row_number' => $rowNumber, 'row_data' => $rowData]);
        // Validate field formats
        $rowValidator = Validator::make($rowData, [
            'whs_no' => 'required|numeric|integer',
            'account_no' => 'required|string|max:50',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'birthdate' => 'required',
            'sex' => 'required|string|in:M,F',
        ]);

        if ($rowValidator->fails()) {
            return [
                'success' => false,
                'errors' => ["Row {$rowNumber}: " . implode(', ', $rowValidator->errors()->all())]
            ];
        }

        $sex = strtoupper($rowData['sex']);
        $email = strtolower($rowData['account_no']) . '@golf.local';

        // Check for duplicates
        $duplicateCheck = $this->checkForDuplicates($rowData, $email, $existingData, $validRows, $rowNumber);
        if (!$duplicateCheck['success']) {

            return $duplicateCheck;
        }

        return [
            'success' => true,
            'data' => [
                'whs_no' => $rowData['whs_no'],
                'account_no' => $rowData['account_no'],
                'first_name' => $rowData['first_name'],
                'middle_name' => $rowData['middle_name'],
                'last_name' => $rowData['last_name'],
                'birthdate' => $rowData['birthdate'],
                'sex' => $sex,
                'email' => $email,
                'row_number' => $rowNumber
            ]
        ];
    }

    /**
     * Check for duplicate data in database and import batch
     */
    private function checkForDuplicates($rowData, $email, $existingData, $validRows, $rowNumber)
    {
        $errors = [];

        // Check duplicates in existing database
        if (in_array($rowData['whs_no'], $existingData['whs_numbers'])) {
            $errors[] = "Row {$rowNumber}: WHS No {$rowData['whs_no']} already exists in database.";
        }

        if (in_array($rowData['account_no'], $existingData['account_numbers'])) {
            $errors[] = "Row {$rowNumber}: Account No {$rowData['account_no']} already exists in database.";
        }

        if (in_array($email, $existingData['emails'])) {
            $errors[] = "Row {$rowNumber}: Email {$email} already exists in database.";
        }

        // Check duplicates within current import batch
        $batchWhsNumbers = array_column($validRows, 'whs_no');
        $batchAccountNumbers = array_column($validRows, 'account_no');
        $batchEmails = array_column($validRows, 'email');

        if (in_array($rowData['whs_no'], $batchWhsNumbers)) {
            $errors[] = "Row {$rowNumber}: Duplicate WHS No {$rowData['whs_no']} found in import file.";
        }

        if (in_array($rowData['account_no'], $batchAccountNumbers)) {
            $errors[] = "Row {$rowNumber}: Duplicate Account No {$rowData['account_no']} found in import file.";
        }

        if (in_array($email, $batchEmails)) {
            $errors[] = "Row {$rowNumber}: Duplicate email {$email} found in import file.";
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

            // Prepare and insert users
            $usersData = $this->prepareUsersData($validRows, $now);
            User::insert($usersData);

            // Get inserted user IDs
            $startUserId = User::where('created_at', $now)->min('id');

            // Prepare and insert profiles
            $profilesData = $this->prepareProfilesData($validRows, $startUserId, $currentUserId, $now);

            Log::info('Inserting user profiles and player profiles', [
                'user_profiles_count' => count($profilesData['userProfiles']),
                'player_profiles_count' => count($profilesData['playerProfiles'])
            ]);
            UserProfile::insert($profilesData['userProfiles']);


            Log::info('Inserting player profiles', ['count' => count($profilesData['playerProfiles'])]);
            PlayerProfile::insert($profilesData['playerProfiles']);

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
    private function prepareUsersData($validRows, $now): array
    {

        Log::info('Preparing user data for bulk insert', ['count' => count($validRows)]);
        $usersData = [];

        foreach ($validRows as $rowData) {
            $usersData[] = [
                // 'name' => $rowData['first_name'] . ' ' . $rowData['last_name'],
                // 'first_name' => $rowData['first_name'],
                // 'middle_name' => $rowData['middle_name'],
                // 'last_name' => $rowData['last_name'],
                'email' => $rowData['email'],
                'password' => 'password123',
                'role' => 'user',
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => Auth::id()
            ];

            Log::info('Prepared user for bulk insert', ['user' => end($usersData)]);
        }

        Log::info('Prepared user data for bulk insert', ['user_data' => $usersData]);

        return $usersData;
    }

    /**
     * Prepare profile data for bulk insertion
     */
    private function prepareProfilesData($validRows, $startUserId, $currentUserId, $now)
    {

        Log::info('Preparing profile data for bulk insert', ['count' => count($validRows)]);

        $userProfilesData = [];
        $playerProfilesData = [];

        foreach ($validRows as $index => $rowData) {
            $userId = $startUserId + $index;

            $userProfilesData[] = [
                'user_id' => $userId,
                'first_name' => $rowData['first_name'],
                'middle_name' => $rowData['middle_name'],
                'last_name' => $rowData['last_name'],
                'birthdate' => $rowData['birthdate'],
                'sex' => $rowData['sex'],
                'created_by' => $currentUserId,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $playerProfilesData[] = [
                'user_id' => $userId,
                'user_profile_id' => $userId, // Assuming user_profile_id matches user_id
                'account_no' => $rowData['account_no'],
                'whs_no' => $rowData['whs_no'],
                'created_by' => $currentUserId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }


        Log::info('Prepared profile data for bulk insert', [
            'user_profiles' => $userProfilesData,
            'player_profiles' => $playerProfilesData
        ]);
        return [
            'userProfiles' => $userProfilesData,
            'playerProfiles' => $playerProfilesData
        ];
    }
}
