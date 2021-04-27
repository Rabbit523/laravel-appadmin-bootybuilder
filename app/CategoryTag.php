<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryTag extends Model
{
    protected $table = "category_tag";

    //
    protected $fillable = [
        "category_id",
        "tag_id"
    ];
}
