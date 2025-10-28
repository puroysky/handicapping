<?php

namespace Database\Seeders;

use App\Models\Participant;
use App\Models\TournamentPlayer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Participant::insert([
            [
                'tournament_id' => 1,
                'user_id' => 2,
                'player_profile_id' => 1,
                'whs_handicap_index' => 1,
                'local_handicap_index' => 1,
                'tournament_handicap_index' => 1.5,
                'local_handicap_index' => 1.0,
                'index_type' => 'WHS',
                'created_by' => 1
            ],
            [
                'tournament_id' => 1,
                'user_id' => 3,
                'player_profile_id' => 2,
                'whs_handicap_index' => 2,
                'local_handicap_index' => 1,
                'tournament_handicap_index' => 1.5,
                'local_handicap_index' => 1.0,
                'index_type' => 'WHS',
                'created_by' => 1
            ]
        ]);
    }
}
