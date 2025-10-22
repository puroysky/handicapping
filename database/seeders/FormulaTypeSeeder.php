<?php

namespace Database\Seeders;

use App\Models\Formula;
use App\Models\FormulaType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormulaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        FormulaType::insert([
            [
                'formula_type_id' => 1,
                'formula_type_code' => 'AGS',
                'formula_type_name' => 'Adjusted Gross Score',
                'formula_type_desc' => 'Adjusted Gross Score Formula',
                'remarks' => 'Used for calculating Adjusted Gross Score',
                'formula_type_fields' => json_encode([
                    'PAR',
                    'HANDICAP_STROKES',
                    'DOUBLE_BOGEY_LIMIT',
                ]),
                'active' => true,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'formula_type_id' => 2,
                'formula_type_code' => 'SD',
                'formula_type_name' => 'Score Differential',
                'formula_type_desc' => 'Score Differential Formula',
                'remarks' => 'Used for calculating Score Differential',

                'formula_type_fields' => json_encode([
                    'adjusted_gross_score',
                    'course_rating',
                    'slope_rating',
                ]),

                'active' => true,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'formula_type_id' => 3,
                'formula_type_code' => 'HI',
                'formula_type_name' => 'Handicap Index',
                'formula_type_desc' => 'Handicap Index Formula',
                'formula_type_fields' => json_encode([
                    'score_differentials',
                ]),
                'remarks' => 'Used for calculating Handicap Index',
                'active' => true,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'formula_type_id' => 4,
                'formula_type_code' => 'CH',
                'formula_type_name' => 'Course Handicap',
                'formula_type_desc' => 'Course Handicap Formula',
                'formula_type_fields' => json_encode([
                    'HANDICAP_INDEX',
                    'SLOPE_RATING',
                    'COURSE_RATING',
                    'PAR',
                ]),
                'remarks' => 'Used for calculating Course Handicap',
                'active' => true,
                'created_by' => 1, // Assuming admin user ID is 1
            ]
        ]);
    }
}
