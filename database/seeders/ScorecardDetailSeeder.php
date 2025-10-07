<?php

namespace Database\Seeders;

use App\Models\Scorecard;
use App\Models\ScorecardDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScorecardDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // North Course - Blue
        ScorecardDetail::insert(
            [
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 1,
                    'yardage' => 360,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 2,
                    'yardage' => 346,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 3,
                    'yardage' => 192,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 4,
                    'yardage' => 130,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 5,
                    'yardage' => 521,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 6,
                    'yardage' => 164,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 7,
                    'yardage' => 476,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 8,
                    'yardage' => 156,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 9,
                    'yardage' => 469,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 10,
                    'yardage' => 418,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 11,
                    'yardage' => 151,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 12,
                    'yardage' => 145,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 13,
                    'yardage' => 475,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 14,
                    'yardage' => 288,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 15,
                    'yardage' => 155,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 16,
                    'yardage' => 427,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 17,
                    'yardage' => 382,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'scorecard_id' => 1,
                    'tee_id' => 2,
                    'hole' => 18,
                    'yardage' => 348,

                    'created_by' => 1, // Assuming admin user ID is 1
                ],
            ]
        );
    }
}
