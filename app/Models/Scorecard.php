<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scorecard extends Model
{


    public function scorecardDetails()
    {
        return $this->hasMany(ScorecardDetail::class, 'scorecard_id', 'scorecard_id');
    }

    public function courseRatings()
    {
        return $this->hasMany(CourseRating::class, 'scorecard_id', 'scorecard_id');
    }

    public function slopeRatings()
    {
        return $this->hasMany(SlopeRating::class, 'scorecard_id', 'scorecard_id');
    }
    public function scorecardPars()
    {
        return $this->hasMany(ScorecardPar::class, 'scorecard_id', 'scorecard_id');
    }
}
