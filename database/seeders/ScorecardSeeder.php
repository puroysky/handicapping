<?php

namespace Database\Seeders;

use App\Models\Scorecard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScorecardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Scorecard::insert(
            [
                [
                    'scorecard_code' => 'v1.0.0',
                    'scorecard_name' => 'North',
                    'scorecard_desc' => 'The North Course Score Card Version 1',
                    'course_id' => 1, // Assuming North Course has ID 1
                    'course_rating_formula_id' => 4,
                    'x_value' => 2,
                    'total_holes' => 18,
                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_code' => 'v1.0.0',
                    'scorecard_name' => 'South',
                    'scorecard_desc' => 'The North Course Score Card Version 1',
                    'course_id' => 2, // Assuming North Course has ID 1
                    'course_rating_formula_id' => 4,
                    'x_value' => 3,
                    'total_holes' => 18,
                    'created_by' => 1, // Assuming admin user ID is 1
                ]
            ]
        );
    }
}
