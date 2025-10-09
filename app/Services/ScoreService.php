<?php

namespace App\Services;

class ScoreService
{
    // How many strokes to add when input is 'x'
    private int $xStrokePenalty;

    public function __construct(int $xStrokePenalty = 2)
    {
        $this->xStrokePenalty = $xStrokePenalty;
    }


    public function index()
    {

        $scores = \App\Models\Score::with('user')->orderBy('created_at', 'desc')->get();
        $title = 'Scores';
        return view('admin.scores.scores', compact('scores', 'title'));
    }

    /**
     * Compute a single hole's score based on raw input and par.
     * Rules:
     * - blank or null => null
     * - numeric string or int => that number
     * - 'x' (case-insensitive) => par + xStrokePenalty
     */
    public function computeHole($raw, int $par): ?int
    {
        if ($raw === null) return null;
        if (is_string($raw)) {
            $trim = trim($raw);
            if ($trim === '') return null;
            if (strtolower($trim) === 'x') return $par + $this->xStrokePenalty;
            if (ctype_digit($trim)) return (int)$trim;
        }
        if (is_int($raw)) return $raw;
        // Fallback: invalid input
        return null;
    }

    /**
     * Compute totals for a round.
     * @param array<int, mixed> $rawScores Indexed by hole 1..18
     * @param array<int, int> $pars Indexed by hole 1..18
     * @return array{ perHole: array<int, ?int>, computedTotal: int, enteredTotal: int }
     */
    public function computeRound(array $rawScores, array $pars): array
    {
        $perHole = [];
        $computedTotal = 0;
        $enteredTotal = 0;
        for ($i = 1; $i <= 18; $i++) {
            $raw = $rawScores[$i] ?? null;
            $par = $pars[$i] ?? 0;
            $val = $this->computeHole($raw, (int)$par);
            $perHole[$i] = $val;
            if ($val !== null) {
                $computedTotal += $val;
            }
            if ($raw !== null && $raw !== '' && !(is_string($raw) && strtolower(trim((string)$raw)) === 'x')) {
                // entered numeric total (as seen in the "Gross Total (entered)" field when summed)
                if (is_numeric($raw)) $enteredTotal += (int)$raw;
            }
        }
        return [
            'perHole' => $perHole,
            'computedTotal' => $computedTotal,
            'enteredTotal' => $enteredTotal,
        ];
    }
}
