<?php

namespace App\Services;

use App\Models\Participant;
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


        $calculationTable = Tournament::find(request()->tournament_id)?->tournament_handicap_calculation_table;

        if (!$calculationTable) {
            throw new Exception('Tournament handicap calculation table not found');
        }

        $bracket = collect(json_decode($calculationTable, true))
            ->sortByDesc('max')
            ->toArray();



        $participantsUserIds = Participant::where('tournament_id', request()->tournament_id)
            ->pluck('user_id');

        // Step 1: Build subquery with window function
        $sub = DB::table('scores')
            ->select('score_id', 'user_id', 'score_differential', 'holes_played', 'date_played')
            ->selectRaw('ROW_NUMBER() OVER (PARTITION BY user_id ORDER BY date_played DESC) as rn')
            ->whereIn('user_id', [428]);
        // ->whereIn('user_id', $participantsUserIds);

        // Step 2: Wrap in derived table and filter to top 20 per user
        $scores = DB::table(DB::raw("({$sub->toSql()}) as t"))
            ->mergeBindings($sub)
            ->where('rn', '<=', 20)
            ->orderBy('user_id')
            ->orderBy('date_played')
            ->get();


        // echo '<pre>';
        // print_r($scores->toArray());
        // echo '</pre>';
        // return;
        // Step 3: Group scores per user (same as before)
        $users = [];
        foreach ($scores as $score) {
            $users[$score->user_id][] = [
                'score_id' => $score->score_id,
                'score_differential' => $score->score_differential,
                'round' => $score->holes_played == 'F9' || $score->holes_played == 'B9' ? 0.5 : 1,
            ];
        }

        // Step 4: Continue your existing bracket calculation
        $userLocalHandicapIndexes = [];
        foreach ($users as $userId => $user) {
            $roundCount = count($user);

            foreach ($bracket as $config) {
                if ($roundCount >= (int)$config['min'] && $roundCount <= (int)$config['max']) {
                    switch ($config['method']) {
                        case 'LOWEST':
                            usort($user, fn($a, $b) => $a['score_differential'] <=> $b['score_differential']);
                            $selectedScores = array_slice($user, 0, (int)$config['count']);
                            $scoreDiff = $selectedScores[0]['score_differential'];
                            break;
                        case 'HIGHEST':
                            usort($user, fn($a, $b) => $b['score_differential'] <=> $a['score_differential']);
                            $selectedScores = array_slice($user, 0, (int)$config['count']);
                            $scoreDiff = $selectedScores[0]['score_differential'];
                            break;
                        case 'AVERAGE_OF_LOWEST':
                            usort($user, fn($a, $b) => $a['score_differential'] <=> $b['score_differential']);
                            $selectedScores = array_slice($user, 0, (int)$config['count']);
                            $scoreDiff = array_sum(array_column($selectedScores, 'score_differential')) / count($selectedScores);
                            break;
                        default:
                            $selectedScores = [];
                            $scoreDiff = null;
                    }

                    $localHandicapIndex = $scoreDiff + floatval($config['adjustment']);

                    $userLocalHandicapIndexes[$userId] = [
                        'local_handicap_index' => round($localHandicapIndex, 2),
                        'details' => [
                            'scores' => $roundCount,
                            'method' => $config['method'],
                            'adjustment' => floatval($config['adjustment']),
                            'selected_scores' => $selectedScores,
                        ]
                    ];
                }
            }
        }

        echo '<pre>';
        print_r($userLocalHandicapIndexes);
        echo '</pre>';
    }
}
