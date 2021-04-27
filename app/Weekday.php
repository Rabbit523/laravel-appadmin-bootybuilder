<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Weekday extends Model
{
    //
    protected $fillable = [
        "name",
        "workout_id"
    ];

    protected $appends = [
        "title",
        "exercises"
    ];

    public function getTitleAttribute() {
        return $this->name;
    }

    public function exercises() {
        return $this->belongsToMany(Exercise::class, 'exercise_weekday', 'weekday_id', 'exercise_id');
    }

    public function getExercisesAttribute() {
        return $this->exercises()->get();
    }

}
