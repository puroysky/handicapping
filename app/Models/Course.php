<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $primaryKey = 'course_id';
    protected $table = 'courses';

    public function scorecards()
    {
        return $this->hasMany(Scorecard::class, 'course_id', 'course_id');
    }

    public function tees()
    {
        return $this->hasMany(Tee::class, 'course_id', 'course_id');
    }
}
