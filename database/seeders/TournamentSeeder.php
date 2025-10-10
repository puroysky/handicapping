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
                'tournament_name' => 'Spring Invitational',
                'tournament_desc' => 'Annual Spring Golf Tournament',
                'tournament_start' => '2024-03-15',
                'tournament_end' => '2024-03-17',
                'created_by' => 1
            ],
            [
                'tournament_id' => 2,
                'tournament_name' => 'Summer Open',
                'tournament_desc' => 'Open Tournament for All Members',
                'tournament_start' => '2024-06-20',
                'tournament_end' => '2024-06-22',
                'created_by' => 1
            ],
            [
                'tournament_id' => 3,
                'tournament_name' => 'Fall Classic',
                'tournament_desc' => 'Classic Tournament to End the Season',
                'tournament_start' => '2024-09-10',
                'tournament_end' => '2024-09-12',
                'created_by' => 1
            ],
            [
                'tournament_id' => 4,
                'tournament_name' => 'Winter Championship',
                'tournament_desc' => 'Championship Tournament in Winter',
                'tournament_start' => '2024-12-05',
                'tournament_end' => '2024-12-07',
                'created_by' => 1
            ],

        ]);
    }
}
