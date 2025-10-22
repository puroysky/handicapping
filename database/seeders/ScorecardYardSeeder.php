<?php

namespace Database\Seeders;

use App\Models\ScorecardYard;
use App\Models\ScorecardYardage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScorecardYardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ScorecardYardage::insert([
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 1,
                'tee_id' => 1,
                'yardage' => 360,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 2,
                'yardage' => 346,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 3,
                'yardage' => 192,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 4,
                'yardage' => 130,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 5,
                'yardage' => 521,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 6,
                'yardage' => 164,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 7,
                'yardage' => 476,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 8,
                'yardage' => 156,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 9,
                'yardage' => 469,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 10,
                'yardage' => 418,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 11,
                'yardage' => 151,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 12,
                'yardage' => 145,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 13,
                'yardage' => 475,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 14,
                'yardage' => 288,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 15,
                'yardage' => 155,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 16,
                'yardage' => 427,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 17,
                'yardage' => 382,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 18,
                'yardage' => 348,
                'tee_id' => 1,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 1,
                'tee_id' => 2,
                'yardage' => 337,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 2,
                'yardage' => 321,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 3,
                'yardage' => 169,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 4,
                'yardage' => 120,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 5,
                'yardage' => 495,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 6,
                'yardage' => 139,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 7,
                'yardage' => 409,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 8,
                'yardage' => 125,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 9,
                'yardage' => 452,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 10,
                'yardage' => 397,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 11,
                'yardage' => 137,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 12,
                'yardage' => 127,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 13,
                'yardage' => 465,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 14,
                'yardage' => 267,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 15,
                'yardage' => 137,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 16,
                'yardage' => 411,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 17,
                'yardage' => 327,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 18,
                'yardage' => 313,
                'tee_id' => 2,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 1,
                'tee_id' => 3,
                'yardage' => 313,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 2,
                'yardage' => 268,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 3,
                'yardage' => 138,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 4,
                'yardage' => 110,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 5,
                'yardage' => 481,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 6,
                'yardage' => 114,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 7,
                'yardage' => 335,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 8,
                'yardage' => 112,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 9,
                'yardage' => 416,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 10,
                'yardage' => 376,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 11,
                'yardage' => 122,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 12,
                'yardage' => 109,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 13,
                'yardage' => 390,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 14,
                'yardage' => 257,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 15,
                'yardage' => 126,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 16,
                'yardage' => 389,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 17,
                'yardage' => 299,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 1,
                'scorecard_hole_id' => 18,
                'yardage' => 292,
                'tee_id' => 3,
                'created_by' => 1, // Assuming admin user ID is 1
            ],

            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 19,
                'tee_id' => 4,
                'yardage' => 416,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 20,
                'yardage' => 453,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 21,
                'yardage' => 373,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 22,
                'yardage' => 202,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 23,
                'yardage' => 545,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 24,
                'yardage' => 412,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 25,
                'yardage' => 501,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 26,
                'yardage' => 223,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 27,
                'yardage' => 375,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 28,
                'yardage' => 358,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 29,
                'yardage' => 504,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 30,
                'yardage' => 197,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 31,
                'yardage' => 433,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 32,
                'yardage' => 426,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 33,
                'yardage' => 401,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 34,
                'yardage' => 420,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 35,
                'yardage' => 538,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 36,
                'yardage' => 211,
                'tee_id' => 4,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 19,
                'tee_id' => 5,
                'yardage' => 402,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 20,
                'yardage' => 438,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 21,
                'yardage' => 361,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 22,
                'yardage' => 193,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 23,
                'yardage' => 525,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 24,
                'yardage' => 395,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 25,
                'yardage' => 486,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 26,
                'yardage' => 209,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 27,
                'yardage' => 365,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 28,
                'yardage' => 318,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 29,
                'yardage' => 486,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 30,
                'yardage' => 183,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 31,
                'yardage' => 420,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 32,
                'yardage' => 413,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 33,
                'yardage' => 385,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 34,
                'yardage' => 404,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 35,
                'yardage' => 524,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 36,
                'yardage' => 195,
                'tee_id' => 5,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 19,
                'tee_id' => 6,
                'yardage' => 375,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 20,
                'yardage' => 410,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 21,
                'yardage' => 338,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 22,
                'yardage' => 167,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 23,
                'yardage' => 505,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 24,
                'yardage' => 370,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 25,
                'yardage' => 458,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 26,
                'yardage' => 186,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 27,
                'yardage' => 345,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 28,
                'yardage' => 287,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 29,
                'yardage' => 474,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 30,
                'yardage' => 166,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 31,
                'yardage' => 392,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 32,
                'yardage' => 391,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 33,
                'yardage' => 360,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 34,
                'yardage' => 378,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 35,
                'yardage' => 501,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 36,
                'yardage' => 170,
                'tee_id' => 6,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 19,
                'tee_id' => 7,
                'yardage' => 352,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 20,
                'yardage' => 333,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 21,
                'yardage' => 323,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 22,
                'yardage' => 144,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 23,
                'yardage' => 480,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 24,
                'yardage' => 349,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 25,
                'yardage' => 421,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 26,
                'yardage' => 165,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 27,
                'yardage' => 324,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 28,
                'yardage' => 263,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 29,
                'yardage' => 458,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 30,
                'yardage' => 135,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 31,
                'yardage' => 348,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 32,
                'yardage' => 356,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 33,
                'yardage' => 341,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 34,
                'yardage' => 322,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 35,
                'yardage' => 481,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'scorecard_id' => 2,
                'scorecard_hole_id' => 36,
                'yardage' => 154,
                'tee_id' => 7,
                'created_by' => 1, // Assuming admin user ID is 1
            ]
        ]);
    }
}
