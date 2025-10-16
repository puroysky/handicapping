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
                'formula_type_id' => 1, // ASG
                'formula_name' => 'Adjusted Gross Score v1.0',
                'formula_code' => 'ASG-v1.0',
                'formula_desc' => 'Initial version of Adjusted Gross Score formula',
                'formula_expression' => 'HOLE_PAR + DOUBLE_BOGEY_LIMIT + HANDICAP_STROKES',
                'formula_components' => json_encode([

                    [
                        'type' => 'field',
                        'name' => 'HOLE_PAR',
                    ],
                    [
                        'type' => 'field',
                        'name' => 'HANDICAP_STROKES',
                    ],
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
                'formula_type_id' => 2, // SD
                'formula_name' => 'Score Differential v1.0',
                'formula_code' => 'SD-v1.0',
                'formula_desc' => 'Initial version of Score Differential formula',
                'formula_expression' => 'SD = (AGS - Course Rating) x 113 / Slope Rating',
                'formula_components' => json_encode([
                    'adjusted_gross_score',
                    'course_rating',
                    'slope_rating',
                ]),
                'remarks' => 'Used for calculating Score Differential',
                'active' => true,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'formula_type_id' => 3, // HI
                'formula_name' => 'Handicap Index v1.0',
                'formula_code' => 'HI-V1.0',
                'formula_desc' => 'Initial version of Handicap Index formula',
                'formula_expression' => 'HI = Average of Lowest Score Differentials x 0.96',
                'formula_components' => json_encode([
                    'score_differentials',
                ]),
                'remarks' => 'Used for calculating Handicap Index',
                'active' => true,
                'created_by' => 1, // Assuming admin user ID is 1
            ]
        ]);
    }
}
