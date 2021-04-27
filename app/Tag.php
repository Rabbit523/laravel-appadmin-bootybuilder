<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $fillable = [
      "name",
      "has_subtags",
      "parent_id",
    ];

    protected $appends = [
      'title',
    ];

    public function subtags() {
        return $this->hasMany(Tag::class, 'parent_id');
    }

    public function exercises() {
        return $this->belongsToMany(Exercise::class, 'exercise_tag', 'tag_id', 'exercise_id');
    }

    public function count_exercises() {
        return $this->exercises()->count();
    }

    public function getTitleAttribute() {
        return $this->name;
    }

    public function getHasSubtagsAttribute($value) {
        return $value ? true : false;
    }
}
