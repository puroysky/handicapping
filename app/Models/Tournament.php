<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{

    protected $primaryKey = 'tournament_id';


    public function tournamentCourses()
    {
        return $this->hasMany(TournamentCourse::class, 'tournament_id', 'tournament_id');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class, 'tournament_id', 'tournament_id');
    }
}
