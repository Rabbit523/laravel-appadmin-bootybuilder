<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    //
    protected $fillable = [
        "timer",
        "no_timer"
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
