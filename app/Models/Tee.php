<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tee extends Model
{
    protected $table = 'tees';
    protected $primaryKey = 'tee_id';


    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
}
