<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'file',
        'checked_qrcode',
        'published'
    ];

    public function tags() {
        return $this->belongsToMany(Tag::class, 'category_tag', 'category_id', 'tag_id');
    }

    public function count_exercises() {
        $tags = $this->tags;
        $cnt = 0;
        foreach ($tags as $tag) {
            if ($tag->has_subtags) {
                foreach ($tag->subtags as $subtag) {
                    $cnt += $subtag->count_exercises();
                }
            } else {
                $cnt += $tag->count_exercises();
            }
        }
        return $cnt;
    }
}
