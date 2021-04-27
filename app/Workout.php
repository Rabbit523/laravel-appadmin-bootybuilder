<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    //
    protected $fillable = [
        "title",
        "description",
        "amount_weeks_program",
        "subscribed",
        "has_timer",
        "productId",
        "file",
        "published",
        "category_id",
        "level1_work",
        "level1_rest",
        "level1_rounds",
        "level2_work",
        "level2_rest",
        "level2_rounds",
        "level3_work",
        "level3_rest",
        "level3_rounds",
    ];

    protected $hidden = [
        "category_id"
    ];

    protected $appends = [
        "category"
    ];

    public function getCategoryAttribute() {
        return $this->workout_category;
    }

    public function workout_category() {
        return $this->belongsTo(WorkoutCategory::class, 'category_id');
    }

    public function weekdays() {
        return $this->hasMany(Weekday::class);
    }
}
