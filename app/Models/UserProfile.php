<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'birthdate',
        'sex',
        'user_desc',
        'remarks',
        'phone',
        'address',
        'avatar',
        'created_by',
    ];
}
