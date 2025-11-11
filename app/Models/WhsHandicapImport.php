<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhsHandicapImport extends Model
{
    protected $table = 'whs_handicap_imports';

    protected $primaryKey = 'whs_handicap_import_id';

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id', 'tournament_id');
    }
}
