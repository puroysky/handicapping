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
                'formula_type_desc' => 'Applies double bogey limit rules to convert gross score into adjusted gross score for handicap calculation',
                'remarks' => 'USGA standard formula type. Limits player scores to double bogey based on handicap. Required for score differential calculation',
                'formula_type_fields' => json_encode([
                    'PAR',
                    'HANDICAP_STROKES',
                    'DOUBLE_BOGEY_LIMIT',
                ]),
                'active' => true,
                'created_by' => 1,
            ],
            [
                'formula_type_id' => 2,
                'formula_type_code' => 'SD',
                'formula_type_name' => 'Score Differential',
                'formula_type_desc' => 'Calculates the difference between adjusted gross score and course rating, adjusted for course slope difficulty',
                'remarks' => 'USGA standard formula type. Measures performance relative to course difficulty using 113 as the standard slope. Used to calculate handicap index',
                'formula_type_fields' => json_encode([
                    'ADJUSTED_GROSS_SCORE',
                    'COURSE_RATING',
                    'SLOPE_RATING',
                ]),
                'active' => true,
                'created_by' => 1,
            ],
            [
                'formula_type_id' => 3,
                'formula_type_code' => 'HI',
                'formula_type_name' => 'Handicap Index',
                'formula_type_desc' => 'Derives player handicap index from recent score differentials, typically using best 8 of last 20 scores',
                'formula_type_fields' => json_encode([
                    'SCORE_DIFFERENTIALS',
                ]),
                'remarks' => 'USGA standard formula type. Represents player skill level independent of course. Updated periodically as new scores are recorded',
                'active' => true,
                'created_by' => 1,
            ],
            [
                'formula_type_id' => 4,
                'formula_type_code' => 'CH',
                'formula_type_name' => 'Course Handicap',
                'formula_type_desc' => 'Converts player handicap index into a course-specific handicap adjusted for slope rating and course rating',
                'formula_type_fields' => json_encode([
                    'HANDICAP_INDEX',
                    'SLOPE_RATING',
                    'COURSE_RATING',
                    'PAR',
                ]),
                'remarks' => 'USGA standard formula type. Used in competition to adjust player scores. Accounts for course difficulty relative to par',
                'active' => true,
                'created_by' => 1,
            ]
        ]);
    }
}
