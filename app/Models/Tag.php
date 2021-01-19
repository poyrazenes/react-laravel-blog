<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Base
{
    use HasFactory;

    public function posts()
    {
        return $this->belongsToMany(
            Post::class,
            'post_tag',
            'tag_id',
            'post_id'
        );
    }
}
