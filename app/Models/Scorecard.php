<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scorecard extends Model
{

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function scorecardHoles()
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

    public function strokeIndex()
    {
        return $this->hasMany(ScorecardStrokeIndex::class, 'scorecard_id', 'scorecard_id');
    }

    public function handicapHole()
    {
        return $this->hasOne(ScorecardStrokeIndex::class, 'scorecard_id', 'scorecard_id');
    }
}
