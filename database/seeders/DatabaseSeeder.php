<?php

namespace Database\Seeders;

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

        User::create([
            'id' => 1,
            'email' => 'test@example.com',
            'role' => 'admin',
            'default_account' => true,
            'password' => bcrypt('123123123'),
            'created_by' => 1,
        ]);

        UserProfile::create([
            'user_profile_id' => 1,
            'user_id' => 1,
            'first_name' => 'Admin',
            'middle_name' => 'Istrator',
            'last_name' => 'User',
            'birthdate' => '1990-01-01',
            'sex' => 'M',
            'user_desc' => 'Test User Admin',
            'remarks' => 'No remarks',
            'phone' => '1234567890',
            'address' => '123 Test St, Test City',
            'avatar' => null,
            'created_by' => 1,
        ]);


        PlayerProfile::create([
            'player_profile_id' => 1,
            'user_id' => 1,
            'user_profile_id' => 1,
            'account_no' => 'ACC1001',
            'whs_no' => 1001,
            'created_by' => 1,
        ]);






        $this->call([
            CourseSeeder::class,
            TeeSeeder::class,
            ScorecardSeeder::class,
            ScorecardHoleSeeder::class,
            ScorecardYardSeeder::class,
            ScorecardHandicapHoleSeeder::class,
            CourseRatingSeeder::class,
            SlopeRatingSeeder::class,

            TournamentSeeder::class,
            TournamentCourseSeeder::class,
        ]);
    }
}
