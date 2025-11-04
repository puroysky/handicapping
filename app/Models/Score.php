<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    // Primary key
    protected $primaryKey = 'score_id';

    //Fillable fields
    protected $fillable = [
        'player_profile_id',
        'user_profile_id',
        'user_id',
        'tournament_id',
        'tournament_course_id',
        'tee_id',
        'scoring_method',
        'score_date',
        'entry_type',
        'gross_score',
        'adjusted_score',
        'net_score',
        'side',
        'is_verified',
        'verified_by',
        'verified_at',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'remarks'
    ];


    public function scoreHoles()
    {
        return $this->hasMany(ScoreHole::class, 'score_id', 'score_id');
    }

    public function playerProfile()
    {
        return $this->belongsTo(PlayerProfile::class, 'player_profile_id', 'player_profile_id');
    }

    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id', 'user_profile_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id', 'tournament_id');
    }

    public function tournamentCourse()
    {
        return $this->belongsTo(TournamentCourse::class, 'tournament_course_id', 'tournament_course_id');
    }


    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
    public function tee()
    {
        return $this->belongsTo(Tee::class, 'tee_id', 'tee_id');
    }
}
