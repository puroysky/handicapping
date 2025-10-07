<?php

namespace Database\Seeders;

use App\Models\CourseRating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseRating::insert([
            [
                'scorecard_id' => 1,
                'tee_id' => 2,
                'course_rating' => 68.8,
                'created_by' => 1, // Assuming admin user ID is 1
            ]
        ]);
    }
}
