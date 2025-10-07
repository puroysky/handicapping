<?php

namespace Database\Seeders;

use App\Models\SlopeRating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SlopeRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        SlopeRating::insert([
            [
                'scorecard_id' => 1,
                'tee_id' => 2,
                'slope_rating' => 132,
                'created_by' => 1, // Assuming admin user ID is 1
            ]
        ]);
    }
}
