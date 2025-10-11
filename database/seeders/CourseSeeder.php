<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::insert(
            [
                [
                    'course_id' => 1,
                    'course_code' => 'N',
                    'course_name' => 'North Course',
                    'course_desc' => 'The North Course at Valley Golf Club',
                    'created_by' => 1, // Assuming admin user ID is 1
                ],
                [
                    'course_id' => 2,
                    'course_code' => 'S',
                    'course_name' => 'South Course',
                    'course_desc' => 'The South Course at Valley Golf Club',
                    'created_by' => 1, // Assuming admin user ID is 1
                ]
            ]
        );
    }
}
