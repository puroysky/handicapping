<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScorecardHole extends Model
{
    public function yardages()
    {
        return $this->hasMany(ScorecardYardage::class, 'scorecard_hole_id', 'scorecard_hole_id');
    }

    public function yardage()
    {
        return $this->hasOne(ScorecardYardage::class, 'scorecard_hole_id', 'scorecard_hole_id');
    }

    public function strokeIndexes()
    {
        return $this->hasMany(ScorecardStrokeIndex::class, 'scorecard_hole_id', 'scorecard_hole_id');
    }

    public function strokeIndex()
    {
        return $this->hasOne(ScorecardStrokeIndex::class, 'scorecard_hole_id', 'scorecard_hole_id');
    }

    public function scorecard()
    {
        return $this->belongsTo(Scorecard::class, 'scorecard_id', 'scorecard_id');
    }
}
