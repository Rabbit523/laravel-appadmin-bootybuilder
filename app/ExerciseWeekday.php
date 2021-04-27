<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExerciseWeekday extends Model
{
    //
    protected $table = 'exercise_weekday';

    protected $fillable = [
        'exercise_id',
        'weekday_id'
    ];
}
