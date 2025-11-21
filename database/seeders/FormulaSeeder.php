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
            // Adjusted Gross Score (AGS) Formulas
            [
                'formula_id' => 1,
                'formula_type_id' => 1, // AGS
                'course_id' => 1,
                'formula_name' => 'Adjusted Gross Score - Base',
                'formula_code' => 'AGS-BASE-001',
                'formula_desc' => 'Calculates adjusted gross score by applying double bogey limit to raw score',
                'formula_expression' => 'PAR + DOUBLE_BOGEY_LIMIT + HANDICAP_STROKES',
                'system_variables' => json_encode([
                    'PAR',
                    'HANDICAP_STROKES',
                    'DOUBLE_BOGEY_LIMIT',
                ]),
                'remarks' => 'USGA standard formula for gross score adjustment based on player handicap and course par',
                'active' => true,
                'created_by' => 1,
            ],

            [
                'formula_id' => 2,
                'formula_type_id' => 1, // AGS
                'course_id' => 2,
                'formula_name' => 'Adjusted Gross Score - Base',
                'formula_code' => 'AGS-BASE-002',
                'formula_desc' => 'Calculates adjusted gross score by applying double bogey limit to raw score',
                'formula_expression' => 'PAR + DOUBLE_BOGEY_LIMIT + HANDICAP_STROKES',
                'system_variables' => json_encode([
                    'PAR',
                    'HANDICAP_STROKES',
                    'DOUBLE_BOGEY_LIMIT',
                ]),
                'remarks' => 'USGA standard formula for gross score adjustment based on player handicap and course par',
                'active' => true,
                'created_by' => 1,
            ],

            // Score Differential (SD) Formulas
            [
                'formula_id' => 3,
                'formula_type_id' => 2, // SD
                'course_id' => 1,
                'formula_name' => 'Score Differential - USGA Standard',
                'formula_code' => 'SD-USGA-001',
                'formula_desc' => 'Calculates score differential using USGA formula with slope rating adjustment',
                'formula_expression' => 'ROUND((113 / SLOPE_RATING) * (ADJUSTED_GROSS_SCORE - COURSE_RATING))',
                'system_variables' => json_encode([
                    'ADJUSTED_GROSS_SCORE',
                    'COURSE_RATING',
                    'SLOPE_RATING',
                ]),
                'remarks' => 'USGA standard formula (113 is the standard slope rating). Measures score performance relative to course difficulty',
                'active' => true,
                'created_by' => 1,
            ],

            [
                'formula_id' => 4,
                'formula_type_id' => 2, // SD
                'course_id' => 2,
                'formula_name' => 'Score Differential - USGA Standard',
                'formula_code' => 'SD-USGA-002',
                'formula_desc' => 'Calculates score differential using USGA formula with slope rating adjustment',
                'formula_expression' => '(113 / SLOPE_RATING) * (ADJUSTED_GROSS_SCORE - COURSE_RATING)',
                'system_variables' => json_encode([
                    'ADJUSTED_GROSS_SCORE',
                    'COURSE_RATING',
                    'SLOPE_RATING',
                ]),
                'remarks' => 'USGA standard formula (113 is the standard slope rating). Measures score performance relative to course difficulty',
                'active' => true,
                'created_by' => 1,
            ],

            // Course Handicap (CH) Formulas
            [
                'formula_id' => 7,
                'formula_type_id' => 4, // CH
                'course_id' => 1,
                'formula_name' => 'Course Handicap - USGA Standard',
                'formula_code' => 'CH-USGA-001',
                'formula_desc' => 'Converts handicap index to course handicap using slope and course rating',
                'formula_expression' => 'HANDICAP_INDEX * (SLOPE_RATING / 113) + (COURSE_RATING - PAR)',
                'system_variables' => json_encode([
                    'HANDICAP_INDEX',
                    'SLOPE_RATING',
                    'COURSE_RATING',
                    'PAR',
                ]),
                'remarks' => 'USGA standard formula. Adjusts handicap index for specific course difficulty and rating',
                'active' => true,
                'created_by' => 1,
            ],

            [
                'formula_id' => 8,
                'formula_type_id' => 4, // CH
                'course_id' => 2,
                'formula_name' => 'Course Handicap - USGA Standard',
                'formula_code' => 'CH-USGA-002',
                'formula_desc' => 'Converts handicap index to course handicap using slope and course rating',
                'formula_expression' => 'HANDICAP_INDEX * (SLOPE_RATING / 113) + (COURSE_RATING - PAR)',
                'system_variables' => json_encode([
                    'HANDICAP_INDEX',
                    'SLOPE_RATING',
                    'COURSE_RATING',
                    'PAR',
                ]),
                'remarks' => 'USGA standard formula. Adjusts handicap index for specific course difficulty and rating',
                'active' => true,
                'created_by' => 1,
            ]
        ]);
    }
}
