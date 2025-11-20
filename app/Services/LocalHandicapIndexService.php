<?php

namespace App\Services;

use App\Exceptions\HandicapCalculationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\In;

class LocalHandicapIndexService
{

    protected $scores = [];
    protected $recentScores = [];
    protected $config = [];
    protected $handicapConfig = [];
    protected $selectedScores = [];

    public function calculate($rawScores, $config)
    {
        $this->config = $config;
        $this->recentScores = $rawScores;
        $this->scores = $this->mergeHalfRounds($rawScores);

        if (count($this->scores) < $this->config['min_scores_per_user']) {
            return $this->handleInsufficientScoresResponse();
        }

        return $this->performHandicapCalculation();
    }

    private function handleInsufficientScoresResponse()
    {
        return [
            'success' => true,
            'local_handicap_index' => null,
            'message' => 'Local handicap calculation requires at least ' . $this->config['min_scores_per_user'] . ' recent score differentials, ' . count($this->scores) . ' were found.',
            'config' => $this->config,
            'details' => [
                'recent_scores'           => $this->scores,
                'selected_scores'         => null,
                'score_differentials'     => null,
                'considered_differentials' => null,
                'method'                  => null,
                'count'                   => null,
                'adjustment'              => null,
            ],
        ];
    }


    private function performHandicapCalculation()
    {
        $wholeRoundsCount = count(array_filter($this->scores, fn($score) => $score['round'] == 1));
        $totalRoundsCount = count($this->scores);
        $mathingBracket = $this->getMatchingBracket($wholeRoundsCount);

        if ($mathingBracket !== null) {
            $fullRoundScores = $this->filterScoresByRound(1);
            $initialHandicap = $this->applyCalculationMethod($fullRoundScores);
            $finalHandicap = $this->recalculateWithConvertedRounds($totalRoundsCount, $initialHandicap);

            return $finalHandicap;
        }

        return null;
    }


    /**
     * Filter scores by round type
     */
    private function filterScoresByRound(float $roundType): array
    {
        return array_values(array_filter($this->scores, fn($score) => $score['round'] == $roundType));
    }


    /**
     * Reapply handicap calculation using the original 18-hole score differentials
     * This handles cases where there are unpaired 9-hole rounds that need adjustment
     */
    private function recalculateWithConvertedRounds($availableRoundsCount, $initialHandicap)
    {

        $halfRoundNoPair = count(array_filter($this->scores, fn($score) => $score['round'] < 1));

        if ($halfRoundNoPair > 0) {

            $scoreWithConvertedhalfRounds = [];

            foreach ($this->scores as $sc) {

                if ($sc['round'] < 1) {
                    $initialHandicapIndex = $initialHandicap['local_handicap_index'];
                    $initialHandicapAdjustment = $initialHandicap['details']['adjustment'];
                    $convertedDifferential = $sc['score_differential'] + $initialHandicapIndex + $initialHandicapAdjustment;

                    $scoreWithConvertedhalfRounds[] = [
                        'score_ids' => [$sc['score_ids']],
                        'original_score_differential' => $sc['score_differential'],
                        'score_differential' => $convertedDifferential,
                        'round' => 1, // converted to full round
                        'adjusted_gross_score' => $sc['adjusted_gross_score'],
                        'holes_played' => 'converted',
                        'course_id' => $sc['course_id'],
                        'tee_id' => $sc['tee_id'],
                        'slope_rating' => (int)$sc['slope_rating'],
                        'course_rating' => (float) $sc['course_rating'],
                    ];
                } else {
                    $scoreWithConvertedhalfRounds[] = $sc;
                }
            }

            $mathingBracket = $this->getMatchingBracket($availableRoundsCount);

            if ($mathingBracket !== null) {
                return $this->applyCalculationMethod($scoreWithConvertedhalfRounds);
            }

            throw new HandicapCalculationException('No matching bracket found after reapplying calculation method with converted half rounds.');
        }

        return $initialHandicap;
    }



    /**
     * Apply the configured calculation method (LOWEST, HIGHEST, AVERAGE_OF_LOWEST)
     */
    private function applyCalculationMethod($scores)
    {
        $count = (int)$this->handicapConfig['count'];
        $method = $this->handicapConfig['method'];
        $adjustment = (float)$this->handicapConfig['adjustment'];

        $sorted = $scores;
        if ($method === 'HIGHEST') {
            usort($sorted, fn($a, $b) => $b['score_differential'] <=> $a['score_differential']);
        } else {
            usort($sorted, fn($a, $b) => $a['score_differential'] <=> $b['score_differential']);
        }

        $selectedScores = array_slice($sorted, 0, $count);

        // Calculate score differential based on method
        $scoreDiff = match ($method) {
            'LOWEST' => $selectedScores[0]['score_differential'] ?? 0,
            'HIGHEST' => $selectedScores[0]['score_differential'] ?? 0,
            'AVERAGE_OF_LOWEST' => count($selectedScores) > 0
                ? array_sum(array_column($selectedScores, 'score_differential')) / count($selectedScores)
                : 0,
            default => null,
        };


        $consideredDifferentials =  match ($method) {
            'LOWEST' => [array_first($selectedScores)],
            'HIGHEST' => [array_first($selectedScores)],
            'AVERAGE_OF_LOWEST' => $selectedScores,
            default => null,
        };

        if ($scoreDiff === null or $consideredDifferentials === null) {
            throw new HandicapCalculationException('Unable to calculate handicap. Invalid calculation method or insufficient scores.');
        }

        $handicapIndex = round($scoreDiff + $adjustment, 2);

        return [
            'success' => true,
            'local_handicap_index' => $handicapIndex,
            'message' => 'Local handicap index calculated successfully.',
            'config' => $this->config,
            'details' => [
                'recent_scores'           => $this->recentScores,
                'selected_scores'         => $this->selectedScores,
                'score_differentials'     => $sorted,
                'considered_differentials' => $consideredDifferentials,
                'method'                  => $method,
                'count'                   => $count,
                'adjustment'              => $adjustment,
            ],
        ];
    }


    private function getMatchingBracket($wholeRound)
    {
        foreach ($this->config['bracket'] as $config) {

            $minRoundCount = min($wholeRound, (int)$config['max']);

            if ($wholeRound >= (int)$config['min'] && $minRoundCount <= (int)$config['max']) {

                $this->handicapConfig = $config;
                return $config;
            }
        }

        return null;
    }









    /**
     * Merge half rounds into full rounds where possible
     */
    public function mergeHalfRounds($scores): array
    {

        $mergedScores = [];
        $unpairedHalfRounds = [];
        $maxScores = $this->config['max_scores_per_user'];

        $scoresArray = $scores->slice(0, $maxScores); // Limit to max scores per user
        $this->selectedScores = $scoresArray;

        foreach ($scoresArray as $score) {

            $holesPlayed = $score->holes_played;

            if ($holesPlayed === 'F9' || $holesPlayed === 'B9') {

                Log::debug('Attempting to pair half round', [
                    'score_id' => $score->score_id,
                    'course_id' => $score->course_id,
                    'tee_id' => $score->tee_id,
                    'holes_played' => $holesPlayed,
                    'date_played' => $score->date_played
                ]);

                $wasPaired = $this->tryPairHalfRound($score, $mergedScores, $unpairedHalfRounds);

                if (!$wasPaired) {

                    $unpairedHalfRounds[$score->course_id][$score->tee_id][$holesPlayed][] = [
                        'score_ids' => [$score->score_id],
                        'original_score_differential' => (float)$score->score_differential,
                        'score_differential' => (float)$score->score_differential,
                        'round' => 0.5,
                        'adjusted_gross_score' => $score->adjusted_gross_score,
                        'holes_played' => $score->holes_played,
                        'course_id' => $score->course_id,
                        'tee_id' => $score->tee_id,
                        'slope_rating' => (int)$score->slope_rating,
                        'course_rating' => (float)$score->course_rating
                    ];
                }

                continue;
            }



            $mergedScores[] = [
                'score_ids' => [$score->score_id],
                'original_score_differential' => (float)$score->score_differential,
                'score_differential' => (float)$score->score_differential,
                'round' => ($score->holes_played === 'F9' || $score->holes_played === 'B9') ? 0.5 : 1,
                'adjusted_gross_score' => $score->adjusted_gross_score,
                'holes_played' => $score->holes_played,
                'course_id' => $score->course_id,
                'tee_id' => $score->tee_id,
                'slope_rating' => (int)$score->slope_rating,
                'course_rating' => (float)$score->course_rating
            ];
        }



        $this->addUnpairedHalfRounds($mergedScores, $unpairedHalfRounds);

        return $mergedScores;
    }

    private function addUnpairedHalfRounds(&$mergedScores, $unpairedHalfRounds)
    {
        foreach ($unpairedHalfRounds as $courses) {
            foreach ($courses as $tees) {
                foreach ($tees as $unpairedHalfRound) {
                    foreach ($unpairedHalfRound as $halfRoundScore) {;
                        $mergedScores[] = [
                            'score_ids' => $halfRoundScore['score_ids'],
                            'score_differential' => (float)$halfRoundScore['score_differential'],
                            'round' => 0.5,
                            'adjusted_gross_score' => $halfRoundScore['adjusted_gross_score'],
                            'holes_played' => $halfRoundScore['holes_played'],
                            'course_id' => $halfRoundScore['course_id'],
                            'tee_id' => $halfRoundScore['tee_id'],
                            'slope_rating' => (int)$halfRoundScore['slope_rating'],
                            'course_rating' => (float)$halfRoundScore['course_rating']
                        ];
                    }
                }
            }
        }
    }

    private function tryPairHalfRound($score, &$mergedScores, &$unpairedHalfRounds): bool
    {
        $oppositeHole = $score->holes_played === 'F9' ? 'B9' : 'F9';

        if (!isset($unpairedHalfRounds[$score->course_id][$score->tee_id][$oppositeHole][0])) {

            return false;
        }


        $mathingcScore = $unpairedHalfRounds[$score->course_id][$score->tee_id][$oppositeHole][0];

        $mergedScores[] = [
            'score_ids' => array_merge($mathingcScore['score_ids'], [$score->score_id]),
            'original_score_differential' => null,
            'score_differential' => (float)$score->score_differential + $mathingcScore['score_differential'],
            'round' => 1, // combined full round
            'adjusted_gross_score' =>  $score->adjusted_gross_score + $mathingcScore['adjusted_gross_score'],
            'holes_played' => 'combined',
            'course_id' => $score->course_id,
            'tee_id' => $score->tee_id,
            'slope_rating' => (int)$score->slope_rating + (int)$mathingcScore['slope_rating'],
            'course_rating' => (float)$score->course_rating + (float)$mathingcScore['course_rating'],

        ];

        unset($unpairedHalfRounds[$score->course_id][$score->tee_id][$oppositeHole][0]);


        $unpairedHalfRounds[$score->course_id][$score->tee_id][$oppositeHole] = array_values($unpairedHalfRounds[$score->course_id][$score->tee_id][$oppositeHole]); //reset array keys

        return true;
    }
}
