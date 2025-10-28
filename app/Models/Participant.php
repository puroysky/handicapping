<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{

    protected $table = 'participants';
    protected $primaryKey = 'participant_id';

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id', 'tournament_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function playerProfile()
    {
        return $this->belongsTo(PlayerProfile::class, 'player_profile_id', 'player_profile_id');
    }

    public function participantCourseHandicaps()
    {
        return $this->hasMany(ParticipantCourseHandicap::class, 'participant_id', 'participant_id');
    }
}
