<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public function tee()
    {
        return $this->hasOne(Tee::class, 'tee_id', 'tee_id');
    }
}
