<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Base
{
    use HasFactory;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
