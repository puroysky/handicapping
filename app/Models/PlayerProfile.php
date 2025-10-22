<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerProfile extends Model
{

    protected $primaryKey = 'player_profile_id';
    protected $table = 'player_profiles';

    protected $fillable = [
        'user_id',
        'user_profile_id',
        'account_no',
        'whs_no',
        'created_by',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id', 'user_profile_id');
    }
}
