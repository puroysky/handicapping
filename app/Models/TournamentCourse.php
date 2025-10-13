<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentCourse extends Model
{

    protected $fillable = [
        'tournament_id',
        'course_id',
        'created_by',
        'updated_by',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id', 'tournament_id');
    }

    public function scorecard()
    {
        return $this->belongsTo(Scorecard::class, 'scorecard_id', 'scorecard_id');
    }
}
