<?php

namespace Database\Seeders;

use App\Models\Formula;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {





        Formula::insert([
            [
                'formula_id' => 1,
                'formula_type_id' => 1, // AGS
                'course_id' => 1,
                'formula_name' => 'Adjusted Gross Score v1.0',
                'formula_code' => 'N-ASG01',
                'formula_desc' => 'Initial version of Adjusted Gross Score formula',
                'formula_expression' => 'PAR + DOUBLE_BOGEY_LIMIT + HANDICAP_STROKES',
                'formula_variables' => json_encode([
                    [
                        'type' => 'variable',
                        'name' => 'DOUBLE_BOGEY_LIMIT',
                        'value' => 2,
                    ],
                ]),
                'remarks' => 'Used for calculating Adjusted Gross Score',
                'active' => true,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'formula_id' => 2,
                'formula_type_id' => 1, // AGS
                'course_id' => 2,
                'formula_name' => 'Adjusted Gross Score v1.0',
                'formula_code' => 'S-ASG01',
                'formula_desc' => 'Initial version of Adjusted Gross Score formula',
                'formula_expression' => 'PAR + DOUBLE_BOGEY_LIMIT + HANDICAP_STROKES',
                'formula_variables' => json_encode([
                    [
                        'type' => 'variable',
                        'name' => 'DOUBLE_BOGEY_LIMIT',
                        'value' => 2,
                    ],
                ]),
                'remarks' => 'Used for calculating Adjusted Gross Score',
                'active' => true,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'formula_id' => 3,
                'formula_type_id' => 2, // SD
                'course_id' => 1,
                'formula_name' => 'Score Differential v1.0',
                'formula_code' => 'N-SD01',
                'formula_desc' => 'Initial version of Score Differential formula',
                'formula_expression' => 'SD = (AGS - Course Rating) x 113 / Slope Rating',
                'formula_variables' => json_encode([
                    'adjusted_gross_score',
                    'course_rating',
                    'slope_rating',
                ]),
                'remarks' => 'Used for calculating Score Differential',
                'active' => true,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'formula_id' => 4,
                'formula_type_id' => 2, // SD
                'course_id' => 2,
                'formula_name' => 'Score Differential v1.0',
                'formula_code' => 'S-SD01',
                'formula_desc' => 'Initial version of Score Differential formula',
                'formula_expression' => 'SD = (AGS - Course Rating) x 113 / Slope Rating',
                'formula_variables' => json_encode([
                    'adjusted_gross_score',
                    'course_rating',
                    'slope_rating',
                ]),
                'remarks' => 'Used for calculating Score Differential',
                'active' => true,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'formula_id' => 5,
                'formula_type_id' => 3, // HI
                'course_id' => 1,
                'formula_name' => 'Handicap Index v1.0',
                'formula_code' => 'N-HI01',
                'formula_desc' => 'Initial version of Handicap Index formula',
                'formula_expression' => 'HI = Average of Lowest Score Differentials x 0.96',
                'formula_variables' => json_encode([
                    'score_differentials',
                ]),
                'remarks' => 'Used for calculating Handicap Index',
                'active' => true,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'formula_id' => 6,
                'formula_type_id' => 3, // HI
                'course_id' => 2,
                'formula_name' => 'Handicap Index v1.0',
                'formula_code' => 'S-HI01',
                'formula_desc' => 'Initial version of Handicap Index formula',
                'formula_expression' => 'HI = Average of Lowest Score Differentials x 0.96',
                'formula_variables' => json_encode([
                    'score_differentials',
                ]),
                'remarks' => 'Used for calculating Handicap Index',
                'active' => true,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'formula_id' => 7,
                'formula_type_id' => 4,
                'course_id' => 1,
                'formula_name' => 'Course Rating v1.0',
                'formula_code' => 'N-CH01',
                'formula_desc' => '',

                'formula_expression' => 'HANDICAP_INDEX * (SLOPE_RATING / STANDARD_SLOPE_RATING) + (COURSE_RATING - PAR)',
                'formula_variables' => json_encode([
                    [
                        'name' => 'STANDARD_SLOPE_RATING',
                        'value' => 113,
                    ],
                ]),
                'remarks' => 'Used for calculating Course Hadicap',
                'active' => true,
                'created_by' => 1, // Assuming admin user ID is 1

            ],

            [
                'formula_id' => 8,
                'formula_type_id' => 4,
                'course_id' => 2,
                'formula_name' => 'Course Rating v1.0',
                'formula_code' => 'S-CH01',
                'formula_desc' => '',

                'formula_expression' => 'HANDICAP_INDEX * (SLOPE_RATING / STANDARD_SLOPE_RATING) + (COURSE_RATING - PAR)',
                'formula_variables' => json_encode([
                    [
                        'name' => 'STANDARD_SLOPE_RATING',
                        'value' => 113,
                    ],
                ]),
                'remarks' => 'Used for calculating Course Hadicap',
                'active' => true,
                'created_by' => 1, // Assuming admin user ID is 1

            ]
        ]);
    }
}
