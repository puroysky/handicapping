<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantCourse extends Model
{

    protected $fillable = [
        'participant_id',
        'course_id',
        'course_handicap',
        'final_course_handicap',
        'tournament_id',
        'created_by',
        'updated_by'
    ];
}
