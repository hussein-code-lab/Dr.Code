<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'trade_name',
        'active_ingredient',
        'dosage_form',
        'default_dosage',
        'default_instructions',
    ];
}
