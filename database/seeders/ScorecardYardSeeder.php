<?php

namespace Database\Seeders;

use App\Models\ScorecardYard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScorecardYardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ScorecardYard::insert([
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 1,
                'tee_id' => 2,
                'yardage' => 360,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 2,
                'yardage' => 346,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 3,
                'yardage' => 192,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 4,
                'yardage' => 130,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 5,
                'yardage' => 521,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 6,
                'yardage' => 208,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 7,
                'yardage' => 403,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 8,
                'yardage' => 150,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 9,
                'yardage' => 370,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 10,
                'yardage' => 403,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 11,
                'yardage' => 151,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 12,
                'yardage' => 145,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 13,
                'yardage' => 475,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 14,
                'yardage' => 288,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 15,
                'yardage' => 155,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 16,
                'yardage' => 427,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 17,
                'yardage' => 382,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 18,
                'yardage' => 348,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
        ]);
    }
}
