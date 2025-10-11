<?php

namespace Database\Seeders;

use App\Models\Tee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tee::insert([

            [
                'tee_id' => 1,
                'tee_code' => 'BLUE',
                'tee_name' => 'Back',
                'tee_desc' => 'North Course - Blue Tees',
                'course_id' => 1, // Assuming North Course has ID 1
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'tee_id' => 2,
                'tee_code' => 'WHITE',
                'tee_name' => 'Middle',
                'tee_desc' => 'North Course - White Tees',
                'course_id' => 1, // Assuming North Course has ID 1
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'tee_id' => 3,
                'tee_code' => 'RED',
                'tee_name' => 'Front',
                'tee_desc' => 'North Course - Red Tees',
                'course_id' => 1, // Assuming North Course has ID 1
                'created_by' => 1, // Assuming admin user ID is 1
            ],


            [
                'tee_id' => 4,
                'tee_code' => 'GOLD',
                'tee_name' => 'Championship Gold',
                'tee_desc' => 'North Course - Gold Tees',
                'course_id' => 2, // Assuming North Course has ID 1
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'tee_id' => 5,
                'tee_code' => 'BLUE',
                'tee_name' => 'Back',
                'tee_desc' => 'North Course - Blue Tees',
                'course_id' => 2, // Assuming South Course has ID 2
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'tee_id' => 6,
                'tee_code' => 'WHITE',
                'tee_name' => 'Middle',
                'tee_desc' => 'North Course - White Tees',
                'course_id' => 2, // Assuming South Course has ID 2
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'tee_id' => 7,
                'tee_code' => 'RED',
                'tee_name' => 'Front',
                'tee_desc' => 'North Course - Red Tees',
                'course_id' => 2, // Assuming South Course has ID 2
                'created_by' => 1, // Assuming admin user ID is 1
            ]

        ]);
    }
}
