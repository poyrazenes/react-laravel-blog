<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostTag extends Base
{
    use HasFactory;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
