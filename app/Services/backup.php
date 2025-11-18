<?php

function groupScoresByUser($scores): array
{
    $grouped = [];
    $halfRounds = [];

    foreach ($scores as $score) {
        $userId = $score->user_id;
        $courseId = $score->course_id;
        $holes = $score->holes_played;
        $diff = (float)$score->score_differential;

        // Handle 9-hole rounds
        if ($holes === 'F9' || $holes === 'B9') {
            $opposite = $holes === 'F9' ? 'B9' : 'F9';

            // Check if opposite 9-hole already exists to combine
            if (isset($halfRounds[$courseId][$opposite]) && !empty($halfRounds[$courseId][$opposite])) {
                $paired = array_shift($halfRounds[$courseId][$opposite]);

                $grouped[$userId][] = [
                    'score_id' => 'combined_' . $paired['score_id'] . '_' . $score->score_id,
                    'score_differential' => $diff + (float)$paired['score_differential'],
                    'round' => 1,
                ];

                // Clean up if array is empty
                if (empty($halfRounds[$courseId][$opposite])) {
                    unset($halfRounds[$courseId][$opposite]);
                }

                continue;
            }

            // Store current 9-hole round for potential future pairing
            $halfRounds[$courseId][$holes][] = $score->toArray();
            continue;
        }

        // Full 18-hole rounds
        $grouped[$userId][] = [
            'score_id' => $score->score_id,
            'score_differential' => $diff,
            'round' => 1,
        ];
    }

    // Add remaining unpaired half rounds
    foreach ($halfRounds as $courses) {
        foreach ($courses as $scoresArray) {
            foreach ($scoresArray as $sc) {
                $grouped[$sc['user_id'] ?? $this->playerProfile->user_id][] = [
                    'score_id' => $sc['score_id'],
                    'score_differential' => (float)$sc['score_differential'],
                    'round' => 0.5,
                ];
            }
        }
    }

    return $grouped;
}
