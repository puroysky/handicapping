<?php

namespace App\Services;

use App\Exceptions\HandicapCalculationException;
use App\Models\Participant;
use App\Models\Score;
use App\Models\Tournament;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LocalHandicapIndexCalculationService
{
    private $bracket = [];
    private $tournamentId;
    private $nullHandicapCount = 0;
    private const CHUNK_SIZE = 100;
    private const MAX_SCORES_PER_USER = 40;

    /**
     * Calculate local handicap index for all participants in a tournament
     */
    public function calculate($tournamentId = null)
    {
        $this->tournamentId = $tournamentId ?? request()->tournament_id;

        try {
            Log::info('Starting local handicap calculation', ['tournament_id' => $this->tournamentId]);

            $this->loadBracketConfiguration();
            $scores = $this->fetchLatestScoresPerUser();
            $handicaps = $this->calculateHandicapsForUsers($scores);
            $updated = $this->updateParticipantHandicaps($handicaps);

            Log::info('Local handicap calculation completed', [
                'tournament_id' => $this->tournamentId,
                'participants_updated' => $updated,
                'total_handicaps' => count($handicaps),
                'null_handicap_count' => $this->nullHandicapCount
            ]);

            return [
                'success' => true,
                'updated' => $updated,
                'handicaps' => $handicaps,
                'null_handicap_count' => $this->nullHandicapCount,
                'pending' => $this->nullHandicapCount
            ];
        } catch (HandicapCalculationException $e) {



            Log::error('Local handicap calculation failed', [
                'tournament_id' => $this->tournamentId,
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
        $tournament = Tournament::find($this->tournamentId);

        if ($tournament === null) {
            throw new HandicapCalculationException(
                'Tournament not found. Please verify the tournament exists and try again.',
                'Tournament not found',
                ['tournament_id' => $this->tournamentId]
            );
        }

        if ($tournament->status !== 'active') {
            throw new HandicapCalculationException(
                'Handicap calculation is only available for active tournaments. Please activate the tournament before calculating handicaps.',
                'Tournament not active',
                ['tournament_id' => $this->tournamentId]
            );
        }

        if (!$tournament || !$tournament->tournament_handicap_calculation_table) {
            throw new HandicapCalculationException(
                'Unable to calculate handicap. Please try again.',
                'Tournament handicap calculation table not found',
                ['tournament_id' => $this->tournamentId]
            );
        }

        $this->bracket = collect(json_decode($tournament->tournament_handicap_calculation_table, true))
            ->sortByDesc('max')
            ->values()
            ->toArray();

        if (empty($this->bracket)) {
            throw new HandicapCalculationException('Handicap calculation table is empty');
        }
    }

    /**
     * Fetch latest 20 scores per user using optimized SQL window function
     */
    private function fetchLatestScoresPerUser()
    {
        $subquery = DB::table('scores')
            ->select('score_id', 'user_id', 'score_differential', 'holes_played', 'date_played')
            ->selectRaw('ROW_NUMBER() OVER (PARTITION BY user_id ORDER BY date_played DESC) as rn');
        // ->whereIn('user_id', $this->getParticipantUserIds());

        $scores = DB::table(DB::raw("({$subquery->toSql()}) as ranked_scores"))
            ->mergeBindings($subquery)
            ->where('rn', '<=', self::MAX_SCORES_PER_USER)
            ->orderBy('user_id')
            ->orderBy('date_played')
            ->get();

        echo '<pre>';
        print_r($scores->toArray());
        echo '</pre>';

        return $this->groupScoresByUser($scores);
    }

    /**
     * Get participant user IDs for this tournament
     */
    private function getParticipantUserIds()
    {
        return Participant::where('tournament_id', $this->tournamentId)
            ->pluck('user_id')
            ->toArray();
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
    {
        $handicaps = [];

        foreach ($userScores as $userId => $scores) {
            $handicap = $this->calculateUserHandicap($userId, $scores);
            if ($handicap) {
                $handicaps[$userId] = $handicap;
            } else {
                $this->nullHandicapCount++;
            }
        }

        if ($this->nullHandicapCount > 0) {
            Log::warning("Unable to calculate handicap for {$this->nullHandicapCount} participants", [
                'tournament_id' => $this->tournamentId,
                'null_count' => $this->nullHandicapCount,
                'total_participants' => count($userScores)
            ]);
        }

        return $handicaps;
    }

    /**
     * Calculate handicap for a single user
     */
    private function calculateUserHandicap($userId, $scores)
    {
        $roundCount = count($scores);

        // Find matching bracket based on round count
        foreach ($this->bracket as $config) {
            if ($roundCount >= (int)$config['min'] && $roundCount <= (int)$config['max']) {
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
            'rounds' => $roundCount,
            'method' => $method,
            'handicap_index' => $handicapIndex,
            'selected_count' => count($selected)
        ]);

        return [
            'local_handicap_index' => $handicapIndex,
            'details' => [
                'scores_considered' => $roundCount,
                'method' => $method,
                'count_used' => $count,
                'adjustment' => $adjustment,
            ]
        ];
    }

    /**
     * Update participant handicaps in database (bulk update)
     */
    private function updateParticipantHandicaps($handicaps): int
    {
        if (empty($handicaps)) {
            Log::warning('No handicaps to update');
            return 0;
        }

        $updated = 0;

        $now = now();

        // Batch updates in chunks to avoid memory issues
        collect($handicaps)
            ->chunk(self::CHUNK_SIZE)
            ->each(function ($chunk) use (&$updated, $now) {
                foreach ($chunk as $userId => $handicap) {
                    $updated += Participant::where('tournament_id', $this->tournamentId)
                        ->where('user_id', $userId)
                        ->update([
                            'local_handicap_index' => $handicap['local_handicap_index'],
                            'final_local_handicap_index' => $handicap['local_handicap_index'],
                            'tournament_handicap_index' => null,
                            'final_tournament_handicap_index' => null,
                            'updated_by' => $now
                        ]);
                }
            });

        return $updated;
    }
}
