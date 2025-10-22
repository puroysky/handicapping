<?php

namespace Database\Seeders;

use App\Models\Scorecard;
use App\Models\ScorecardHandicapHole;
use App\Models\ScorecardStrokeIndex;
use App\Models\ScorecardHoleHandicap;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScorecardStokeIndexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //         $table->unsignedBigInteger('scorecard_id');
        // $table->unsignedBigInteger('scorecard_hole_id');
        // $table->enum('sex', ['M', 'F'])->comment('M = Male, F = Female');
        // $table->unsignedSmallInteger('stroke_index')->nullable()->default(null)->comment('Handicap for the hole, typically 1-18, null if not assigned');


        ScorecardStrokeIndex::insert([
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 1,
                'hole' => 1,
                'sex' => 'M',
                'stroke_index' => 3,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 2,
                'hole' => 2,
                'sex' => 'M',
                'stroke_index' => 5,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 3,
                'hole' => 3,
                'sex' => 'M',
                'stroke_index' => 11,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 4,
                'hole' => 4,
                'sex' => 'M',
                'stroke_index' => 13,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 5,
                'hole' => 5,
                'sex' => 'M',
                'stroke_index' => 1,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 6,
                'hole' => 6,
                'sex' => 'M',
                'stroke_index' => 7,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 7,
                'hole' => 7,
                'sex' => 'M',
                'stroke_index' => 17,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 8,
                'hole' => 8,
                'sex' => 'M',
                'stroke_index' => 15,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 9,
                'hole' => 9,
                'sex' => 'M',
                'stroke_index' => 9,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 10,
                'hole' => 10,
                'sex' => 'M',
                'stroke_index' => 12,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 11,
                'hole' => 11,
                'sex' => 'M',
                'stroke_index' => 18,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 12,
                'hole' => 12,
                'sex' => 'M',
                'stroke_index' => 10,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 13,
                'hole' => 13,
                'sex' => 'M',
                'stroke_index' => 6,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 14,
                'hole' => 14,
                'sex' => 'M',
                'stroke_index' => 14,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 15,
                'hole' => 15,
                'sex' => 'M',
                'stroke_index' => 16,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 16,
                'hole' => 16,
                'sex' => 'M',
                'stroke_index' => 8,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 17,
                'hole' => 17,
                'sex' => 'M',
                'stroke_index' => 4,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 18,
                'hole' => 18,
                'sex' => 'M',
                'stroke_index' => 2,
                'created_by' => 1,
            ],

            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 1,
                'hole' => 1,
                'sex' => 'F',
                'stroke_index' => 3,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 2,
                'hole' => 2,
                'sex' => 'F',
                'stroke_index' => 7,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 3,
                'hole' => 3,
                'sex' => 'F',
                'stroke_index' => 13,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 4,
                'hole' => 4,
                'sex' => 'F',
                'stroke_index' => 9,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 5,
                'hole' => 5,
                'sex' => 'F',
                'stroke_index' => 1,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 6,
                'hole' => 6,
                'sex' => 'F',
                'stroke_index' => 15,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 7,
                'hole' => 7,
                'sex' => 'F',
                'stroke_index' => 11,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 8,
                'hole' => 8,
                'sex' => 'F',
                'stroke_index' => 17,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 9,
                'hole' => 9,
                'sex' => 'F',
                'stroke_index' => 5,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 10,
                'hole' => 10,
                'sex' => 'F',
                'stroke_index' => 5,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 11,
                'hole' => 11,
                'sex' => 'F',
                'stroke_index' => 18,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 12,
                'hole' => 12,
                'sex' => 'F',
                'stroke_index' => 14,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 13,
                'hole' => 13,
                'sex' => 'F',
                'stroke_index' => 4,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 14,
                'hole' => 14,
                'sex' => 'F',
                'stroke_index' => 12,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 15,
                'hole' => 15,
                'sex' => 'F',
                'stroke_index' => 16,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 16,
                'hole' => 16,
                'sex' => 'F',
                'stroke_index' => 6,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 17,
                'hole' => 17,
                'sex' => 'F',
                'stroke_index' => 8,
                'created_by' => 1,
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 18,
                'hole' => 18,
                'sex' => 'F',
                'stroke_index' => 2,
                'created_by' => 1,
            ]
        ]);
    }
}
