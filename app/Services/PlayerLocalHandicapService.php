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
    protected $bracket = [];
    protected $maxScorePerUser;
    protected $minScoresPerUser;

    protected $handicapConfig;
    protected PlayerProfile $playerProfile;


    //////////////////////



    public function calculate($playerid)
    {

        $this->playerProfile = PlayerProfile::with('user')->where('player_profile_id', $playerid)
            ->first();


        $config = $this->loadBracketConfiguration();


        $scores = Score::select('score_id', 'user_id', 'score_differential', 'holes_played', 'date_played', 'adjusted_gross_score', 'scores.course_id', 'tees.tee_id', 'slope_rating', 'course_rating', 'courses.course_name', 'tees.tee_name')
            ->leftJoin('courses', 'scores.course_id', '=', 'courses.course_id')
            ->leftJoin('tees', 'scores.tee_id', '=', 'tees.tee_id')
            ->whereBetween('date_played', [$config['score_date']['start'], $config['score_date']['end']])
            ->where('user_id', $this->playerProfile->user_id)
            ->limit($this->maxScorePerUser * 2)
            ->orderBy('date_played', 'desc')->get();



        $handicapService = new LocalHandicapIndexService();
        $scores = $handicapService->calculate($scores, $config);


        return array_merge($scores, [
            'profile' => [
                'name' => $this->playerProfile->userProfile->first_name . ' ' . $this->playerProfile->userProfile->last_name,
                'whs_no' => $this->playerProfile->whs_no,
                'account_no' => $this->playerProfile->account_no,
            ]
        ]);
    }

    private function loadBracketConfiguration(): array
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
                'start' => SystemSettingService::get('local_handicap.calculation_start_date'),
                'end' => SystemSettingService::get('local_handicap.calculation_end_date'),
            ],
        );
    }
}
