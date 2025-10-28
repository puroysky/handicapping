<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantCourseHandicap extends Model
{
    protected $table = 'participant_course_handicaps';
    protected $primaryKey = 'participant_course_handicap_id';


    //fillable
    protected $fillable = [
        'tournament_id',
        'participant_id',
        'course_id',
        'tee_id',
        'created_by',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function tee()
    {
        return $this->belongsTo(Tee::class, 'tee_id', 'tee_id');
    }
}
