<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExerciseTag extends Model
{
    //
    protected $table = "exercise_tag";

    protected $fillable = [
        "exercise_id",
        "tag_id"
    ];
}
