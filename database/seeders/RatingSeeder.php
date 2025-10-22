<?php

namespace Database\Seeders;

use App\Models\CourseRating;
use App\Models\Rating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rating::insert([
            [
                'scorecard_id' => 1,
                'tee_id' => 1,
                'course_rating' => 68.8,
                'slope_rating' => 132,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'tee_id' => 2,
                'course_rating' => 66.7,
                'slope_rating' => 125,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'tee_id' => 3,
                'course_rating' => 68.5,
                'slope_rating' => 125,
                'created_by' => 1, // Assuming admin user ID is 1
            ],


            [
                'scorecard_id' => 2,
                'tee_id' => 4,
                'course_rating' => 74,
                'slope_rating' => 137,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'tee_id' => 5,
                'course_rating' => 72.8,
                'slope_rating' => 134,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'tee_id' => 6,
                'course_rating' => 70.6,
                'slope_rating' => 126,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 2,
                'tee_id' => 7,
                'course_rating' => 73.3,
                'slope_rating' => 129,
                'created_by' => 1, // Assuming admin user ID is 1
            ]

        ]);
    }
}
