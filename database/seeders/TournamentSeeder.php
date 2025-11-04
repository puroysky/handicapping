<?php

namespace Database\Seeders;

use App\Models\Tournament;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TournamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tournament::insert([
            [
                'tournament_id' => 1,
                'tournament_name' => 'All Seniors 2025',
                'tournament_desc' => 'Tournament for All Senior 2025',
                'tournament_start' => '2025-08-30',
                'tournament_end' => '2025-08-30',
                'handicap_formula_expression' => 'AVG(SELECTED_DIFFERENTIALS) * 0.96',
                'score_diff_start_date' => '2023-01-01',
                'score_diff_end_date' => '2025-07-31',
                'recent_scores_count' => 20,
                'score_selection_type' => 'LOWEST',
                'created_by' => 1
            ],
            [
                'tournament_id' => 2,
                'tournament_name' => 'Summer Open',
                'tournament_desc' => 'Open Tournament for All Members',
                'tournament_start' => '2024-06-20',
                'tournament_end' => '2024-06-22',
                'handicap_formula_expression' => 'AVG(SELECTED_DIFFERENTIALS) * 0.96',
                'score_diff_start_date' => '2024-04-01',
                'score_diff_end_date' => '2024-06-19',
                'recent_scores_count' => 20,
                'score_selection_type' => 'LOWEST',
                'created_by' => 1
            ],
            [
                'tournament_id' => 3,
                'tournament_name' => 'Fall Classic',
                'tournament_desc' => 'Classic Tournament to End the Season',
                'tournament_start' => '2024-09-10',
                'tournament_end' => '2024-09-12',
                'handicap_formula_expression' => 'AVG(SELECTED_DIFFERENTIALS) * 0.96',
                'score_diff_start_date' => '2024-07-01',
                'score_diff_end_date' => '2024-09-09',
                'recent_scores_count' => 5,
                'score_selection_type' => 'HIGHEST',
                'created_by' => 1
            ],
            [
                'tournament_id' => 4,
                'tournament_name' => 'Winter Championship',
                'tournament_desc' => 'Championship Tournament in Winter',
                'tournament_start' => '2024-12-05',
                'tournament_end' => '2024-12-07',
                'handicap_formula_expression' => 'AVG(SELECTED_DIFFERENTIALS) * 0.96',
                'score_diff_start_date' => '2024-10-01',
                'score_diff_end_date' => '2024-12-04',
                'recent_scores_count' => 20,
                'score_selection_type' => 'LOWEST',
                'created_by' => 1
            ],

        ]);
    }
}
