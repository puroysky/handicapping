<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scorecard extends Model
{


    public function holes()
    {
        return $this->hasMany(ScorecardHole::class, 'scorecard_id', 'scorecard_id');
    }

    public function courseRatings()
    {
        return $this->hasMany(CourseRating::class, 'scorecard_id', 'scorecard_id');
    }

    public function slopeRatings()
    {
        return $this->hasMany(SlopeRating::class, 'scorecard_id', 'scorecard_id');
    }

    public function yardages()
    {
        return $this->hasMany(ScorecardYard::class, 'scorecard_id', 'scorecard_id');
    }

    public function handicaps()
    {
        return $this->hasMany(ScorecardHoleHandicap::class, 'scorecard_id', 'scorecard_id');
    }
}
