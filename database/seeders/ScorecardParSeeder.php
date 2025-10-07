<?php

namespace Database\Seeders;

use App\Models\ScorecardPar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScorecardParSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {



        ScorecardPar::insert([
            [
                'scorecard_id' => 1,
                'hole' => 1,
                'par' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 2,
                'par' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 3,
                'par' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 4,
                'par' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 5,
                'par' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 6,
                'par' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 7,
                'par' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 8,
                'par' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 9,
                'par' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 10,
                'par' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 11,
                'par' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 12,
                'par' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 13,
                'par' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 14,
                'par' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 15,
                'par' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 16,
                'par' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 17,
                'par' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'hole' => 18,
                'par' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],



        ]);
    }
}
