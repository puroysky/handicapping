<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScorecardHole extends Model
{
    public function yardages()
    {
        return $this->hasMany(ScorecardYard::class, 'scorecard_hole_id', 'scorecard_hole_id');
    }

    public function yardage()
    {
        return $this->hasOne(ScorecardYard::class, 'scorecard_hole_id', 'scorecard_hole_id');
    }
}
