<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scorecard extends Model
{


    protected $table = 'scorecards';
    protected $primaryKey = 'scorecard_id';

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function scorecardHoles()
    {
        return $this->hasMany(ScorecardHole::class, 'scorecard_id', 'scorecard_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'scorecard_id', 'scorecard_id');
    }

    public function rating()
    {
        return $this->hasOne(Rating::class, 'scorecard_id', 'scorecard_id');
    }



    public function yardages()
    {
        return $this->hasMany(ScorecardYardage::class, 'scorecard_id', 'scorecard_id');
    }

    public function strokeIndex()
    {
        return $this->hasMany(ScorecardStrokeIndex::class, 'scorecard_id', 'scorecard_id');
    }

    public function strokeIndexes()
    {
        return $this->hasMany(ScorecardStrokeIndex::class, 'scorecard_id', 'scorecard_id');
    }

    public function handicapHole()
    {
        return $this->hasOne(ScorecardStrokeIndex::class, 'scorecard_id', 'scorecard_id');
    }



    public function courseRatingFormula()
    {
        return $this->hasOne(Formula::class, 'formula_id', 'course_rating_formula_id');
    }
}
