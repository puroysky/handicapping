<?php

namespace Database\Seeders;

use App\Models\Score;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Score::insert([

            [
                'player_profile_id' => 1,
                'user_profile_id' => 1,
                'user_id' => 2,
                'participant_id' => 1,
                'tournament_id' => 1,
                'course_id' => 1,
                'tee_id' => 1,
                'date_played' => '2025-10-15',
                'scoring_method' => 'hbh',
                'entry_type' => 'form',
                'holes_played' => '18',
                'tournament_handicap_index' => 12.5,
                'handicap_index_type' => 'local',
                'course_handicap' => 14,
                'gross_score' => 85,
                'adjusted_gross_score' => 80,
                'net_score' => 66,
                'score_differential' => 7.3,
                'is_verified' => true,
                'verified_by' => 1,
                'verified_at' => now(),
                'created_by' => 1,
            ],

            [
                'player_profile_id' => 2,
                'user_profile_id' => 2,
                'user_id' => 3,
                'participant_id' => 2,
                'tournament_id' => 1,
                'course_id' => 1,
                'tee_id' => 2,
                'date_played' => '2025-10-15',
                'scoring_method' => 'hbh',
                'entry_type' => 'form',
                'holes_played' => '18',
                'tournament_handicap_index' => 12.5,
                'handicap_index_type' => 'whs',
                'course_handicap' => 14,
                'gross_score' => 85,
                'adjusted_gross_score' => 80,
                'net_score' => 66,
                'score_differential' => 7.3,
                'is_verified' => true,
                'verified_by' => 1,
                'verified_at' => now(),
                'created_by' => 1,
            ],


        ]);
    }
}
