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

            //north course tees
            [
                'tee_id' => 1,
                'tee_code' => 'B',
                'tee_name' => 'Blue (Back)',
                'tee_desc' => 'North Course - Blue Tees',
                'course_id' => 1, // Assuming North Course has ID 1
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'tee_id' => 2,
                'tee_code' => 'W',
                'tee_name' => 'White (Middle)',
                'tee_desc' => 'North Course - White Tees',
                'course_id' => 1, // Assuming North Course has ID 1
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'tee_id' => 3,
                'tee_code' => 'R',
                'tee_name' => 'Red (Front)',
                'tee_desc' => 'North Course - Red Tees',
                'course_id' => 1, // Assuming North Course has ID 1
                'created_by' => 1, // Assuming admin user ID is 1
            ],



            // south course tees
            [
                'tee_id' => 4,
                'tee_code' => 'G',
                'tee_name' => 'Championship Gold',
                'tee_desc' => 'South Course - Gold Tees',
                'course_id' => 2, // Assuming North Course has ID 1
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'tee_id' => 5,
                'tee_code' => 'B',
                'tee_name' => 'Blue (Back)',
                'tee_desc' => 'South Course - Blue Tees',
                'course_id' => 2, // Assuming South Course has ID 2
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'tee_id' => 6,
                'tee_code' => 'W',
                'tee_name' => 'White (Middle)',
                'tee_desc' => 'South Course - White Tees',
                'course_id' => 2, // Assuming South Course has ID 2
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'tee_id' => 7,
                'tee_code' => 'R',
                'tee_name' => 'Red (Front)',
                'tee_desc' => 'South Course - Red Tees',
                'course_id' => 2, // Assuming South Course has ID 2
                'created_by' => 1, // Assuming admin user ID is 1
            ]

        ]);
    }
}
