<?php

namespace Database\Seeders;

use App\Models\Formula;
use App\Models\Member;
use App\Models\PlayerProfile;
use App\Models\ScorecardHandicapHole;
use App\Models\Tournament;
use App\Models\User;
use App\Models\UserProfile;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::insert([
            [
                'id' => 1,
                'email' => 'developer@gmail.com',
                'role' => 'admin',
                'default_account' => true,
                'password' => bcrypt('123123123'),
                'created_by' => 1,
            ],

            [
                'id' => 2,
                'email' => 'asuncion.froilan1010@gmail.com',
                'role' => 'member',
                'default_account' => false,
                'password' => bcrypt('123123123'),
                'created_by' => 1,
            ],

            [
                'id' => 3,
                'email' => 'vanessa@gmail.com',
                'role' => 'player',
                'default_account' => false,
                'password' => bcrypt('123123123'),
                'created_by' => 1,
            ]
        ]);

        UserProfile::insert([
            [
                'user_profile_id' => 1,
                'user_id' => 1,
                'first_name' => 'Developer',
                'middle_name' => 'Istrator',
                'last_name' => 'Valley Golf',
                'birthdate' => '1990-01-01',
                'sex' => 'M',
                'user_desc' => 'Developer Account',
                'remarks' => 'This is a developer account',
                'phone' => '1234567890',
                'address' => '123 Test St, Test City',
                'avatar' => null,
                'created_by' => 1,
            ],

            [
                'user_profile_id' => 2,
                'user_id' => 2,
                'first_name' => 'Froilan',
                'middle_name' => 'Almazan',
                'last_name' => 'Asuncion',
                'birthdate' => '1994-10-10',
                'sex' => 'M',
                'user_desc' => 'Professional Golfer',
                'remarks' => 'Make sure to call the Golf Association for WHS registration.',
                'phone' => '099557033836',
                'address' => '407A Narra St., Agapito Subd., Pasig City',
                'avatar' => null,
                'created_by' => 1,
            ],

            [
                'user_profile_id' => 3,
                'user_id' => 3,
                'first_name' => 'Nhesa',
                'middle_name' => 'Asuncion',
                'last_name' => 'Sumabat',
                'birthdate' => '1991-04-01',
                'sex' => 'F',
                'user_desc' => 'Junior Golfer',
                'remarks' => 'Give special discount on tournament fees.',
                'phone' => '09540469925',
                'address' => 'Caabatacan East, Lasam, Cagayan',
                'avatar' => null,
                'created_by' => 1,
            ]
        ]);

        Member::insert([
            [
                'member_id' => 1,
                'user_id' => 2,
                'member_no' => '0001',
                'created_by' => 1,
            ]
        ]);



        PlayerProfile::insert([
            [
                'player_profile_id' => 1,
                'user_id' => 2,
                'user_profile_id' => 2,
                'member_id' => 1,
                'account_no' => '0001-00',
                'whs_no' => 1111,
                'created_by' => 1,
            ],


            [
                'player_profile_id' => 2,
                'user_id' => 3,
                'user_profile_id' => 3,
                'member_id' => 1,
                'account_no' => '0001-02',
                'whs_no' => 2222,
                'created_by' => 1,
            ]
        ]);






        $this->call([
            CourseSeeder::class,
            TeeSeeder::class,
            ScorecardSeeder::class,
            ScorecardHoleSeeder::class,
            ScorecardYardSeeder::class,
            ScorecardStokeIndexSeeder::class,
            CourseRatingSeeder::class,
            SlopeRatingSeeder::class,

            TournamentSeeder::class,
            TournamentCourseSeeder::class,
            FormulaTypeSeeder::class,
            FormulaSeeder::class,
        ]);
    }
}
