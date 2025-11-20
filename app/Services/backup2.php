<?php

namespace App\Services;

use App\Exceptions\HandicapCalculationException;
use Illuminate\Support\Facades\Log;

class LocalHandicapIndexService
{
    protected array $scores = [];
    protected $recentScores;
    protected array $config = [];
    protected array $handicapConfig = [];
    protected $selectedScores;

    /**
     * Calculate local handicap index from raw scores
     * 
     * @param mixed $rawScores Collection of score objects
     * @param array $config Configuration with brackets and constraints
     * @return array Calculation result with handicap index and details
     * @throws HandicapCalculationException
     */
    public function calculate($rawScores, $config)
    {
        $this->config = $config;
        $this->recentScores = $rawScores;
        $this->scores = $this->mergeHalfRounds($rawScores);

        if (count($this->scores) < $this->config['min_scores_per_user']) {
            return $this->buildInsufficientScoresResponse();
        }

        return $this->calculateUserHandicap();
    }

    /**
     * Calculate handicap index for user
     * Main orchestration method that handles full round calculation and half round conversion
     */
    private function calculateUserHandicap(): array
    {
        try {
            return $this->performHandicapCalculation();
        } catch (HandicapCalculationException $e) {
            Log::warning('Handicap calculation error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Build response when insufficient scores are available
     */
    private function buildInsufficientScoresResponse(): array
    {
        return [
            'success' => true,
            'local_handicap_index' => null,
            'message' => sprintf(
                'Local handicap calculation requires at least %d recent score differentials, %d were found.',
                $this->config['min_scores_per_user'],
                count($this->scores)
            ),
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

    /**
     * Calculate handicap for the user
     */
    private function performHandicapCalculation(): array
    {
        $wholeRoundsCount = $this->countRounds(1);
        $totalRoundsCount = count($this->scores);

        $matchingBracket = $this->getMatchingBracket($wholeRoundsCount);

        if ($matchingBracket === null) {
            throw new HandicapCalculationException('No matching bracket found for ' . $wholeRoundsCount . ' rounds.');
        }

        $fullRoundScores = $this->filterScoresByRound(1);
        $calculatedHandicap = $this->performCalculation($fullRoundScores);

        return $this->recalculateWithConvertedHalves($totalRoundsCount, $calculatedHandicap);
    }

    /**
     * Count scores by round type (1 = full, 0.5 = half, etc.)
     */
    private function countRounds(float $roundType): int
    {
        return count(array_filter($this->scores, fn($score) => $score['round'] == $roundType));
    }

    /**
     * Filter scores by round type
     */
    private function filterScoresByRound(float $roundType): array
    {
        return array_values(array_filter($this->scores, fn($score) => $score['round'] == $roundType));
    }

    /**
     * Recalculate handicap after converting unpaired half rounds
     */
    private function recalculateWithConvertedHalves(int $totalRoundsCount, array $calculatedHandicap): array
    {
        $halfRoundCount = $this->countRounds(0.5);

        if ($halfRoundCount === 0) {
            return $calculatedHandicap;
        }

        $scoresWithConvertedHalves = $this->convertHalfRounds($calculatedHandicap);
        $matchingBracket = $this->getMatchingBracket($totalRoundsCount);

        if ($matchingBracket === null) {
            throw new HandicapCalculationException('No matching bracket found after recalculating with converted half rounds.');
        }

        return $this->performCalculation($scoresWithConvertedHalves);
    }

    /**
     * Convert unpaired half rounds to full rounds using calculated handicap
     */
    private function convertHalfRounds(array $calculatedHandicap): array
    {
        $handicapIndex = $calculatedHandicap['local_handicap_index'];
        $adjustmentFactor = $calculatedHandicap['details']['adjustment'];

        $convertedScores = [];
        foreach ($this->scores as $score) {
            if ($score['round'] < 1) {
                $convertedScores[] = [
                    'score_ids' => [$score['score_id'] ?? null],
                    'original_score_differential' => $score['score_differential'],
                    'score_differential' => $score['score_differential'] + $handicapIndex + $adjustmentFactor,
                    'round' => 1,
                    'adjusted_gross_score' => $score['adjusted_gross_score'],
                    'holes_played' => 'converted',
                    'course_id' => $score['course_id'],
                    'tee_id' => $score['tee_id'] ?? null,
                ];
            } else {
                $convertedScores[] = $score;
            }
        }
        return $convertedScores;
    }

    /**
     * Perform calculation method (LOWEST, HIGHEST, AVERAGE_OF_LOWEST)
     */
    private function performCalculation(array $scores): array
    {
        if (empty($scores)) {
            throw new HandicapCalculationException('No scores available for calculation.');
        }

        $requiredScoreCount = (int)$this->handicapConfig['count'];
        $calculationMethod = $this->handicapConfig['method'];
        $adjustmentFactor = (float)$this->handicapConfig['adjustment'];

        $sortedScores = $this->sortScoresByDifferential($scores, $calculationMethod);
        $selectedScores = array_slice($sortedScores, 0, $requiredScoreCount);

        $calculatedDifferential = $this->calculateScoreDifferential($calculationMethod, $selectedScores);
        $consideredDifferentials = $this->getConsideredDifferentials($calculationMethod, $selectedScores);

        if ($calculatedDifferential === null || $consideredDifferentials === null) {
            throw new HandicapCalculationException('Unable to calculate handicap. Invalid method or insufficient scores.');
        }

        $handicapIndex = round($calculatedDifferential + $adjustmentFactor, 2);

        return [
            'success' => true,
            'local_handicap_index' => $handicapIndex,
            'message' => 'Local handicap index calculated successfully.',
            'config' => $this->config,
            'details' => [
                'recent_scores'            => $this->recentScores->toArray(),
                'selected_scores'          => $this->selectedScores->toArray(),
                'score_differentials'      => $sortedScores,
                'considered_differentials' => $consideredDifferentials,
                'method'                   => $calculationMethod,
                'count'                    => $requiredScoreCount,
                'adjustment'               => $adjustmentFactor,
            ],
        ];
    }

    /**
     * Sort scores by differential in ascending or descending order
     */
    private function sortScoresByDifferential(array $scores, string $sortMethod): array
    {
        $sortedScores = $scores;
        usort($sortedScores, function ($scoreA, $scoreB) use ($sortMethod) {
            $differentialComparison = $scoreA['score_differential'] <=> $scoreB['score_differential'];
            return $sortMethod === 'HIGHEST' ? -$differentialComparison : $differentialComparison;
        });
        return $sortedScores;
    }

    /**
     * Calculate score differential based on method
     */
    private function calculateScoreDifferential(string $calculationMethod, array $selectedScores): ?float
    {
        if (empty($selectedScores)) {
            return 0;
        }

        return match ($calculationMethod) {
            'LOWEST', 'HIGHEST' => $selectedScores[0]['score_differential'] ?? 0,
            'AVERAGE_OF_LOWEST' => array_sum(array_column($selectedScores, 'score_differential')) / count($selectedScores),
            default => null,
        };
    }

    /**
     * Get considered differentials based on method
     */
    private function getConsideredDifferentials(string $calculationMethod, array $selectedScores): ?array
    {
        return match ($calculationMethod) {
            'LOWEST', 'HIGHEST' => [$selectedScores[0]],
            'AVERAGE_OF_LOWEST' => $selectedScores,
            default => null,
        };
    }

    /**
     * Get matching bracket from config based on round count
     */
    private function getMatchingBracket(int $roundCount): ?array
    {
        foreach ($this->config['bracket'] as $bracketConfig) {
            $minRounds = (int)$bracketConfig['min'];
            $maxRounds = (int)$bracketConfig['max'];

            if ($roundCount >= $minRounds && $roundCount <= $maxRounds) {
                $this->handicapConfig = $bracketConfig;
                return $bracketConfig;
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
        $maxScores = (int)$this->config['max_scores_per_user'];

        // Slice and convert to array for iteration
        $scoreArray = $scores->slice(0, $maxScores)->toArray();
        $this->selectedScores = $scores->slice(0, $maxScores);

        foreach ($scoreArray as $score) {
            $score = (array)$score;
            $holesPlayed = $score['holes_played'];

            // Handle half rounds (F9 or B9)
            if ($holesPlayed === 'F9' || $holesPlayed === 'B9') {
                $wasPaired = $this->tryPairHalfRound($score, $unpairedHalfRounds, $mergedScores);
                if (!$wasPaired) {
                    // Store unpaired half round
                    $unpairedHalfRounds[$score['course_id']][$score['tee_id']][$holesPlayed][] = [
                        'score_ids' => [$score['score_id']],
                        'original_score_differential' => (float)$score['score_differential'],
                        'score_differential' => (float)$score['score_differential'],
                        'round' => 0.5,
                        'adjusted_gross_score' => $score['adjusted_gross_score'],
                        'holes_played' => $score['holes_played'],
                        'course_id' => $score['course_id'],
                        'tee_id' => $score['tee_id'],
                        'course_rating' => $score['course_rating'],
                        'slope_rating' => $score['slope_rating'],
                    ];
                }
                continue;
            }

            // Handle full rounds (18 holes)
            $mergedScores[] = [
                'score_ids' => [$score['score_id']],
                'original_score_differential' => (float)$score['score_differential'],
                'score_differential' => (float)$score['score_differential'],
                'round' => 1,
                'adjusted_gross_score' => $score['adjusted_gross_score'],
                'holes_played' => $holesPlayed,
                'course_id' => $score['course_id'],
                'tee_id' => $score['tee_id'],
                'slope_rating' => $score['slope_rating'],
                'course_rating' => $score['course_rating']
            ];
        }

        // Add unpaired half rounds
        $this->addUnpairedHalfRounds($unpairedHalfRounds, $mergedScores);

        return $mergedScores;
    }

    /**
     * Try to pair a half round with its opposite half
     */
    private function tryPairHalfRound(array $score, array &$unpairedHalfRounds, array &$mergedScores): bool
    {
        $courseId = $score['course_id'];
        $oppositeHalf = $score['holes_played'] === 'F9' ? 'B9' : 'F9';
        $teeId = $score['tee_id'];

        if (
            !isset($unpairedHalfRounds[$courseId][$teeId][$oppositeHalf]) ||
            empty($unpairedHalfRounds[$courseId][$teeId][$oppositeHalf])
        ) {
            return false;
        }

        $matchingScore = array_shift($unpairedHalfRounds[$courseId][$teeId][$oppositeHalf]);

        $mergedScores[] = [
            'score_ids' => [$matchingScore['score_id'], $score['score_id']],
            'original_score_differential' => null,
            'score_differential' => (float)$matchingScore['score_differential'] + (float)$score['score_differential'],
            'round' => 1,
            'adjusted_gross_score' => $matchingScore['adjusted_gross_score'] + $score['adjusted_gross_score'],
            'holes_played' => 'combined',
            'course_id' => $courseId,
            'tee_id' => $teeId,
            'course_rating' => $matchingScore['course_rating'],
            'slope_rating' => $matchingScore['slope_rating'],
        ];

        return true;
    }

    /**
     * Add unpaired half rounds to merged scores
     */
    private function addUnpairedHalfRounds(array $unpairedHalfRounds, array &$mergedScores): void
    {
        foreach ($unpairedHalfRounds as $courseScores) {
            foreach ($courseScores as $halfRoundScores) {
                foreach ($halfRoundScores as $halfRoundScoreArr) {

                    $halfRoundScore = array_first($halfRoundScoreArr);

                    $mergedScores[] = [
                        'score_ids' => $halfRoundScore['score_ids'],
                        'original_score_differential' => (float)$halfRoundScore['score_differential'],
                        'score_differential' => (float)$halfRoundScore['score_differential'],
                        'round' => 0.5,
                        'adjusted_gross_score' => $halfRoundScore['adjusted_gross_score'],
                        'holes_played' => $halfRoundScore['holes_played'],
                        'course_id' => $halfRoundScore['course_id'],
                        'tee_id' => $halfRoundScore['tee_id'],
                        'course_rating' => $halfRoundScore['course_rating'],
                        'slope_rating' => $halfRoundScore['slope_rating'],
                    ];
                }
            }
        }
    }
}
