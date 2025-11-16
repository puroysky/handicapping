<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $calculationTable = '{
        "3": {
            "max": "3",
            "min": "3",
            "count": "1",
            "method": "LOWEST",
            "adjustment": "-2.0"
        },
        "4": {
            "max": "4",
            "min": "4",
            "count": "1",
            "method": "LOWEST",
            "adjustment": "-1.0"
        },
        "5": {
            "max": "5",
            "min": "5",
            "count": "1",
            "method": "LOWEST",
            "adjustment": "0"
        },
        "6": {
            "max": "6",
            "min": "6",
            "count": "2",
            "method": "AVERAGE_OF_LOWEST",
            "adjustment": "-1.0"
        },
        "7": {
            "max": "8",
            "min": "7",
            "count": "2",
            "method": "AVERAGE_OF_LOWEST",
            "adjustment": "0"
        },
        "9": {
            "max": "11",
            "min": "9",
            "count": "3",
            "method": "AVERAGE_OF_LOWEST",
            "adjustment": "0"
        },
        "12": {
            "max": "14",
            "min": "12",
            "count": "4",
            "method": "AVERAGE_OF_LOWEST",
            "adjustment": "0"
        },
        "15": {
            "max": "16",
            "min": "15",
            "count": "5",
            "method": "AVERAGE_OF_LOWEST",
            "adjustment": "0"
        },
        "17": {
            "max": "18",
            "min": "17",
            "count": "6",
            "method": "AVERAGE_OF_LOWEST",
            "adjustment": "0"
        },
        "19": {
            "max": "19",
            "min": "19",
            "count": "7",
            "method": "AVERAGE_OF_LOWEST",
            "adjustment": "0"
        },
        "20": {
            "max": "20",
            "min": "20",
            "count": "8",
            "method": "AVERAGE_OF_LOWEST",
            "adjustment": "0"
        }
    }';


        SystemSetting::insert([
            [
                'setting_code' => 'site_name',
                'setting_name' => 'Site Name',
                'setting_desc' => 'The name of the handicapping system',
                'setting_value' => 'Golf Handicapping System',
                'setting_value_type' => 'text',
                'remarks' => 'Default site name',
                'active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_code' => 'score_differential.formula',
                'setting_name' => 'Score Differential Formula',
                'setting_desc' => 'Formula used to calculate score differentials',
                'setting_value' => 'ADJUSTED_SCORE - COURSE_RATING * 113 / SLOPE_RATING',
                'setting_value_type' => 'text',
                'remarks' => 'USGA Score Differential Formula',
                'active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_code' => 'local_handicap.calculation_start_date',
                'setting_name' => 'Handicap Calculation Start Date',
                'setting_desc' => 'The date from which the system starts calculating handicaps',
                'setting_value' => '2020-01-01',
                'setting_value_type' => 'text',
                'remarks' => 'Default start date for handicap calculations',
                'active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_code' => 'local_handicap.calculation_end_date',
                'setting_name' => 'Handicap Calculation End Date',
                'setting_desc' => 'The date until which the system calculates handicaps',
                'setting_value' => '2030-12-31',
                'setting_value_type' => 'text',
                'remarks' => 'Default end date for handicap calculations',
                'active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_code' => 'local_handicap.calculation_table',
                'setting_name' => 'Handicap Calculation Table',
                'setting_desc' => 'The bracket configuration for handicap calculations',
                'setting_value' => $calculationTable,
                'setting_value_type' => 'json',
                'remarks' => 'Default handicap bracket configuration',
                'active' => true,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
