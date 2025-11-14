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

class ImportCheckerService
{
    public function test($filePath)
    {

        $file = storage_path($filePath = 'Tournament.xlsx');

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
        $requiredColumns = ['account_no', 'adjusted_gross_score', 'slope_rating', 'course_rating', 'holes_completed', 'date_played', 'tee_id', 'course_id', 'tournament_name'];

        foreach ($requiredColumns as $column) {
            if (!in_array($column, $header)) {
                return [
                    'success' => false,
                    'message' => "Missing required column: {$column}. Required columns: " . implode(', ', $requiredColumns)
                ];
            }
        }



        $newFormatWithTournamentLevel = [];
        $formatDateChecker = [];
        foreach ($data as $index => $row) {

            if ($index === 0) {
                continue;
            }


            // normalize/format date in column 6 (Excel date or string) to Y-m-d
            $rawDate = $row[6];
            $formattedDate = null;

            if (is_numeric($rawDate)) {
                try {
                    $dt = ExcelDate::excelToDateTimeObject($rawDate);
                    $formattedDate = Carbon::instance($dt)->toDateString();
                } catch (Exception $e) {
                    $formattedDate = trim((string)$rawDate);
                }
            } else {
                try {
                    $formattedDate = Carbon::parse($rawDate)->toDateString();
                } catch (Exception $e) {
                    $formattedDate = trim((string)$rawDate);
                }
            }

            $row[6] = $formattedDate;

            $formatDateChecker[$row[9]][$row[0]][$row[6]][] = $row;


            $newFormatWithTournamentLevel[$row[8]][$row[7]][$row[9]][$row[5]][$row[3] . '_' . $row[4]][] = $row;

            $newFormatWithoutTournamentLevel[$row[8]][$row[7]][$row[5]][$row[3] . '_' . $row[4]][] = $row;
        }
        $errors = [];
        $errorsWithoutTournamentLevel = [];

        $goods = [];
        $goodsWithoutTournamentLevel = [];

        foreach ($newFormatWithTournamentLevel as $course => $courses) {
            foreach ($courses as $tee => $tees) {
                foreach ($tees as $tournament => $tournaments) {
                    foreach ($tournaments as $holePlayed => $holesPlayed) {
                        if (count($holesPlayed) > 1) {

                            $errors[] = array(
                                "course" => $course,
                                "tournament" => $tournament,
                                'tee' => $tee,
                                'holes_played' => $holePlayed,
                                'multiple_slope_course_ratings' => array_keys($holesPlayed)

                            );
                        } else {
                            $goods[$tournament][$course][] = array(
                                "course" => $course,
                                "tournament" => $tournament,
                                'tee' => $tee,
                                'holes_played' => $holePlayed,
                                'slope_course_rating' => array_keys($holesPlayed)

                            );
                        }
                    }
                }
            }
        }

        foreach ($newFormatWithoutTournamentLevel as $course => $courses) {
            foreach ($courses as $tee => $tees) {
                foreach ($tees as $holePlayed => $holesPlayed) {
                    if (count($holesPlayed) > 1) {
                        $errorsWithoutTournamentLevel[] = array(
                            "course" => $course,
                            'tee' => $tee,
                            'holes_played' => $holePlayed,
                            'multiple_slope_course_ratings' => array_keys($holesPlayed)

                        );
                    } else {
                        $goodsWithoutTournamentLevel[$course][] = array(
                            "course" => $course,
                            'tee' => $tee,
                            'holes_played' => $holePlayed,
                            'slope_course_rating' => array_keys($holesPlayed)

                        );
                    }
                }
            }
        }



        $dateErrors = [];


        foreach ($formatDateChecker as $tournaments) {
            foreach ($tournaments as $accountNos) {
                foreach ($accountNos as $tournamentDates) {
                    if (count($tournamentDates) > 1) {
                        $dateErrors[] = $tournamentDates;
                    }
                }
            }
        }



        // echo '<pre>';
        // print_r($dateErrors);

        // echo '</pre>';
        // return;


        // echo '<pre>';
        // print_r($errors);

        // echo '</pre>';

        // return;

        echo '<pre>';
        print_r($errorsWithoutTournamentLevel);

        echo '</pre>';

        return;


        // echo '<pre>';
        // print_r($newFormatWithTournamentLevel);
        // echo '<pre>';
    }
}
