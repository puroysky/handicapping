<?php

namespace Database\Seeders;

use App\Models\Tournament;
use App\Models\TournamentCourse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TournamentCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TournamentCourse::insert([
            [
                'tournament_course_id' => 1,
                'tournament_id' => 1,
                'course_id' => 1,
                'scorecard_id' => 1,
                'created_by' => 1
            ],

            [
                'tournament_course_id' => 2,
                'tournament_id' => 1,
                'course_id' => 2,
                'scorecard_id' => 2,
                'created_by' => 1
            ],

            [
                'tournament_course_id' => 3,
                'tournament_id' => 2,
                'course_id' => 1,
                'scorecard_id' => 1,
                'created_by' => 1
            ],
            [
                'tournament_course_id' => 4,
                'tournament_id' => 2,
                'course_id' => 2,
                'scorecard_id' => 2,
                'created_by' => 1
            ],
            [
                'tournament_course_id' => 5,
                'tournament_id' => 3,
                'course_id' => 1,
                'scorecard_id' => 1,
                'created_by' => 1
            ],
            [
                'tournament_course_id' => 6,
                'tournament_id' => 4,
                'course_id' => 2,
                'scorecard_id' => 2,
                'created_by' => 1
            ],

        ]);
    }
}
