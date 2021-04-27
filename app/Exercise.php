<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        "title",
        "description",
        "series",
        "repetitions",
        "standalone",
        "format_id",
        "file",
        "thumbnail",
        "video_length",
        "views",
        "published"
    ];

    protected $appends = ['video_format'];
    protected $hidden = [
        'format',
    ];

    public function getVideoFormatAttribute()
    {
        return $this->format;
    }

    public function format() {
        return $this->belongsTo(Format::class);
    }
}
