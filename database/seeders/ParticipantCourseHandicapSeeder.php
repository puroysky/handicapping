<?php

namespace Database\Seeders;

use App\Models\Participant;
use App\Models\ParticipantCourseHandicap;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParticipantCourseHandicapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ParticipantCourseHandicap::insert([
            [
                'participant_course_handicap_id' => 1,
                'tournament_id' => 1,
                'participant_id' => 1,
                'course_id' => 1,
                'tee_id' => 1,
                'course_handicap' => 8.5,
                'created_by' => 1,
            ],
            [
                'participant_course_handicap_id' => 2,
                'tournament_id' => 1,
                'participant_id' => 2,
                'course_id' => 1,
                'tee_id' => 2,
                'course_handicap' => 15.3,
                'created_by' => 1,
            ],

        ]);
    }
}
