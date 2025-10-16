<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{

    protected $fillable = [
        'formula_name',
        'formula_code',
        'formula_desc',
        'formula_expression',
        'formula_variables',
        'formula_type_id',
        'active',
        'created_by',
        'updated_by',

    ];
}
