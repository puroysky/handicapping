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

class LocalHandicapIndexCalculationService
{

    private $tournamentId;
    private $nullHandicapCount = 0;
    private const CHUNK_SIZE = 100;
    protected $bracket = [];
    protected $maxScorePerUser;
    protected $minScoresPerUser;

    protected $handicapConfig;
    protected PlayerProfile $playerProfile;


    /**
     * Calculate local handicap index for all participants in a tournament
     */
    public function calculate($tournamentId = null)
    {
        $this->tournamentId = $tournamentId ?? request()->tournament_id;

        try {

            $config = $this->loadBracketConfiguration();
            $scores = $this->fetchLatestScoresPerUser($config);
            $scoresGroupedByUser = $this->groupScoresByUser($scores);
            $handicaps = $this->calculateHandicapsForUsers($scoresGroupedByUser, $config);
            $updated = $this->updateParticipantHandicaps($handicaps);

            return [
                'success' => true,
                'updated_count' => $updated,
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
        } catch (Exception $e) {

            Log::error('Unexpected error during local handicap calculation', [
                'tournament_id' => $this->tournamentId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'An unexpected error occurred during handicap calculation. Please try again later.'
            ], 500);
        }
    }

    /**
     * Load bracket configuration from tournament
     */
    private function loadBracketConfiguration(): array
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

        $this->maxScorePerUser = max(array_column($this->bracket, 'max'));
        $this->minScoresPerUser = min(array_column($this->bracket, 'min'));

        if (empty($this->bracket)) {
            throw new HandicapCalculationException('Handicap calculation table is empty');
        }

        return array(
            'bracket' => $this->bracket,
            'min_scores_per_user' => $this->minScoresPerUser,
            'max_scores_per_user' => $this->maxScorePerUser,
            'score_date' =>  [
                'start' => $tournament->score_diff_start_date,
                'end' => $tournament->score_diff_end_date,
            ],
        );
    }

    /**
     * Fetch latest scores per user within date range
     */
    private function fetchLatestScoresPerUser($config)
    {
        $subquery = DB::table('scores')
            ->leftJoin('courses', 'scores.course_id', '=', 'courses.course_id')
            ->leftJoin('tees', 'scores.tee_id', '=', 'tees.tee_id')
            ->select('score_id', 'user_id', 'score_differential', 'holes_played', 'date_played', 'adjusted_gross_score', 'scores.course_id', 'tees.tee_id', 'slope_rating', 'course_rating', 'courses.course_name', 'tees.tee_name')
            ->whereBetween('date_played', [
                $config['score_date']['start'],
                $config['score_date']['end']
            ])
            ->selectRaw('ROW_NUMBER() OVER (PARTITION BY user_id ORDER BY date_played DESC) as rn')
            ->whereIn('user_id', $this->getParticipantUserIds());

        $scores = DB::table(DB::raw("({$subquery->toSql()}) as ranked_scores"))
            ->mergeBindings($subquery)
            ->where('rn', '<=', $this->maxScorePerUser)
            ->orderBy('user_id')
            ->orderBy('date_played')
            ->get();

        return $scores;
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
        $scoresGroupByUser = [];

        foreach ($scores as $score) {
            $scoresGroupByUser[$score->user_id][] = (array) $score;
        }

        return $scoresGroupByUser;
    }

    /**
     * Calculate handicap indices for all users
     */
    private function calculateHandicapsForUsers($userScores, $config): array
    {
        $handicaps = [];

        $localHandicapIndexService = new LocalHandicapIndexService();



        foreach ($userScores as $userId => $scores) {
            $handicap = $localHandicapIndexService->calculate($scores, $config);
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
     * Update participant handicaps in database (bulk update)
     */
    private function updateParticipantHandicaps($handicaps): int
    {
        if (empty($handicaps)) {
            return 0;
        }

        $updated = 0;

        $now = now();
        $userId = Auth::id();

        $participants = Participant::where('tournament_id', $this->tournamentId)->get();

        DB::beginTransaction();
        collect($participants)
            ->chunk(self::CHUNK_SIZE)
            ->each(function ($chunk) use (&$updated, &$pending, $now, $handicaps, $userId) {
                foreach ($chunk as $participant) {
                    $pUserId = $participant->user_id;
                    $localHandicapIndex = $handicaps[$pUserId]['local_handicap_index'] ?? null;

                    $updated += Participant::where('tournament_id', $this->tournamentId)
                        ->where('user_id', $pUserId)
                        ->update([
                            'local_handicap_index' => $localHandicapIndex,
                            'final_local_handicap_index' => $localHandicapIndex,
                            'tournament_handicap_index' => null,
                            'final_tournament_handicap_index' => null,
                            'updated_by' => $userId,
                            'updated_at' => $now,
                        ]);
                }
            });

        DB::commit();

        return $updated;
    }
}
