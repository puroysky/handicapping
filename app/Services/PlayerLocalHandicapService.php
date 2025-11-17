<?php

namespace App\Services;

use App\Exceptions\HandicapCalculationException;
use App\Models\Participant;
use App\Models\PlayerProfile;
use App\Models\Score;
use App\Models\Tournament;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlayerLocalHandicapService
{
    private $bracket = [];
    private $nullHandicapCount = 0;
    private const CHUNK_SIZE = 100;
    private $maxScorePerUser = 40;
    private $minScoresPerUser = 6;
    private PlayerProfile $playerProfile;
    private $scores = [];
    private $handicapConfig = [];



    /**
     * Calculate local handicap index for all participants in a tournament
     */
    public function calculate($userId = null)
    {

        $this->playerProfile = PlayerProfile::with('user')->where('user_id', $userId)
            ->first();

        try {


            $this->loadBracketConfiguration();
            $scores = $this->fetchLatestScoresPerUser();



            $player = [

                'name' => $this->playerProfile->user->profile->first_name . ' ' . $this->playerProfile->user->profile->last_name,
                'whs_no' => $this->playerProfile->whs_no,
                'account_no' => $this->playerProfile->account_no,
            ];

            if (count($this->scores) < $this->minScoresPerUser) {
                return [
                    'success' => true,
                    'message' => 'Local handicap calculation requires at least ' . $this->minScoresPerUser . ' recent score differentials, ' . count($this->scores) . ' were found.',
                    'handicaps' => null,
                    'config' => $this->handicapConfig,
                    'recent_scores' => $this->scores,
                    'score_date' =>  [
                        'start' => SystemSettingService::get('local_handicap.calculation_start_date'),
                        'end' => SystemSettingService::get('local_handicap.calculation_end_date'),
                    ],
                    'player' => $player
                ];
            }

            $handicaps = $this->calculateHandicapsForUsers($scores);

            return [
                'success' => true,
                'handicaps' => $handicaps,
                'config' => $this->handicapConfig,
                'recent_scores' => $this->scores,
                'score_date' =>  [
                    'start' => SystemSettingService::get('local_handicap.calculation_start_date'),
                    'end' => SystemSettingService::get('local_handicap.calculation_end_date'),
                ],
                'player' => $player
            ];
        } catch (HandicapCalculationException $e) {



            Log::error('Local handicap calculation failed', [

                'error' => $e->getUserMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'success' => false,
                'error' => $e->getUserMessage()
            ], 400);
        }
    }

    /**
     * Load bracket configuration from tournament
     */
    private function loadBracketConfiguration(): void
    {


        $handicapIndexCalTable = SystemSettingService::get('local_handicap.calculation_table');

        Log::debug('Loaded handicap index calculation table', [
            'table' => $handicapIndexCalTable
        ]);

        if (empty($handicapIndexCalTable)) {
            throw new HandicapCalculationException(
                'Unable to calculate handicap. Please try again.',
                'Tournament handicap calculation table not found',
                ['player_profile' => $this->playerProfile->toArray()]
            );
        }

        $this->bracket = collect($handicapIndexCalTable)
            ->sortByDesc('max')
            ->values()
            ->toArray();

        $this->maxScorePerUser = max(array_column($this->bracket, 'max')) * 2;
        $this->minScoresPerUser = min(array_column($this->bracket, 'min'));

        if (empty($this->bracket)) {
            throw new HandicapCalculationException('Handicap calculation table is empty');
        }
    }

    /**
     * Fetch latest 20 scores per user using optimized SQL window function
     */
    private function fetchLatestScoresPerUser()
    {

        $scores = Score::select('score_id', 'user_id', 'score_differential', 'holes_played', 'date_played', 'adjusted_gross_score')->where('user_id', $this->playerProfile->user_id)->limit($this->maxScorePerUser)->orderBy('date_played', 'desc')->get();

        $this->scores = $scores;
        return $this->groupScoresByUser($scores);
    }


    /**
     * Group scores by user ID
     */
    private function groupScoresByUser($scores): array
    {
        $grouped = [];

        foreach ($scores as $score) {
            $grouped[$score->user_id][] = [
                'score_id' => $score->score_id,
                'score_differential' => (float)$score->score_differential,
                'round' => ($score->holes_played === 'F9' || $score->holes_played === 'B9') ? 0.5 : 1,
            ];
        }

        return $grouped;
    }

    /**
     * Calculate handicap indices for all users
     */
    private function calculateHandicapsForUsers($userScores): array
    {;
        $scores = array_first($userScores);


        $handicap = $this->calculateUserHandicap($scores);

        return $handicap;
    }

    /**
     * Calculate handicap for a single user
     */
    private function calculateUserHandicap($scores)
    {

        $userId = $this->playerProfile->user_id;

        $roundCount = floor(array_sum(array_column($scores, 'round')));

        // Find matching bracket based on round count
        foreach ($this->bracket as $config) {

            $minRoundCount = min($roundCount, (int)$config['max']);

            if ($roundCount >= (int)$config['min'] && $minRoundCount <= (int)$config['max']) {

                $this->handicapConfig = $config;
                return $this->applyCalculationMethod($userId, $scores, $config, $roundCount);
            }
        }

        Log::debug("No bracket match for user {$userId} with {$roundCount} rounds");
        return null;
    }

    /**
     * Apply the configured calculation method (LOWEST, HIGHEST, AVERAGE_OF_LOWEST)
     */
    private function applyCalculationMethod($userId, $scores, $config, $roundCount)
    {
        $count = (int)$config['count'];
        $method = $config['method'];
        $adjustment = (float)$config['adjustment'];

        // Sort based on method
        $sorted = $scores;
        if ($method === 'HIGHEST') {
            usort($sorted, fn($a, $b) => $b['score_differential'] <=> $a['score_differential']);
        } else {
            usort($sorted, fn($a, $b) => $a['score_differential'] <=> $b['score_differential']);
        }

        $selected = array_slice($sorted, 0, $count);

        // Calculate score differential based on method
        $scoreDiff = match ($method) {
            'LOWEST' => $selected[0]['score_differential'] ?? 0,
            'HIGHEST' => $selected[0]['score_differential'] ?? 0,
            'AVERAGE_OF_LOWEST' => count($selected) > 0
                ? array_sum(array_column($selected, 'score_differential')) / count($selected)
                : 0,
            default => 0,
        };

        $handicapIndex = round($scoreDiff + $adjustment, 2);

        Log::debug("Calculated handicap for user {$userId}", [
            'recent_scores' => $roundCount,
            'method' => $method,
            'handicap_index' => $handicapIndex,
            'selected_count' => count($selected)
        ]);

        return [
            'local_handicap_index' => $handicapIndex,
            'details' => [
                'recent_scores' => $roundCount,
                'used_scores' => $count,
                'method' => $method,
                'adjustment' => $adjustment,
            ]
        ];
    }
}
