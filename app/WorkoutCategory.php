<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkoutCategory extends Model
{
    //
    protected $fillable = [
        'title',
        'description'
    ];

    protected $hidden = [
        'description'
    ];

    public function workouts()
    {
        return $this->hasMany(Workout::class, 'category_id', 'id');
    }
}
