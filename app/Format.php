<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    public function exercises() {
        return $this->hasMany(Exercise::class);
    }
}
