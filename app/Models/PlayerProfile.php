<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'user_profile_id',
        'account_no',
        'whs_no',
        'created_by',
    ];
}
