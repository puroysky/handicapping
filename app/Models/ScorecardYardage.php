<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScorecardYardage extends Model
{

    public function hole()
    {
        return $this->belongsTo(ScorecardHole::class, 'scorecard_hole_id', 'scorecard_hole_id');
    }
}
