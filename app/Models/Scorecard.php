<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scorecard extends Model
{


    protected $table = 'scorecards';
    protected $primaryKey = 'scorecard_id';


    protected $fillable = [
        'scorecard_code',
        'scorecard_name',
        'scorecard_desc',
        'adjusted_gross_score_formula_id',
        'score_differential_formula_id',
        'course_handicap_formula_id',
        'course_id',
        'x_value',
        'active',
        'created_by',
    ];

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


    public function adjustedGrossScoreFormula()
    {
        return $this->hasOne(Formula::class, 'formula_id', 'adjusted_gross_score_formula_id');
    }


    public function scoreDifferentialFormula()
    {
        return $this->hasOne(Formula::class, 'formula_id', 'score_differential_formula_id');
    }

    public function courseHandicapFormula()
    {
        return $this->hasOne(Formula::class, 'formula_id', 'course_handicap_formula_id');
    }
}
