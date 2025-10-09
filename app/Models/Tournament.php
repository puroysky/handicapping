<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{

    protected $primaryKey = 'tournament_id';


    public function courses()
    {
        return $this->hasMany(TournamentCourse::class, 'tournament_id', 'tournament_id');
    }
}
