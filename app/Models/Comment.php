<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Base
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id', 'comment', 'approved'];

    protected $appends = ['date_formatted'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDateFormattedAttribute()
    {
        return Carbon::parse($this->created_at)
            ->format('Y/m/d h:i a');
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }
}
